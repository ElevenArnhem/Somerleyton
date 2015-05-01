<?php

include 'conn.inc.php';

$headSpeciesProc = $dbh->prepare('EXEC proc_getHeadSpecies');
$headSpeciesProc->execute();
$headSpecies = $headSpeciesProc->fetchAll();
?>

<h1>Subsoort toevoegen</h1>

<form action="?page=addSubSpeciesPost" method="post">
    <input type='hidden' name='STAFFID' value='<?php echo $_SESSION['STAFFID']; ?>'>
    <div class="form-group">
        <label>Subsoort naam</label>
        <input name="SUBSPECIESNAME" type="text" class="form-control" placeholder="Naam subsoort">
    </div>
    <div class="form-group">

        <label>Hoofdsoort</label>
        <select name="LATINNAME" type="text" class="form-control" placeholder="Hoofdsoort">
            <?php
            foreach ($headSpecies as $fetchHeadSpecies) {
            echo '<option value="'.$fetchHeadSpecies["LatinName"].'">'.$fetchHeadSpecies["LatinName"].'</option>';
            }
            ?>
            </select>
        <a href="addHeadSpecies.php"> <button type="button" class="btn btn-default">Nieuwe toevoegen</button></a>
    </div>
    <div class="form-group">
        <label>Beschrijving</label>
        <textarea name="DESCRIPTION" class="form-control" rows="3"></textarea>
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Toevoegen">
</form>