<?php
$staffID = $_SESSION['STAFFID'];

If(isset($_POST['ItemName']) && isset($_POST['Unit'])){
    $additemstmt = $dbh->prepare("EXEC proc_addItem ?, ?, ?");
    $additemstmt->bindParam(1, $staffID);
    $additemstmt->bindParam(2, $_POST['ItemName']);
    $additemstmt->bindParam(3, $_POST['Unit']);

    $additemstmt->execute();
    spErrorCaching($additemstmt);
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
<hr>
<form action="index.php?page=addItem" method="post">
      <div class="col-lg-6">
          <input name="ItemName" type="text" class="form-control" placeholder="Productnaam" required>
          <br>
          <input name="Unit" type="text" class="form-control" placeholder="Eenheid" required>
          <br>
          <button type="submit" class="btn btn-default" value="opslaan">Opslaan</button>
          <br><br><br>
      </div>
</form>


<form action="index.php?page=addItem" method="post">
      <div class="col-lg-8">
        <div class="input-group">
          <input name="SEARCHCRITERIA" type="text" class="form-control" placeholder="Zoek leveranciers op: naam of adres">
          <span class="input-group-btn">
            <button class="btn btn-default" value="search" type="submit">Zoek</button>
          </span>
        </div>
        <br><br>
      </div>
<table class="table table-hover" >
        <tr>
            <th>Naam</th>
            <th>Telefoonnummer</th>
        </tr>';
    foreach ($leveranciers as $leverancier) {
        echo '<tr>
          <td>' . $leverancier["SupplierName"] . '</td>
          <td>' . $leverancier["SupplierTelephoneNumber"] . '</td>
       </tr>';
    }
echo '</table>
</form>';