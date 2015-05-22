<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 13-5-2015
 * Time: 13:35
 */



$headSpeciesName = $_GET['headspecies'];
$subSpeciesName = $_GET['subspecies'];
$staffID = $_SESSION['STAFFID'];
if(isset($_GET['headspecies']) && isset($_GET['subspecies'])) {

    if (isset($_POST['DELETEGENERIC'])) {
        $feedingRecipeID = $_POST['FEEDINGRECIPEID'];
        $dayGeneral = $_POST['DAYGENERAL'];
        $timeGeneral = $_POST['TIMEGENERAL'];
        $deleteGenericstmt = $dbh->prepare("proc_DeleteGeneriekVoerSchema ?,?,?,?,?,?");
        $deleteGenericstmt->bindParam(1, $staffID);
        $deleteGenericstmt->bindParam(2, $headSpeciesName);
        $deleteGenericstmt->bindParam(3, $subSpeciesName);
        $deleteGenericstmt->bindParam(4, $feedingRecipeID);
        $deleteGenericstmt->bindParam(5, $dayGeneral);
        $deleteGenericstmt->bindParam(6, $timeGeneral);
        $deleteGenericstmt->execute();
        spErrorCaching($deleteGenericstmt);
        //if(isset())
    }
    if (isset($_POST['DELETESPECIFIC'])) {
        $feedingRecipeID = $_POST['FEEDINGRECIPEID'];
        $dayGeneral = $_POST['DAYGENERAL'];
        $timeGeneral = $_POST['TIMEGENERAL'];
        $animalID = $_POST['SPECIFICANIMALS'];
//    echo $staffID, $animalID, $feedingRecipeID, $dayGeneral, $timeGeneral;
        $deleteSpecificstmt = $dbh->prepare("proc_DeleteSpecifiekVoerSchema ?,?,?,?,?");
        $deleteSpecificstmt->bindParam(1, $staffID);
        $deleteSpecificstmt->bindParam(2, $animalID);
        $deleteSpecificstmt->bindParam(3, $feedingRecipeID);
        $deleteSpecificstmt->bindParam(4, $dayGeneral);
        $deleteSpecificstmt->bindParam(5, $timeGeneral);
        $deleteSpecificstmt->execute();
        spErrorCaching($deleteSpecificstmt);
        //if(isset())
    }
    if (isset($_POST['ADDGENERICFEEDINGSCHEMEROW'])) {
        $dayGeneral = $_POST['DayGeneral'];
        $timeGeneral = $_POST['TimeGeneral'];
        $feedingRecipeID = $_POST['FeedingRecipeID'];
        $addGenericFeedingSchemestmt = $dbh->prepare("proc_AddGeneriekVoerschema ?,?,?,?,?,?");
        $addGenericFeedingSchemestmt->bindParam(1, $headSpeciesName);
        $addGenericFeedingSchemestmt->bindParam(2, $subSpeciesName);
        $addGenericFeedingSchemestmt->bindParam(3, $feedingRecipeID);
        $addGenericFeedingSchemestmt->bindParam(4, $dayGeneral);
        $addGenericFeedingSchemestmt->bindParam(5, $timeGeneral);
        $addGenericFeedingSchemestmt->bindParam(6, $staffID);
        $addGenericFeedingSchemestmt->execute();
        spErrorCaching($addGenericFeedingSchemestmt);
    }

    if (isset($_POST['ADDSPECIFICFEEDINGSCHEME'])) {
        $animalID = $_POST['ADDSPECIFICFEEDINGSCHEME'];
        $dayGeneral = $_POST['DayGeneral'];
        $timeGeneral = $_POST['TimeGeneral'];
        $feedingRecipeID = $_POST['FeedingRecipeID'];
        $addSpecificFeedingSchemestmt = $dbh->prepare("proc_AddSpecifiekVoerSchema ?,?,?,?,?");
        $addSpecificFeedingSchemestmt->bindParam(1, $animalID);
        $addSpecificFeedingSchemestmt->bindParam(2, $feedingRecipeID);
        $addSpecificFeedingSchemestmt->bindParam(3, $dayGeneral);
        $addSpecificFeedingSchemestmt->bindParam(4, $timeGeneral);
        $addSpecificFeedingSchemestmt->bindParam(5, $staffID);
        $addSpecificFeedingSchemestmt->execute();
        spErrorCaching($addSpecificFeedingSchemestmt);
    }
    $genericFeedingSchemestmt = $dbh->prepare("EXEC proc_GetGeneriekVoerSchema ?,?,?");
    $genericFeedingSchemestmt->bindParam(1, $subSpeciesName);
    $genericFeedingSchemestmt->bindParam(2, $headSpeciesName);
    $genericFeedingSchemestmt->bindParam(3, $staffID);
    $genericFeedingSchemestmt->execute();
    $genericFeedingScheme = $genericFeedingSchemestmt->fetchAll();


    $recipestmt = $dbh->prepare("EXEC proc_GetRecipe");
    $recipestmt->execute();
    $recipe = $recipestmt->fetchAll();
    $specificAnimals = 0;
//i
//    $specificAnimals = $schemes['HeadKeeperFromSubSpecies'];
//}


    if (isset($_POST['SPECIFICANIMALS'])) {
        $specificAnimals = $_POST['SPECIFICANIMALS'];
    }


    $animalsstmt = $dbh->prepare("EXEC proc_GetAnimalAndVoersschema ?,?,?");
    $animalsstmt->bindParam(1, $headSpeciesName);
    $animalsstmt->bindParam(2, $subSpeciesName);
    $animalsstmt->bindParam(3, $specificAnimals);
    $animalsstmt->execute();
    $animals = $animalsstmt->fetchAll();

    if (isset($genericFeedingScheme[0]['HeadKeeperFromSubSpecies'])) {
        $specificAnimals = $genericFeedingScheme[0]['HeadKeeperFromSubSpecies'];
    }
    if (isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
        $specificAnimalID = $_POST['SPECIFICANIMALFEEDINGSCHEME'];
        $specificAnimals = $_POST['SPECIFICANIMALFEEDINGSCHEME'];
        $specificFeedingSchemestmt = $dbh->prepare("proc_GetSpecifiekVoerSchema ?,?");
        $specificFeedingSchemestmt->bindParam(1, $specificAnimalID);
        $specificFeedingSchemestmt->bindParam(2, $staffID);
        $specificFeedingSchemestmt->execute();
        $specificFeedingScheme = $specificFeedingSchemestmt->fetchAll();

    }


    echo '<h2>Voedingsschema</h1>
    <h3>Hoofdsoort: ' . $_GET['headspecies'] . '</h2>
    <h3>Subsoort: ' . $_GET['subspecies'] . '</h2>
    <a role="button" class="btn btn-primary" href="index.php?page=createRecipe">Nieuw Recept</a>
     <hr>
     <div class="row">';

    $addButton = '<input type="hidden" name="SPECIFICANIMALS" value="' . $specificAnimals . '">
<button name="ADDGENERICFEEDINGSCHEMEROW" type="submit" class="btn btn-default" >Voeg toe</button>';
    $deleteButton = ' <button name="DELETEGENERIC" value="1" type="submit" class="btn btn-link btn-xs" aria-label="Left Align">
';
    if (isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
        $specificAnimals = 0;
    }
    feedingSchedule($genericFeedingScheme, $addButton, $dbh, $deleteButton, $specificAnimals);


    echo '
    </div>



    <div class="row">
    <div class="col-lg-4">
<form action="index.php?page=feedingscheme&headspecies=' . $_GET['headspecies'] . '&subspecies=' . $_GET['subspecies'] . '" method="post">';
    if (isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
        echo '<input type="hidden" name="SPECIFICANIMALFEEDINGSCHEME" value="' . $_POST['SPECIFICANIMALFEEDINGSCHEME'] . '">';
    }

    if (!isset($_POST['SPECIFICANIMALS']) || (isset($_POST['SPECIFICANIMALS']) && $_POST['SPECIFICANIMALS'] == 0)) {
        echo '<button name = "SPECIFICANIMALS" value = "1" type = "submit" class="btn btn-default" > Alleen dieren met een specifiek voerschema </button >';
    }
    if ((isset($_POST['SPECIFICANIMALS']) && $_POST['SPECIFICANIMALS'] > 0)) {
        echo '<button name = "SPECIFICANIMALS" value = "0" type = "submit" class="btn btn-default" > Alle dieren </button >';
    }
    echo '<br><br>
    </form>
    <table class="table table-hover"><tr>
            <th>ID</th>
            <th>Naam</th>
            </tr>';
    if (isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
        $specificAnimals = $_POST['SPECIFICANIMALFEEDINGSCHEME'];
    }
    foreach ($animals as $animal) {
        echo '
    <tr';
        if (isset($_POST['SPECIFICANIMALFEEDINGSCHEME']) && $_POST['SPECIFICANIMALFEEDINGSCHEME'] == $animal['AnimalID']) {
            echo ' class="active "';
        }
        echo '>
    <td>' . $animal['AnimalID'] . '</td>
    <td>
        <form action="index.php?page=feedingscheme&headspecies=' . $_GET['headspecies'] . '&subspecies=' . $_GET['subspecies'] . '" method="post">
            <input type="hidden" name="SPECIFICANIMALS" value="' . $specificAnimals . '">
            <button name="SPECIFICANIMALFEEDINGSCHEME" value="' . $animal['AnimalID'] . '" type="submit" class="btn btn-link">
                ' . $animal['AnimalName'] . '
            </button>
        </form>
    </td>
    </tr>';
    }
    echo '
</table>
</div>';
    if (isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
        $addButton = ' <input type="hidden" name="SPECIFICANIMALS" value="' . $specificAnimals . '">
    <input type="hidden" name="SPECIFICANIMALFEEDINGSCHEME" value="' . $_POST['SPECIFICANIMALFEEDINGSCHEME'] . '">
                    <button name="ADDSPECIFICFEEDINGSCHEME" value="' . $_POST['SPECIFICANIMALFEEDINGSCHEME'] . '" type="submit" class="btn btn-default" >Voeg toe</button>';

        $deleteButton = ' <input type="hidden" name="SPECIFICANIMALS" value="' . $_POST['SPECIFICANIMALFEEDINGSCHEME'] . '">
<input type="hidden" name="SPECIFICANIMALFEEDINGSCHEME" value="' . $_POST['SPECIFICANIMALFEEDINGSCHEME'] . '">
<button name="DELETESPECIFIC"  type="submit" class="btn btn-link btn-xs" aria-label="Left Align">
';
        feedingSchedule($specificFeedingScheme, $addButton, $dbh, $deleteButton, $specificAnimalID);


    }
    echo '

</div>';
} else {
    header('index.php');
}
