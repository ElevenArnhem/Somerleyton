<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript"></script>
<script src="js/script.js"></script>

<?php

include 'conn.inc.php';


$headSpeciesProc = $dbh->prepare('EXEC proc_getHeadSpecies');
$headSpeciesProc->execute();
$headSpecies = $headSpeciesProc->fetchAll();

if(isset($_POST["submit"])) {
    $image = 'sdfsdf';

    $speciesStatement = $dbh->prepare("proc_addSubSpecies ?, ?, ?, ?, ?");
    $speciesStatement->bindParam(1, $_POST["STAFFID"]);
    $speciesStatement->bindParam(2, $_POST["LATINNAME"]);
    $speciesStatement->bindParam(3, $_POST["SUBSPECIESNAME"]);
    $speciesStatement->bindParam(4, $_POST["DESCRIPTION"]);
    $speciesStatement->bindParam(5, $image);
    $speciesStatement->execute();
    echo '<div class="alert alert-success" role="alert">Diersoort is toegevoegd.</div>';

}
?>

<h1>Diersoort toevoegen</h1>

<form action="?page=addSpecies" method="post">
    <input type='hidden' name='STAFFID' value='<?php echo $_SESSION['STAFFID']; ?>'>
    <div class="form-group">

        <label>Hoofdsoort</label>
        <select name="LATINNAME" id="selectbox" class="form-control" type="text" placeholder="Hoofdsoort" required>
            <?php
            foreach ($headSpecies as $fetchHeadSpecies) {
                echo '<option value="'.$fetchHeadSpecies["LatinName"].'">'.$fetchHeadSpecies["LatinName"].'</option>';
            }
            ?>
        </select>
        <span>Andere hoofdsoort:</span>
        <input id="enable" name="enable" type="checkbox" />
        <input class="form-control textbox" name="LATINNAME" required type="text" disabled />
    </div>
    <div class="form-group">
        <label>Subsoort naam</label>
        <input name="SUBSPECIESNAME" type="text" class="form-control" placeholder="
        <?php if(isset($_POST['subSpecies'])) {echo $_POST['subSpecies'];} else{ echo 'Naam subsoort"';} echo' required>
    </div>
    <div class="form-group">
        <label>Beschrijving</label>
        <textarea name="DESCRIPTION" class="form-control" rows="3" required></textarea>
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Toevoegen">
</form>';
    ?>