<?php
$staffID = $_SESSION['STAFFID'];

$itemID = 0;
if(isset($_GET['ItemID'])) {
    $itemID = $_GET['ItemID'];
}

if(isset($_POST['ItemName']) && isset($_POST['Unit'])){
    $additemstmt = $dbh->prepare("proc_addItem ?, ?, ?, ?");
    $additemstmt->bindParam(1, $staffID);
    $additemstmt->bindParam(2, $_POST['ItemName']);
    $additemstmt->bindParam(3, $_POST['Unit']);
    $additemstmt->bindParam(4, $_POST['Supplier']);

    $additemstmt->execute();

    spErrorCaching($additemstmt);
    $newItemID = $additemstmt->fetch();
    if(isset($newItemID[0])) {
        $itemID = $newItemID[0];
    }
}

if($itemID > 0) {
    $getItemstmt = $dbh->prepare("EXEC proc_getItem ?, ?");
    $getItemstmt->bindParam(1, $staffID);
    $getItemstmt->bindParam(2, $itemID);
    $getItemstmt->execute();
    $item = $getItemstmt->fetch();
    if(isset($_POST['DeleteSupplierFromItem'])) {
        $supplierToDeleteName = $_POST['DeleteSupplierFromItem'];
        $deleteSupplierstmt = $dbh->prepare("proc_deleteSupplierFromItem ?, ?, ?");
        $deleteSupplierstmt->bindParam(1, $staffID);
        $deleteSupplierstmt->bindParam(2, $itemID);
        $deleteSupplierstmt->bindParam(3, $supplierToDeleteName);
        $deleteSupplierstmt->execute();
        spErrorCaching($deleteSupplierstmt);
    }
    if(isset($_POST['addSupplierToItem'])) {
        $supplierToAddName = $_POST['addSupplierToItem'];
        $addSupplierToItem = $dbh->prepare("proc_addSupplierFromItem ?, ?, ?");
        $addSupplierToItem->bindParam(1, $staffID);
        $addSupplierToItem->bindParam(2, $itemID);
        $addSupplierToItem->bindParam(3, $supplierToAddName);
        $addSupplierToItem->execute();
        spErrorCaching($addSupplierToItem);
    }
    if(isset($item)) {
        $itemLeveranciersstmt = $dbh->prepare("proc_getSuppliersForItem ?");
        $itemLeveranciersstmt->bindParam(1, $itemID);
        $itemLeveranciersstmt->execute();
        $itemLeveranciers = $itemLeveranciersstmt->fetchAll();
    }
}


$searchCriteria = '';
if(isset($_POST["SEARCHCRITERIA"])) {
    $searchCriteria = $_POST["SEARCHCRITERIA"];
}



$isActive = 1;
$leverancierstmt = $dbh->prepare("EXEC proc_searchLeveranciers ?,?, ?");
$leverancierstmt->bindParam(1, $staffID);
$leverancierstmt->bindParam(2, $searchCriteria);
$leverancierstmt->bindParam(3, $isActive);

$leverancierstmt->execute();
$leveranciers = $leverancierstmt->fetchall();

if($_SESSION['FUNCTION'] != 'KantoorPersoneel') {
    spErrorCaching($leverancierstmt);
}
echo '

<div class="row">
    <div class="col-lg-6">
      <form action="index.php?page=alterItem&ItemID='.$item['ItemID'].'" method="post">
        <div class="input-group">
            <dl class="dl-horizontal">
                <dt>Product ID:</dt><dd>'.$item['ItemID'].'</dd><br>
                <dt>Product naam:</dt><dd>'.$item['ItemName'].'</dd><br>
                <dt>Product eenheid:</dt><dd>'.$item['Unit'].'</dd><br>
            </dl>
        </div>
            <table class="table table-hover" >
            <tr>
                <th>Naam</th>
                <th>Telefoonnummer</th>
            </tr>';
foreach ($itemLeveranciers as $itemLeverancier) {
    echo '<tr>
              <td>' . $itemLeverancier["SupplierName"] . '</td>
              <td>' . $itemLeverancier["SupplierTelephoneNumber"] . '</td>
              <td><form action="index.php?page=alterItem&ItemID='.$item['ItemID'].'" method="post">
                <button type="submit" name="DeleteSupplierFromItem" class="btn btn-link" aria-label="Left Align" value="' . $itemLeverancier["SupplierName"] . '">
                    <span class="glyphicon glyphicon-remove-sign" aria-hidden="true" />
                </button>
              </form></td>
           </tr>';
}
echo '</table>
    </div>


  <div class="col-lg-6">
      <form action="index.php?page=alterItem&ItemID='.$item['ItemID'].'" method="post">
        <div class="input-group">
          <input name="SEARCHCRITERIA" type="text" class="form-control" placeholder="Zoek leveranciers op: naam of adres">
          <span class="input-group-btn">
            <button class="btn btn-default" value="search" type="submit">Zoek</button>
          </span>
        </div>
        <br><br>
    <table class="table table-hover" >
            <tr>
                <th>Naam</th>
                <th>Telefoonnummer</th>
            </tr>';
        foreach ($leveranciers as $leverancier) {
            $check = 0;
            foreach($itemLeveranciers as $itemLeverancier) {
                if($leverancier['SupplierName'] == $itemLeverancier['SupplierName'])  {
                    $check++;
                }
            }
            if($check == 0) {
                echo '<tr>
                          <td>' . $leverancier["SupplierName"] . '</td>
                          <td>' . $leverancier["SupplierTelephoneNumber"] . '</td>
                          <td>
                             <button type="submit" name="addSupplierToItem" class="btn btn-link" aria-label="Left Align" value="' . $leverancier["SupplierName"] . '" >
                                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" />
                                </button>
                            </td>
                       </tr>';
            }
        }
    echo '</table>
    </div>
</form>
';