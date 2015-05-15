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
     <div class="row">';

$addButton = '<input type="hidden" name="SPECIFICANIMALS" value="'.$specificAnimals.'">
<button name="ADDGENERICFEEDINGSCHEMEROW" type="submit" class="btn btn-default" >Voeg toe</button>';
feedingSchedule($genericFeedingScheme, $addButton, $dbh);

   echo '
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
    <tr';
    if(isset($_POST['SPECIFICANIMALFEEDINGSCHEME']) && $_POST['SPECIFICANIMALFEEDINGSCHEME'] == $animal['AnimalID']) {
        echo ' class="active "';
    }
    echo'>
    <td>'.$animal['AnimalID'].'</td>
    <td>
        <form action="index.php?page=feedingscheme&headspecies='.$_GET['headspecies'].'&subspecies='.$_GET['subspecies'].'" method="post">
            <input type="hidden" name="SPECIFICANIMALS" value="'.$specificAnimals.'">
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
    $addButton = ' <input type="hidden" name="SPECIFICANIMALS" value="'.$specificAnimals.'">
    <input type="hidden" name="SPECIFICANIMALFEEDINGSCHEME" value="'.$_POST['SPECIFICANIMALFEEDINGSCHEME'].'">
                    <button name="ADDSPECIFICFEEDINGSCHEME" value="'.$_POST['SPECIFICANIMALFEEDINGSCHEME'].'" type="submit" class="btn btn-default" >Voeg toe</button>';
    feedingSchedule($specificFeedingScheme,$addButton, $dbh);


}
echo '

</div>';
