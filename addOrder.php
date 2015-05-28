<?php
    $staffID = $_SESSION['STAFFID'];
    $searchCriteria = '';
    $selectedLeverancier = '';
    $leverancierIsActive = 1;
    $itemsInOrder = array();

    if(isset($_POST['SelectedLeverancier']))
        $selectedLeverancier = $_POST['SelectedLeverancier'];

    //  Get all suppliers
    $getLeveranciersStatement = $dbh -> prepare('EXEC proc_searchLeveranciers ?, ?, ?');
    $getLeveranciersStatement -> bindParam(1, $staffID);
    $getLeveranciersStatement -> bindParam(2, $searchCriteria);
    $getLeveranciersStatement -> bindParam(3, $leverancierIsActive);
    $getLeveranciersStatement -> execute();
    $leveranciers = $getLeveranciersStatement -> fetchAll();

    if(!isset($_POST['SelectedLeverancier']) && isset($leveranciers[0]['SupplierName']))
        $selectedLeverancier = $leveranciers[0]['SupplierName'];

    // Get all items for Supplier
    $getItemsForSupplierStatement = $dbh -> prepare('proc_getItemsForSupplier ?');
    $getItemsForSupplierStatement -> bindParam(1, $selectedLeverancier);
    $getItemsForSupplierStatement -> execute();
    $itemsForSupplier = $getItemsForSupplierStatement -> fetchAll();

    if(isset($_POST['btnAddItem']))  {
        $item = $_POST['itemName'];
        $aantal = $_POST['amountForItem'];
        $prijs = $_POST['priceForItem'];

        $itemToArray = $item . ';' . $aantal. ';' . $prijs;

        if(isset($_POST['SerializedOrders'])) {
            $serializedOrderItems = $_POST['SerializedOrders'];
            $itemsInOrder = unserialize(base64_decode($serializedOrderItems));
        }

        array_push($itemsInOrder, $itemToArray);
    }

    if(isset($_POST['btnRemoveItem'])) {
        $item = $_POST['itemName'];
        $aantal = $_POST['amountForItem'];
        $prijs = $_POST['priceForItem'];


        $itemToArray = $item . ';' . $aantal . ';' . $prijs;

        if (isset($_POST['SerializedOrders'])) {
            $serializedOrderItems = $_POST['SerializedOrders'];
            $itemsInOrder = unserialize(base64_decode($serializedOrderItems));
        }

        $index = array_search($itemToArray, $itemsInOrder);
        if($index !== false){
            unset($itemsInOrder[$index]);
        }
    }

    $serializedOrderItems = base64_encode(serialize($itemsInOrder));

    if(isset($_POST['btnPlaceOrder'])){

        $comment = '';
        if(isset($_POST['OrderComments']))
            $comment = $_POST['OrderComments'];

        $serializedItemsInOrder = '';

        if (isset($_POST['SerializedOrders'])) {
            $serializedOrderItems = $_POST['SerializedOrders'];
            $serializedItemsInOrder = base64_decode($serializedOrderItems);
        }

        $placeOrderStatement = $dbh -> prepare('proc_addOrder ?, ?, ?, ?');
        $placeOrderStatement -> bindParam(1, $staffID);
        $placeOrderStatement -> bindParam(2, $selectedLeverancier);
        $placeOrderStatement -> bindParam(3, $comment);
        $placeOrderStatement -> bindParam(4, $serializedItemsInOrder);
        $placeOrderStatement -> execute();

        spErrorCaching($placeOrderStatement);
    }
?>

Leverancier:
<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
        <?php echo $selectedLeverancier ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
        <?php foreach($leveranciers as $leverancier){ ?>
            <li role="presentation">
                <form action="index.php?page=addOrder" method="post">
                    <button type="submit" class="btn btn-link" name="SelectedLeverancier" value="<?php echo $leverancier['SupplierName']; ?>"><?php echo $leverancier['SupplierName']; ?></button>
                </form>
            </li>
        <?php } ?>
    </ul>
</div>
<br />
<h4>Producten:</h4>
<table class="table table-hover">
    <tr>
        <th>Product</th>
        <th>Aantal</th>
        <th>Prijs</th>
        <th>Eenheid</th>
        <th></th>
    </tr>
    <?php
        foreach($itemsForSupplier as $item) { ?>
            <tr>
                <form action="index.php?page=addOrder" method="post">
                    <td>
                        <?php echo $item['ItemName'] ?>
                    </td>
                    <td>
                        <input type="number" min="1" value="1" name="amountForItem">
                    </td>
                    <td>
                        <input type="number" value="1.00" step="0.01" min="0.01" name="priceForItem">
                    </td>
                    <td>
                        <?php echo $item['Unit'] ?>
                    </td>
                    <td>
                        <input type="hidden" name="SerializedOrders" value="<?php echo $serializedOrderItems ?>">
                        <input type="hidden" name="SelectedLeverancier" value="<?php echo $selectedLeverancier ?>">
                        <input type="hidden" name="itemName" value="<?php echo $item['ItemName'] ?>">
                        <button type="submit" name="btnAddItem" class="btn btn-link" aria-label="Left Align">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" />
                        </button>
                    </td>
                </form>
            </tr>
        <?php }
    ?>
</table>

<h4>Producten in order:</h4>
<table class="table table-hover">
    <tr>
        <th>Product</th>
        <th>Aantal</th>
        <th>Prijs</th>
        <th></th>
    </tr>
    <?php foreach($itemsInOrder as $itemInOrder) {
        $propertiesInItemInOrder = explode(';', $itemInOrder);

        $itemInOrderName = $propertiesInItemInOrder[0];
        $itemInOrderAmount = $propertiesInItemInOrder[1];
        $itemInOrderPrice = $propertiesInItemInOrder[2];
        ?>
        <tr>
            <td> <?php echo $itemInOrderName ?> </td>
            <td> <?php echo $itemInOrderAmount ?> </td>
            <td> <?php echo '€ ' . $itemInOrderPrice ?> </td>
            <td>
                <form action="index.php?page=addOrder" method="post">
                    <input type="hidden" name="itemName" value="<?php echo $itemInOrderName ?>">
                    <input type="hidden" name="amountForItem" value="<?php echo $itemInOrderAmount ?>">
                    <input type="hidden" name="priceForItem" value="<?php echo $itemInOrderPrice ?>">
                    <input type="hidden" name="SelectedLeverancier" value="<?php echo $selectedLeverancier ?>">
                    <input type="hidden" name="SerializedOrders" value="<?php echo $serializedOrderItems ?>">
                    <button type="submit" name="btnRemoveItem" class="btn btn-link" aria-label="Left Align">
                        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true" />
                    </button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<form action="index.php?page=addOrder" method="post">
    <input type="hidden" name="SerializedOrders" value="<?php echo $serializedOrderItems ?>">

    Opmerkingen
    <textarea name="OrderComments" class="form-control" rows="5"></textarea>
    <br />
    <button class="btn btn-primary" type="submit" name="btnPlaceOrder" >Plaatsen</button>
</form>
