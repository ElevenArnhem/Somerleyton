<?php

$staffID = $_SESSION['STAFFID'];
$searchCriteria = '';
if(isset($_POST["SEARCHCRITERIA"])) {
    $searchCriteria = $_POST["SEARCHCRITERIA"];
}

$leverancierstmt = $dbh->prepare("EXEC proc_searchLeveranciers ?,?, ?");
$leverancierstmt->bindParam(1, $staffID);
$leverancierstmt->bindParam(2, $searchCriteria);
$leverancierstmt->bindParam(3, $_POST["SEARCHISACTIVE"]);

$leverancierstmt->execute();
$leveranciers = $leverancierstmt->fetchAll();
if($_SESSION['FUNCTION'] != 'KantoorPersoneel') {
    spErrorCaching($leverancierstmt);
}
if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
echo '
<hr>
<form action="index.php?page=Leverancier" method="post">
      <div class="col-lg-6">
        <div class="input-group">
          <input name="SEARCHCRITERIA" type="text" class="form-control" placeholder="Zoek leveranciers op: naam of adres">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Zoek</button>
          </span>
        </div>
      </div>
    <div class="radio">
      <label>';
      if($_POST["SEARCHISACTIVE"] == 1 && isset($_POST["SEARCHISACTIVE"])){
          echo '<input type="radio" name="SEARCHISACTIVE" value="1" checked>';
        } else {
            echo '<input type="radio" name="SEARCHISACTIVE" value="1">';
        } echo '
            Actieve leveranciers
        </label>
    </div>
    <div class="radio">
        <label>';
        if($_POST["SEARCHISACTIVE"] == 0 && isset($_POST["SEARCHISACTIVE"])) {
            echo '<input type="radio" name="SEARCHISACTIVE" value="0" checked>';
        } else {
            echo '<input type="radio" name="SEARCHISACTIVE" value="0">';
        } echo '
                Niet actieve leveranciers
        </label>
    </div>
</form>
<br>
<table class="table table-hover" >
        <tr>
             <th>Naam</th>
            <th>Adress</th>
            <th>Telefoonnummer</th>
            <th></th>
        </tr>';
foreach($leveranciers as $leverancier) {
    echo '<tr>
          <td>' . $leverancier["SupplierName"] . '</td>
          <td>' . $leverancier["SupplierAddress"] . '</td>
          <td>' . $leverancier["SupplierTelephoneNumber"] . '</td>';

    if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
        echo '<td>
                    <a href="?page=alterLeverancier&Leverancier='.$leverancier["SupplierName"].'">
                        <button type="button" class="btn btn-default">Aanpassen</button>
                    </a>
                  </td>';
    }echo '</tr>';
}echo '</table>';

    echo '<div class="btn-group" role="group">
            <a href="index.php?page=addLeverancier"><button type="button" class="btn btn-default">Toevoegen</button></a>
          </div>';
}