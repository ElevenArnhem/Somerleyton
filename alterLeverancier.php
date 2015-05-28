<?php
/**
 * Created by PhpStorm.
 * User: koen
 * Date: 27-5-2015
 * Time: 12:58
 */

$leverancierstmt = $dbh->prepare("EXEC proc_getLeverancier ?");
$leverancierstmt->bindParam(1, $_GET['Leverancier']);
$leverancierstmt->execute();
$leveranciers = $leverancierstmt->fetch();

if(isset($_POST['Telefoonnummer']) && isset($_POST['Adres'])) {
    $leverancier2stmt = $dbh->prepare("EXEC proc_addLeverancier ?, ?, ?, ?,?");
    $leverancier2stmt->bindParam(1, $_SESSION['STAFFID']);
    $leverancier2stmt->bindParam(2, $_GET['Leverancier']);
    $leverancier2stmt->bindParam(3, $_POST["Adres"]);
    $leverancier2stmt->bindParam(4, $_POST["Telefoonnummer"]);
    $leverancier2stmt->bindParam(5, $_POST["SEARCHISACTIVE"]);

    $leverancier2stmt->execute();
    spErrorCaching($leverancier2stmt);

    $leverancierstmt = $dbh->prepare("EXEC proc_getLeverancier ?");
    $leverancierstmt->bindParam(1, $_GET['Leverancier']);
    $leverancierstmt->execute();
    $leveranciers = $leverancierstmt->fetch();
}

echo '
<hr>
<form action="index.php?page=alterLeverancier&Leverancier='.$_GET["Leverancier"].'" method="post">
      <div class="col-lg-8">
          <h2>'.$_GET["Leverancier"].'</h2>
          <br><br>
          <p>Adres</p>
          <input name="Adres" type="text" class="form-control" Value='.$leveranciers["SupplierAddress"].' required>
          <br><br>
          <p>Telefoonnummer</p>
          <input name="Telefoonnummer" type="text" class="form-control" Value='.$leveranciers['SupplierTelephoneNumber'].' required>

         <br>

          <div class="radio">
      <label>';
if($leveranciers["SupplierIsActive"] == 1){
    echo '<input type="radio" name="SEARCHISACTIVE" value="1" checked>';
} else {
    echo '<input type="radio" name="SEARCHISACTIVE" value="1">';
} echo '
            Actieve leverancier
        </label>
    </div>

    <div class="radio">
        <label>';
if($leveranciers["SupplierIsActive"] == 0) {
    echo '<input type="radio" name="SEARCHISACTIVE" value="0" checked>';
} else {
    echo '<input type="radio" name="SEARCHISACTIVE" value="0">';
} echo '
                Niet actieve leverancier
        </label>
    </div>

          <br>
          <button type="submit" class="btn btn-default">Invoeren</button>

      </div>

</form>';



