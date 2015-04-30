<?php

$speciesName = $dbh->prepare("SELECT SubSpeciesName FROM SubSpecies ORDER BY SubSpeciesName");
$speciesName->execute();
$species = $speciesName->fetchAll();
?>


<form action="addSubSpeciesPost.php" method="post">
    <div class="form-group">
        <label>Naam</label>
        <input name="ANIMALNAME" type="text" class="form-control" placeholder="Naam dier">
    </div>
    <div class="form-group">
        <label>Latijnse naam</label>
        <input name="LATINNAME" type="text" class="form-control" placeholder="Latijnse naam">
    </div>
    <div class="form-group">
        <label>Hoofdsoort</label>
        <select name="SPECIESNAME" class="form-control">
            <?php
            foreach($species as $specie) {
                echo    '<option value="'.$specie["SubSpeciesName"].'">'.$specie["SubSpeciesName"].'</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Beschrijving</label>
        <textarea name="DESCRIPTION" class="form-control" rows="3"></textarea>
    </div>
    <input type="submit" value="submit" name="submit">
</form>