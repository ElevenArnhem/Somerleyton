<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 13-5-2015
 * Time: 13:35
 */



$headSpeciesName = $_GET['headspecies'];
$subSpeciesName = $_GET['subspecies'];

if(isset($_POST['ADDGENERICFEEDINGSCHEMEROW'])) {
    $dayGeneral = $_POST['DayGeneral'];
    $timeGeneral = $_POST['TimeGeneral'];
    $feedingRecipeID = $_POST['FeedingRecipeID'];
    $addGenericFeedingSchemestmt = $dbh->prepare("EXEC proc_AddGeneriekVoerschema ?,?,?,?,?");
    $addGenericFeedingSchemestmt->bindParam(1,$headSpeciesName);
    $addGenericFeedingSchemestmt->bindParam(2,$subSpeciesName);
    $addGenericFeedingSchemestmt->bindParam(3,$feedingRecipeID);
    $addGenericFeedingSchemestmt->bindParam(4,$dayGeneral);
    $addGenericFeedingSchemestmt->bindParam(5,$timeGeneral);
    $addGenericFeedingSchemestmt->execute();
    spErrorCaching($addGenericFeedingSchemestmt);
}

if(isset($_POST['ADDSPECIFICFEEDINGSCHEME'])) {
    $animalID = $_POST['ADDSPECIFICFEEDINGSCHEME'];
    $dayGeneral = $_POST['DayGeneral'];
    $timeGeneral = $_POST['TimeGeneral'];
    $feedingRecipeID = $_POST['FeedingRecipeID'];
    $addSpecificFeedingSchemestmt = $dbh->prepare("EXEC proc_AddSpecifiekVoerSchema ?,?,?,?");
    $addSpecificFeedingSchemestmt->bindParam(1,$animalID);
    $addSpecificFeedingSchemestmt->bindParam(2,$feedingRecipeID);
    $addSpecificFeedingSchemestmt->bindParam(3,$dayGeneral);
    $addSpecificFeedingSchemestmt->bindParam(4,$timeGeneral);
    $addSpecificFeedingSchemestmt->execute();
    spErrorCaching($addSpecificFeedingSchemestmt);
}
$genericFeedingSchemestmt = $dbh->prepare("EXEC proc_GetGeneriekVoerSchema ?,?");
$genericFeedingSchemestmt->bindParam(1,$subSpeciesName);
$genericFeedingSchemestmt->bindParam(2,$headSpeciesName);
$genericFeedingSchemestmt->execute();
$genericFeedingScheme = $genericFeedingSchemestmt->fetchAll();

$recipestmt = $dbh->prepare("EXEC proc_GetRecipe");
$recipestmt->execute();
$recipe = $recipestmt->fetchAll();

$specificAnimals = 0;
if(isset($_POST['SPECIFICANIMALS'])) {
    $specificAnimals = $_POST['SPECIFICANIMALS'];
}
$animalsstmt = $dbh->prepare("EXEC proc_GetAnimalAndVoersschema ?,?,?");
$animalsstmt->bindParam(1,$headSpeciesName);
$animalsstmt->bindParam(2,$subSpeciesName);
$animalsstmt->bindParam(3,$specificAnimals);
$animalsstmt->execute();
$animals = $animalsstmt->fetchAll();

if(isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
    $specificAnimalID = $_POST['SPECIFICANIMALFEEDINGSCHEME'];
    $specificFeedingSchemestmt = $dbh->prepare("proc_GetSpecifiekVoerSchema ?");
    $specificFeedingSchemestmt->bindParam(1, $specificAnimalID);
    $specificFeedingSchemestmt->execute();
    $specificFeedingScheme = $specificFeedingSchemestmt->fetchAll();
}


echo '<h2>Voedingsschema</h1>
    <h3>Hoofdsoort: '.$_GET['headspecies'].'</h2>
    <h3>Subsoort: '.$_GET['subspecies'].'</h2>
     <hr>
     <div class="row">
    <div class="col-lg-4">
    <h4>Generiek voedingsschema</h4>
<table class="table table-hover"><tr>
            <th>Dag</th>
            <th>Tijdstip</th>
            <th>Recept</th>
            </tr>';
foreach($genericFeedingScheme as $genericFeedingSchemeRow) {
//    if($_SESSION['FUNCTION'])
    echo '<tr>
<td>'.$genericFeedingSchemeRow['DayGeneral'].'</td>
<td>'; echo explode('.', $genericFeedingSchemeRow['TimeGeneral'])[0]; echo '</td>
<td>'.$genericFeedingSchemeRow['FeedingRecipeID'].'
<button type="button" class="btn btn-link btn-xs" aria-label="Left Align">
<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
</button></td>
        ';

    echo'</tr>';
};
echo '
<tr><form action="index.php?page=feedingscheme&headspecies='.$_GET['headspecies'].'&subspecies='.$_GET['subspecies'].'" method="post">
<td><select name="DayGeneral" type="text" class="form-control" required>
    <option>maandag</option>
    <option>dinsdag</option>
    <option>woensdag</option>
    <option>donderdag</option>
    <option>vrijdag</option>
    <option>zaterdag</option>
    <option>zondag</option>
    </select></td>
<td><input name="TimeGeneral" type="time" class="form-control" required></td>
<td><select name="FeedingRecipeID"  type="text" class="form-control" required>';
foreach($recipe as $recipeRow) {
    echo '
    <option>'.$recipeRow['FeedingRecipeID'].'</option>';
}
echo '
    </select></td>
    </tr>
    <tr><td></td>
    <td><button name="ADDGENERICFEEDINGSCHEMEROW" type="submit" class="btn btn-default" >Voeg toe</button></td>
</tr></form>
</table>

    </div>
    <div class="col-lg-4">
<form action="index.php?page=feedingscheme&headspecies='.$_GET['headspecies'].'&subspecies='.$_GET['subspecies'].'" method="post">';
if($specificAnimals == 0) {
    echo '<button name = "SPECIFICANIMALS" value = "1" type = "submit" class="btn btn-default" > Alleen dieren met een specifiek voerschema </button >';
    }
    if($specificAnimals == 1) { echo '<button name = "SPECIFICANIMALS" value = "0" type = "submit" class="btn btn-default" > Alle dieren </button >';} echo '<br><br>
    </form>
    <table class="table table-hover"><tr>
            <th>ID</th>
            <th>Naam</th>
            </tr>';
foreach($animals as $animal) {
    echo'
    <tr>
    <td>'.$animal['AnimalID'].'</td>
    <td>
        <form action="index.php?page=feedingscheme&headspecies='.$_GET['headspecies'].'&subspecies='.$_GET['subspecies'].'" method="post">
            <button name="SPECIFICANIMALFEEDINGSCHEME" value="'.$animal['AnimalID'].'" type="submit" class="btn btn-link">
                '.$animal['AnimalName'].'
            </button>
        </form>
    </td>
    </tr>';
}
echo '
</table>
</div>';
if(isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
    echo '
<div class="col-lg-4">
    <h4>Specifiek voedingsschema</h4>
    <table class="table table-hover">
        <tr>
            <th>Dag</th>
            <th>Tijdstip</th>
            <th>Recept</th>
        </tr>';
    foreach ($specificFeedingScheme as $specificFeedingSchemeRow) {
        //    if($_SESSION['FUNCTION'])
        echo '
            <tr>
                <td>' . $specificFeedingSchemeRow['DayGeneral'] . '</td>
                <td>';
        echo explode('.', $specificFeedingSchemeRow['TimeGeneral'])[0];
        echo '</td>
                <td>' . $specificFeedingSchemeRow['FeedingRecipeID'] . '</td>
        ';

        echo '</tr>';
    };
    echo '
        <tr>
            <form action="index.php?page=feedingscheme&headspecies=' . $_GET['headspecies'] . '&subspecies=' . $_GET['subspecies'] . '" method="post">
                <td>
                    <select name="DayGeneral" type="text" class="form-control" required>
                        <option>maandag</option>
                        <option>dinsdag</option>
                        <option>woensdag</option>
                        <option>donderdag</option>
                        <option>vrijdag</option>
                        <option>zaterdag</option>
                        <option>zondag</option>
                    </select>
                </td>
                <td>
                    <input name="TimeGeneral" type="time" class="form-control" required>
                </td>
                <td>
                    <select name="FeedingRecipeID"  type="text" class="form-control" required>';
    foreach ($recipe as $recipeRow) {
        echo '
                        <option>' . $recipeRow['FeedingRecipeID'] . '</option>';
    }
    echo '
                    </select>
                </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="SPECIFICANIMALFEEDINGSCHEME" value="'.$_POST['SPECIFICANIMALFEEDINGSCHEME'].'">
                <button name="ADDSPECIFICFEEDINGSCHEME" value="'.$_POST['SPECIFICANIMALFEEDINGSCHEME'].'" type="submit" class="btn btn-default" >Voeg toe</button>
            </td>
        </tr>
    </form>
</table>

</div>';
}
echo '

</div>';
