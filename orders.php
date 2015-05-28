<?php

    $staffID = $_SESSION['STAFFID'];
    $currentOrderStatus = 'Geplaatst';

    if(isset($_POST['btnZoeken']))
    {
        $searchCriteria = $_POST['SearchCriteria'];
        $currentOrderStatus = $_POST['OrderStatus'];

        $searchOrdersStatement = $dbh -> prepare("proc_searchOrders ?, ?, ?");
        $searchOrdersStatement -> bindParam(1, $staffID);
        $searchOrdersStatement -> bindParam(2, $searchCriteria);
        $searchOrdersStatement -> bindParam(3, $currentOrderStatus);
        $searchOrdersStatement -> execute();
        $orders = $searchOrdersStatement -> fetchAll();
    }
    else
    {
        $getOrdersStatement = $dbh -> prepare("proc_getOrders ?");
        $getOrdersStatement -> bindParam(1, $currentOrderStatus);
        $getOrdersStatement -> execute();
        $orders = $getOrdersStatement -> fetchAll();
    }

    $getOrderStatusStatement = $dbh -> prepare('proc_getOrderStates');
    $getOrderStatusStatement -> execute();
    $orderStates = $getOrderStatusStatement -> fetchAll();
?>
<h1>Order overzicht</h1>
<hr>
<form action="index.php?page=orders" method="post">
    <div class="input-group">
        <input name="SearchCriteria" type="text" class="form-control" placeholder="Zoeken op ordernummer, datum of medewerker">
          <span class="input-group-btn">
            <button class="btn btn-default" name="btnZoeken" type="submit">Zoeken</button>
          </span>
    </div>
    <br />
    <h4>Status:</h4>
    <select name="OrderStatus" type="text" class="form-control" value="<?php echo $currentOrderStatus ?>">
        <?php
        foreach($orderStates as $orderState){
            ?>
            <option value="<?php echo $orderState['OrderState'] ?>" <?php if($orderState['OrderState'] == $currentOrderStatus){ echo 'selected';} ?>><?php echo $orderState['OrderState'] ?></option>
        <?php
        }
        ?>
    </select>
</form>
<br />
<table class="table table-hover" >
    <tr>
        <th>Ordernummer</th>
        <th>Datum</th>
        <th>Medewerker</th>
        <th>Status</th>
        <th>Prijs</th>
        <?php
            if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
                echo '<th></th>';
            }
        ?>
    </tr>

    <?php
        foreach($orders as $order){
            ?>
            <tr>
                <td><?php echo $order['OrderID'] ?></td>
                <td><?php $date = new DateTime($order['OrderDate']); echo $date -> format('Y-m-d H:i') ?></td>
                <td><?php echo $order['StaffName'] ?></td>
                <td><?php echo $order['OrderState'] ?></td>
                <td><?php echo '€ ' .round($order['Price'], 2) ?></td>

                <?php
                    if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
                    ?>
                        <td>
                            <form action="index.php?page=alterOrder" method="post">
                                <input type="hidden" name="orderID" value="<?php echo $order['OrderID']?>">
                                <button type="submit" class="btn btn-default">Bewerken</button>
                            </form>
                        </td>
                    <?php
                    }
                ?>
            </tr>
            <?php
        }
    ?>
</table>

<form action="index.php?page=addOrder" method="post">
    <button type="submit" class="btn btn-default">Toevoegen</button>
</form>