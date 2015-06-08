<?php
    $staffID = $_SESSION['STAFFID'];
    if(isset($_POST['orderID'])){
        $orderID = $_POST['orderID'];

        if(isset($_POST['btnSave'])){
            $orderState = $_POST['OrderState'];

            $comment = '';
            if(isset($_POST['OrderComments'])) {
                $comment = $_POST['OrderComments'];
            }

            $orderRecievedDate = '';
            if(isset($_POST['OrderRecievedDate'])) {
                $orderRecievedDate = $_POST['OrderRecievedDate'];

                if($orderRecievedDate == '1900-01-01 00:00') {
                    $orderRecievedDate = '';
                } else {
                    $orderRecievedDate = makeDateTime($orderRecievedDate);
                }


            }
            $invoiceRecievedDate = '';
            if(isset($_POST['InvoiceRecievedDate'])) {
                $invoiceRecievedDate = $_POST['InvoiceRecievedDate'];

                if($invoiceRecievedDate == '1900-01-01 00:00') {
                    $invoiceRecievedDate = '';
                } else {
                    $invoiceRecievedDate = makeDateTime($invoiceRecievedDate);
                }

            }
            $invoicePaidDate = '';
            if(isset($_POST['InvoicePaidDate'])) {
                $invoicePaidDate = $_POST['InvoicePaidDate'];

                if($invoicePaidDate == '1900-01-01 00:00') {
                    $invoicePaidDate = '';
                } else {
                    $invoicePaidDate = makeDateTime($invoicePaidDate);
                }

            }
//            echo '-';
//            echo $staffID;
//            echo '-';
//            echo $orderID;
//            echo '-';
//            echo $orderState;
//            echo '-';
//            echo $comment;
//            echo '-';
//            echo $orderRecievedDate;
//            echo '-';
//            echo $invoiceRecievedDate;
//            echo '-';
//            echo $invoicePaidDate;
//            echo '-';

            $alterOrderStatement = $dbh -> prepare('proc_alterOrder ?, ?, ?, ?, ?, ?, ?');
            $alterOrderStatement -> bindParam(1, $staffID);
            $alterOrderStatement -> bindParam(2, $orderID);
            $alterOrderStatement -> bindParam(3, $orderState);
            $alterOrderStatement -> bindParam(4, $comment);
            $alterOrderStatement -> bindParam(5, $orderRecievedDate);
            $alterOrderStatement -> bindParam(6, $invoiceRecievedDate);
            $alterOrderStatement -> bindParam(7, $invoicePaidDate);
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
                <dt s>Order nummer</dt><dd><?php echo $orderID ?></dd><br>
                <dt>Geplaatst door</dt><dd><?php echo $orderDetails[0]['StaffName'] ?></dd><br>
                <dt>Datum</dt><dd><?php $date = new DateTime($orderDetails[0]['OrderDate']); echo $date -> format('H:i d-m-Y'); ?></dd><br>
                <dt>Leverancier</dt><dd><?php echo $orderDetails[0]['SupplierName'] ?></dd><br>
                <dt>Telefoon nummer</dt><dd><?php echo $orderDetails[0]['SupplierTelephoneNumber'] ?></dd><br>
                <dt>Adres</dt><dd><?php echo $orderDetails[0]['SupplierAddress'] ?></dd><br>
                <dt>Status</dt><dd>
                    <?php if(isset( $orderDetails[0]['OrderReceivedDate'])) { ?>
                    <input type="hidden" name="OrderRecievedDate" value="<?php echo $orderDetails[0]['OrderReceivedDate'] ?>" /><?php }
                    if(isset( $orderDetails[0]['InvoiceReceivedDate'])) {?>
                    <input type="hidden" name="InvoiceRecievedDate" value="<?php echo $orderDetails[0]['InvoiceReceivedDate'] ?> " />
                    <?php }
                    if(isset( $orderDetails[0]['InvoicePaidDate'])) {?>
                    <input type="hidden" name="InvoicePaidDate" value="<?php echo $orderDetails[0]['InvoicePaidDate'] ?>" /><?php } ?>
                <select name="OrderState" class="form-control">
                    <?php foreach($orderStates as $orderState) { ?>
                        <option value="<?php echo $orderState['OrderState'] ?>"
                            <?php if($currentOrderState == $orderState['OrderState']) { echo 'selected';} ?>>
                            <?php echo $orderState['OrderState'] ?>
                        </option>
                    <?php } ?>
                </select></dd><br>
                <dt>Ontvangst datum</dt><dd>

                    <?php
                        if(isset($orderDetails[0]['OrderReceivedDate'])){
                            $date = new DateTime($orderDetails[0]['OrderReceivedDate']);
                            echo $date -> format('H:i d-m-Y');  ?>
                                </dd><br>
                        <?php
                        }
                        else {
                            ?>
                            <input type="datetime-local" name="OrderRecievedDate" class="form-control" max="<?php echo date('Y-m-d'); ?>" /> </dd><br>
                <?php
                        }
                ?>
                <dt>Datum rekening </dt><dd><?php
                    if(isset($orderDetails[0]['InvoiceReceivedDate'])) {
                        $date = new DateTime($orderDetails[0]['InvoiceReceivedDate']);
                        echo $date -> format('H:i d-m-Y');
                    } else {?>
                        <input type="datetime-local" name="InvoiceRecievedDate" class="form-control" max="<?php echo date('Y-m-d'); ?>" /><?php } ?>
                </dd><br>
                <dt>Datum betaald</dt><dd><?php
                    if(isset($orderDetails[0]['InvoicePaidDate'])) {
                        $date = new DateTime($orderDetails[0]['InvoicePaidDate']);
                    echo $date -> format('H:i d-m-Y');
                    } else {?>
                        <input type="datetime-local" name="InvoicePaidDate" class="form-control" max="<?php echo date('Y-m-d'); ?>" /><?php } ?>
                </dd><br>
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
