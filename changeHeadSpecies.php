<?php
    $headSpeciesName = $_POST['headSpecies'];

if(isset($_POST["submit"])) {
    $staffID = $_SESSION['STAFFID'];
    $origineleNaam = $_POST["originalHeadSpeciesName"];
    $nieuweNaam = $_POST["headSpecies"];

    $speciesStatement = $dbh->prepare("proc_alterHeadSpecies ?, ?, ?");
    $speciesStatement->bindParam(1, $staffID);
    $speciesStatement->bindParam(2, $origineleNaam);
    $speciesStatement->bindParam(3, $nieuweNaam);
    $speciesStatement->execute();
    spErrorCaching($speciesStatement);
}
?>

<h1>Hoofdsoort aanpassen</h1>

<form action="?page=changeHeadSpecies" method="POST">
    <input type="hidden" name="originalHeadSpeciesName" value="<?php echo $headSpeciesName ?>"/>
    <div class="form-group">
        <label>Hoofdsoort</label>
        <input class="form-control textbox" name="headSpecies" type="text" disabled value="<?php echo $headSpeciesName ?>">
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Opslaan">
</form>