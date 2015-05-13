<?php

if(isset($_POST["submit"])) {
    $speciesStatement = $dbh->prepare("proc_addHeadSpecies ?, ?");
    $speciesStatement->bindParam(1, $_POST["STAFFID"]);
    $speciesStatement->bindParam(2, $_POST["LATINNAME"]);
    $speciesStatement->execute();
    spErrorCaching($speciesStatement);
}
?>

<h1>Hoofdsoort toevoegen</h1>

<form action="?page=addHeadSpecies" method="post">
    <input type='hidden' name='STAFFID' value='<?php echo $_SESSION['STAFFID']; ?>'>
    <div class="form-group">
        <label>Hoofdsoort</label>
        <input class="form-control textbox" name="LATINNAME" required type="text" />
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Toevoegen">
</form>