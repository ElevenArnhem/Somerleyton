<?php
    $staffID = $_SESSION['STAFFID'];
    if(isset($_POST['orderID'])){
        $orderID = $_POST['orderID'];

        if(isset($_POST['btnSave'])){
            $orderState = $_POST['OrderState'];
            $comment = $_POST['OrderComments'];

            $orderRecievedDate = '';
            if(isset($_POST['OrderRecievedDate'])) {
                $orderRecievedDate = $_POST['OrderRecievedDate'];

                if($orderRecievedDate == '1900-01-01 00:00') {
                    $orderRecievedDate = '';
                }
            }

            $alterOrderStatement = $dbh -> prepare('proc_alterOrder ?, ?, ?, ?, ?');
            $alterOrderStatement -> bindParam(1, $staffID);
            $alterOrderStatement -> bindParam(2, $orderID);
            $alterOrderStatement -> bindParam(3, $orderState);
            $alterOrderStatement -> bindParam(4, $comment);
            $alterOrderStatement -> bindParam(5, $orderRecievedDate);
            $alterOrderStatement -> execute();

            spErrorCaching($alterOrderStatement);
        }

        $getOrderDetailStatement = $dbh -> prepare('proc_getOrderDetails ?, ?');
        $getOrderDetailStatement -> bindParam(1, $staffID);
        $getOrderDetailStatement -> bindParam(2, $orderID);
        $getOrderDetailStatement -> execute();
        $orderDetails = $getOrderDetailStatement -> fetchAll();

        $currentOrderState = $orderDetails[0]['OrderState'];

        $getOrderStatesstmt = $dbh -> prepare('proc_getOrderStates');
        $getOrderStatesstmt->execute();
        $orderStates = $getOrderStatesstmt->fetchAll();
    }

?>
<form action="index.php?page=alterOrder" method="post">
    <div class="row">
        <div class="col-lg-6">
            <h2>Order gegevens</h2>
            <dl class="dl-horizontal">
                <dt>Order nummer</dt><dd><?php echo $orderID ?></dd><br>
                <dt>Geplaatst door</dt><dd><?php echo $orderDetails[0]['StaffName'] ?></dd><br>
                <dt>Datum</dt><dd><?php $date = new DateTime($orderDetails[0]['OrderDate']); echo $date -> format('Y-m-d H:i') ?></dd><br>
                <dt>Leverancier</dt><dd><?php echo $orderDetails[0]['SupplierName'] ?></dd><br>
                <dt>Telefoon nummer</dt><dd><?php echo $orderDetails[0]['SupplierTelephoneNumber'] ?></dd><br>
                <dt>Adres</dt><dd><?php echo $orderDetails[0]['SupplierAddress'] ?></dd><br>
                <dt>Status</dt><dd>
                <select name="OrderState" class="form-control">
                    <?php foreach($orderStates as $orderState) { ?>
                        <option value="<?php echo $orderState['OrderState'] ?>" <?php if($currentOrderState == $orderState['OrderState']) { echo 'selected';} ?>><?php echo $orderState['OrderState'] ?></option>
                    <?php } ?>
                </select></dd><br>
                <dt>Ontvangst datum</dt><dd>

                    <?php
                        if(isset($orderDetails[0]['OrderRecievedDate'])){
                            $date = new DateTime($orderDetails[0]['OrderRecievedDate']);
                            echo $date -> format('Y-m-d')  ?>
                                </dd><br>
                        <?php
                        }
                        else {
                            ?>
                            <input type="date" name="OrderRecievedDate" class="form-control" max="<?php echo date('Y-m-d'); ?>" />
                <?php
                        }
                ?>
           </dl>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <h4>Producten</h4>
            <table class="table table-hover">
                <tr>
                    <th>Product</th>
                    <th>Aantal</th>
                    <th>Prijs</th>
                </tr>
                <?php foreach($orderDetails as $itemInOrder) {?>
                    <tr>
                        <td><?php echo $itemInOrder['ItemName'] ?></td>
                        <td><?php echo $itemInOrder['Amount'] . ' ' . $itemInOrder['Unit'] ?></td>
                        <td><?php echo '€ ' .round($itemInOrder['Price']) ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="col-lg-6">
            <h4>Opmerkingen</h4>
            <textarea name="OrderComments" class="form-control" rows="5"><?php echo $orderDetails[0]['Comment'] ?></textarea>
        </div>
    </div>
    <input type="hidden" value="<?php echo $orderID ?>" name="orderID" />
    <button type="submit" name="btnSave" class="btn btn-default">Opslaan</button>
</form>