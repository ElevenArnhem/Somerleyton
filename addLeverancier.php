<?php
/**
 * Created by PhpStorm.
 * User: koen
 * Date: 27-5-2015
 * Time: 11:23
 */

If(isset($_POST['Leveranciersnaam']) && isset($_POST['Adres']) && isset($_POST['Telefoonnummer'])){
    $staffID = $_SESSION['STAFFID'];
    $isactive = 1;

    $addleverancierstmt = $dbh->prepare("EXEC proc_addLeverancier ?, ?, ?, ?,?");
    $addleverancierstmt->bindParam(1, $staffID);
    $addleverancierstmt->bindParam(2, $_POST['Leveranciersnaam']);
    $addleverancierstmt->bindParam(3, $_POST['Adres']);
    $addleverancierstmt->bindParam(4, $_POST['Telefoonnummer']);
    $addleverancierstmt->bindParam(5, $isactive);

    $addleverancierstmt->execute();
    spErrorCaching($addleverancierstmt);
}

echo '
<hr>
<form action="index.php?page=addLeverancier" method="post">
      <div class="col-lg-8">
          <input name="Leveranciersnaam" type="text" class="form-control" placeholder="Leveranciersnaam" maxlength="50" required>
          <br><br><br>
          <input name="Adres" type="text" class="form-control" placeholder="Adres" maxlength="50"  required>
          <br><br><br>
          <input name="Telefoonnummer" type="text" class="form-control" placeholder="Telefoonnummer" maxlength="20"  required>
          <br><br><br>
          <button type="submit" class="btn btn-default">Invoeren</button>
      </div>
</form>
';
