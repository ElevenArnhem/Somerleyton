<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript"></script>
<script src="js/script.js"></script>

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
        <select name="LATINNAME" id="textbox" type="text" placeholder="Hoofdsoort">
            <?php
            foreach ($headSpecies as $fetchHeadSpecies) {
            echo '<option value="'.$fetchHeadSpecies["LatinName"].'">'.$fetchHeadSpecies["LatinName"].'</option>';
            }
            ?>
            </select>
        <input id="enable" name="enable" type="checkbox" />
        <input id="first_name" class="textbox" name="LATINNAME" type="text" disabled />
    </div>
    <div class="form-group">
        <label>Beschrijving</label>
        <textarea name="DESCRIPTION" class="form-control" rows="3"></textarea>
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Toevoegen">
</form>