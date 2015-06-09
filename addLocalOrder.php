<?php
    if(canRead() && canCreate() && canUpdate()) {
        $staffID = $_SESSION['STAFFID'];
        $week = getCurrentWeekNumber();
        $year = getCurrentYear();
        $searchCriteria = '';

        if(isset($_POST["SearchItems"])) {
            $searchCriteria = $_POST["SearchItems"];
        }

        $itemstmt = $dbh -> prepare("EXEC proc_searchItems ?, ?");
        $itemstmt -> bindParam(1, $staffID);
        $itemstmt -> bindParam(2, $searchCriteria);
        $itemstmt -> execute();
        $items = $itemstmt -> fetchAll();

        $itemsInLocalOrder = array();

        if(isset($_POST['btnBestellijstBekijken'])) {
            $getLocalOrderStatement = $dbh -> prepare("proc_getLocalOrder ?, ?, ?");
            $getLocalOrderStatement -> bindParam(1, $staffID);
            $getLocalOrderStatement -> bindParam(2, $year);
            $getLocalOrderStatement -> bindParam(3, $week);
            $getLocalOrderStatement -> execute();
            $currentItemsInLocalOrder = $getLocalOrderStatement -> fetchAll();

            foreach($currentItemsInLocalOrder as $currentItemInLocalOrder) {
                $itemToArray = $currentItemInLocalOrder['ItemID']. ';' . $currentItemInLocalOrder['ItemName'] . ';' . $currentItemInLocalOrder['Unit']. ';' . $currentItemInLocalOrder['Amount'];
                array_push($itemsInLocalOrder, $itemToArray);
            }
        }

        if(isset($_POST['itemsInLocalOrder']))  {
            $serializedItemsInLocalOrder = $_POST['itemsInLocalOrder'];
            $itemsInLocalOrder = unserialize(base64_decode($serializedItemsInLocalOrder));
        }

        if(isset($_POST['btnAddItem'])){
            $itemID = $_POST['itemID'];
            $itemName = $_POST['itemName'];
            $itemAmount = $_POST['amountForItem'];
            $itemUnit = $_POST['itemUnit'];

            foreach($itemsInLocalOrder as $itemInLocalOrder) {
                $propertiesInItemInLocalOrder = explode(';', $itemInLocalOrder);
                $itemInLocalOrderID = $propertiesInItemInLocalOrder[0];

                if($itemInLocalOrderID == $itemID) {
                    $itemInOrderAmount = $propertiesInItemInLocalOrder[3];
                    $itemAmount = $itemInOrderAmount + $itemAmount;

                    $index = array_search($itemInLocalOrder, $itemsInLocalOrder);
                    if($index !== false){
                        unset($itemsInLocalOrder[$index]);
                    }
                }
            }

            $itemToArray = $itemID. ';' . $itemName . ';' . $itemUnit. ';' . $itemAmount;
            array_push($itemsInLocalOrder, $itemToArray);
        }

        if(isset($_POST['btnRemoveItem'])){
            $itemID = $_POST['itemID'];

            foreach($itemsInLocalOrder as $itemInLocalOrder) {
                $propertiesInItemInLocalOrder = explode(';', $itemInLocalOrder);
                $itemInLocalOrderID = $propertiesInItemInLocalOrder[0];

                if($itemInLocalOrderID == $itemID) {
                    $index = array_search($itemInLocalOrder, $itemsInLocalOrder);
                    if($index !== false){
                        unset($itemsInLocalOrder[$index]);
                    }
                }
            }
        }

        if(isset($_POST['btnPlaceLocalOrder'])){
            $retVal = '';
            foreach($itemsInLocalOrder as $itemInLocalOrder) {
                $propertiesInItemInLocalOrder = explode(';', $itemInLocalOrder);

                $itemID = $propertiesInItemInLocalOrder[0];
                $itemAmount = $propertiesInItemInLocalOrder[3];

                $retVal = $retVal . $itemID . ';' . $itemAmount . '"';
            }

            $placeOrderStatement = $dbh -> prepare('proc_alterInternalOrder ?, ?');
            $placeOrderStatement -> bindParam(1, $staffID);
            $placeOrderStatement -> bindParam(2, $retVal);
            $placeOrderStatement -> execute();

            spErrorCaching($placeOrderStatement);
        }

        $serializedItemsInLocalOrder = base64_encode(serialize($itemsInLocalOrder));
    ?>
    <h1>Bestellijst maken</h1>
    <h4>Week: <?php echo $week ?></h4>
    <form action="index.php?page=addLocalOrder" method="post">
        <div class="col-lg-6">
            <div class="input-group">
                <input name="SearchItems" type="text" class="form-control" placeholder="Zoeken">
                  <span class="input-group-btn">
                    <input type="hidden" name="itemsInLocalOrder" value="<?php echo $serializedItemsInLocalOrder ?>">
                    <button class="btn btn-default" type="submit">Zoeken</button>
                  </span>
            </div>
        </div>
    </form>
    <br><br>
    <div class="row">
        <div class="col-lg-6">
            <table class="table table-hover">
                <tr>
                    <th>Nummer</th>
                    <th>Naam</th>
                    <th>Eenheid</th>
                    <th>Aantal</th>
                    <th></th>
                </tr>
                <?php
                foreach($items as $item) { ?>
                    <tr>
                        <form action="index.php?page=addLocalOrder" method="post">
                            <td><?php echo $item['ItemID'] ?></td>
                            <td><?php echo $item['ItemName'] ?></td>
                            <td><?php echo $item['Unit'] ?></td>
                            <td><input class="form-control" type="number" min="1" value="1" max="2147483646" name="amountForItem"></td>
                            <td>
                                <input type="hidden" name="itemsInLocalOrder" value="<?php echo $serializedItemsInLocalOrder ?>">
                                <input type="hidden" name="itemID" value="<?php echo $item['ItemID'] ?>">
                                <input type="hidden" name="itemName" value="<?php echo $item['ItemName'] ?>">
                                <input type="hidden" name="itemUnit" value="<?php echo $item['Unit'] ?>">

                                <button type="submit" name="btnAddItem" class="btn btn-link" aria-label="Left Align">
                                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" />
                                </button>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="col-lg-6">
            <h4>Items in bestellijst</h4>
            <table class="table table-hover">
                <tr>
                    <th>Nummer</th>
                    <th>Naam</th>
                    <th>Eenheid</th>
                    <th>Aantal</th>
                    <th></th>
                </tr>
                <?php foreach($itemsInLocalOrder as $itemInLocalOrder) {
                    $propertiesInLocalOrderItems = explode(';', $itemInLocalOrder);

                    $itemInLocalOrderID = $propertiesInLocalOrderItems[0];
                    $itemInLocalOrderName = $propertiesInLocalOrderItems[1];
                    $itemInLocalOrderUnit = $propertiesInLocalOrderItems[2];
                    $itemInOrderLocalAmount = $propertiesInLocalOrderItems[3];
                    ?>
                    <tr>
                        <td><?php echo $itemInLocalOrderID ?></td>
                        <td><?php echo $itemInLocalOrderName ?></td>
                        <td><?php echo $itemInLocalOrderUnit ?></td>
                        <td><?php echo $itemInOrderLocalAmount ?></td>
                        <td>
                            <form action="index.php?page=addLocalOrder" method="post">
                                <input type="hidden" name="itemID" value="<?php echo $itemInLocalOrderID ?>">
                                <input type="hidden" name="itemsInLocalOrder" value="<?php echo $serializedItemsInLocalOrder ?>">
                                <button type="submit" name="btnRemoveItem" class="btn btn-link" aria-label="Left Align">
                                    <span class="glyphicon glyphicon-remove-sign" aria-hidden="true" />
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <form action="index.php?page=addLocalOrder" method="post">
            <input type="hidden" name="itemsInLocalOrder" value="<?php echo $serializedItemsInLocalOrder ?>">
            <button class="btn btn-primary" type="submit" name="btnPlaceLocalOrder">Opslaan</button>
        </form>
    </div>
<?php }?>