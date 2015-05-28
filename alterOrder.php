<?php
    $staffID = $_SESSION['STAFFID'];
    if(isset($_POST['orderID'])){
        $orderID = $_POST['orderID'];

        $getOrderDetailStatement = $dbh -> prepare('proc_getOrderDetails ?, ?');
        $getOrderDetailStatement -> bindParam(1, $staffID);
        $getOrderDetailStatement -> bindParam(2, $orderID);
        $getOrderDetailStatement -> execute();
        $orderDetails = $getOrderDetailStatement -> fetchAll();
    }
?>

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
            <dt>Status</dt><dd><?php echo $orderDetails[0]['OrderState'] ?></dd><br>
            <dt>Ontvangst datum</dt><dd><?php echo $orderDetails[0]['OrderRecievedDate'] ?></dd><br>
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
                    <td><?php echo $itemInOrder['Amount'] ?></td>
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