<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 1-5-2015
 * Time: 11:14
 */

function spErrorCaching($stmt) {
    $explodedStr = explode(']',$stmt->errorInfo()[2]);
    $errorMessage = end($explodedStr);

    if($errorMessage != '')
        echo '<div class="alert alert-danger" role="alert">'.$errorMessage.'</div>';
    else
        echo '<div class="alert alert-success" role="alert">Geslaagd</div>';
}

function addPicture($animalID) {

    $target_dir = "/pictures/";

    $targetFileName = $animalID;
    $tmpTargetFileName = $targetFileName.'.'.explode('.', $_FILES['fileToUpload']['name'])[1];
    $targetFileName = $tmpTargetFileName;
    $target_file = $target_dir . basename($targetFileName);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"].$targetFileName);
        if ($check !== false) {

            $uploadOk = 1;
        } else {

            $uploadOk = 0;
        }
    }
// Check if file already exists
    if (file_exists($target_file)) {
        unlink('pictures/'.$targetFileName);
//            $uploadOk = 0;
    }
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo '<div class="alert alert-danger" role="alert">Sorry, het bestand is te groot.</div>';
        $uploadOk = 0;
    }
// Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo '<div class="alert alert-danger" role="alert">Sorry, alleen JPG, JPEG, PNG & GIF bestanden zijn toegestaan.</div>';
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo '<div class="alert alert-danger" role="alert">Sorry, er iets mis gegaan tijden het uploaden.</div>';
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
           //echo 'foto toegevoegd';
        } else {
            echo '<div class="alert alert-danger" role="alert">Sorry, er iets mis gegaan tijden het uploaden.</div>';
        }
    }

}

function getCurrentWeekNumber(){
    $date = getCurrentDate();
    $week = $date -> format("W");

    return $week;
}

function getCurrentYear(){
    $date = getCurrentDate();
    $year = $date -> format("Y");

    return $year;
}

function getCurrentDate(){
    $date = date('Y-m-d');
    $curDate = new DateTime($date);

    return $curDate;
}

function addSpeciesPicture($fileName){
    if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {

        $target_dir = isLocal()."/pictures/";

        $targetFileName = $fileName;
        $tmpTargetFileName = $targetFileName.'.'.explode('.', $_FILES['fileToUpload']['name'])[1];
        $targetFileName = $tmpTargetFileName;
        $target_file = $target_dir . basename($targetFileName);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {

                $uploadOk = 1;
            } else {

                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            unlink('pictures/'.$targetFileName);

        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo '<div class="alert alert-danger" role="alert">Sorry, het bestand is te groot.</div>';
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo '<div class="alert alert-danger" role="alert">Sorry, alleen JPG, JPEG, PNG & GIF bestanden zijn toegestaan.</div>';
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo '<div class="alert alert-danger" role="alert">Sorry, er iets mis gegaan tijden het uploaden.</div>';
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                return $targetFileName;
            } else {
                echo '<div class="alert alert-danger" role="alert">Sorry, er iets mis gegaan tijden het uploaden.</div>';
            }
        }
    }
}

function isLocal()
{
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );
    if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        return '../Somerleyton';
    }
    return '';
}

function feedingSchedule($feedingScheme, $addButton, $dbh, $deleteButton, $specificAnimals) {

    $recipestmt = $dbh->prepare("EXEC proc_GetRecipe");
    $recipestmt->execute();
    $recipe2= $recipestmt->fetchAll();
    echo '
    <div class="col-lg-3">
    <br>
        <h4>Voedingsschema</h4>
        <table class="table table-hover">
            <tr>
                <th>Dag</th>
                <th>Tijdstip</th>
                <th>Recept </th>
            </tr>';
    if (isset($feedingScheme[0]['FeedingRecipeID'])) {
        foreach ($feedingScheme as $feedingSchemeRow) {

                //    if($_SESSION['FUNCTION'])
                echo '
                    <tr>
                        <form action="index.php?page=feedingRecipe" method="post"> <td>
                        <input type="hidden" name="feedingSchemeRow" value="' . base64_encode(serialize($feedingSchemeRow)) . '">
                        <input type="hidden" name="latinName" value="' . $_GET['headspecies'] . '">
                        <input type="hidden" name="subSpecies" value="' . $_GET['subspecies'] . '">';
                if (isset($_POST['SPECIFICANIMALFEEDINGSCHEME']) && $specificAnimals > 0) {

                    echo ' <input type="hidden" name="animalID" value="' . $specificAnimals . '">';
                }

                echo '<button type="submit" class="btn btn-link">' . $feedingSchemeRow['DayGeneral'] . '</button></td></form>
                        <td>';
                echo explode('.', $feedingSchemeRow['TimeGeneral'])[0];
                echo '</td>
                        <form action="index.php?page=feedingscheme&headspecies=' . $_GET['headspecies'] . '&subspecies=' . $_GET['subspecies'] . '" method="post">
                        <td>' . $feedingSchemeRow['FeedingRecipeID'];
                if ($feedingSchemeRow['HeadKeeperFromSubSpecies'] == '1') {
                    echo '

                    <input type="hidden" name="FEEDINGRECIPEID" value="' . $feedingSchemeRow['FeedingRecipeID'] . '">
                    <input type="hidden" name="DAYGENERAL" value="' . $feedingSchemeRow['DayGeneral'] . '">
                    <input type="hidden" name="TIMEGENERAL" value="' . explode('.', $feedingSchemeRow['TimeGeneral'])[0] . '">' . $deleteButton . '
                        <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                        </button>
                        </form>';
                }
                echo '</td>';

                echo '</tr>';

        }

    }
    echo '
    </table>

    </div>';

    $searchString = '';
    if(isset($_POST['SEARCHSTRING'])) {
        $searchString = $_POST['SEARCHSTRING'];
    }
    $headSpecies = $_GET['headspecies'];
    $subSpecies = $_GET['subspecies'];
    $getRecipesstmt = $dbh->prepare("EXEC proc_SearchRecipeForSubSpecies ?,?,?");
    $getRecipesstmt->bindParam(1, $searchString);
    $getRecipesstmt->bindParam(2, $headSpecies);
    $getRecipesstmt->bindParam(3, $subSpecies);
    $getRecipesstmt->execute();
    $recipes = $getRecipesstmt->fetchAll();
    echo '


  <div class="col-lg-6">
    <form action="index.php?page=feedingscheme&headspecies='.$_GET['headspecies'].'&subspecies='.$_GET['subspecies'].'" method="post">


        <div class="input-group">
          <input name="SEARCHSTRING" type="text" class="form-control" placeholder="Zoek recepten op ingredient naam">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit" >Zoek</button>
          </span>

        </div><!-- /input-group -->

     <!-- /.col-lg-6 -->


     <br>
    </form>';
    echo '
    <table class="table table-hover">
    <form action="index.php?page=feedingscheme&headspecies=' . $_GET['headspecies'] . '&subspecies=' . $_GET['subspecies'] . '" method="post">
    <tr>
                <th>ReceptID</th>
                <th>Ingredienten</th>
                <th>Hoeveelheid</th>

    </tr>';
    $recipeID = 0;
    foreach($recipes as $recipe) {
        $items = null;
        if($recipeID != $recipe['FeedingRecipeID']) {
            $recipeID = $recipe['FeedingRecipeID'];

            echo '<tr>
                <td>'.$recipe['FeedingRecipeID'].'</td>
                <td>
    ';
            if ($recipeID == $recipe['FeedingRecipeID']) {

                foreach ($recipes as $recipe1) {
                    if ($recipeID == $recipe1['FeedingRecipeID']) {
                        $popupRow = $recipe1['ItemName'];
                        echo $popupRow. '<br>';

                    }
                }
            }

            echo'
        </td><td>';
            if ($recipeID == $recipe['FeedingRecipeID']) {

                foreach ($recipes as $recipe1) {
                    if ($recipeID == $recipe1['FeedingRecipeID']) {
                        $popupRow = $recipe1['Amount'].' ' .$recipe1['Unit'];
                        echo $popupRow. '<br>';

                    }
                }
            }

            echo '</td>';

            }

        echo'</tr>';
    };
    if($specificAnimals > 0 || isset($_POST['SPECIFICANIMALFEEDINGSCHEME'])) {
        if ($feedingScheme[0]['HeadKeeperFromSubSpecies'] == '1') {
            echo '
            <tr>

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
                        <select class="form-control" name="FeedingRecipeID">';
                        $recipeID = 0;
                        foreach($recipes as $recipe) {
                            if ($recipeID != $recipe['FeedingRecipeID']) {
                                $recipeID = $recipe['FeedingRecipeID'];

                                echo '<option>' . $recipe['FeedingRecipeID'] . '</option>';
                            }
                        }
                    echo '</select>
                    </td>
                                <td><input type="hidden" name="SPECIFICANIMALS" value="' . $specificAnimals . '">
                                 ' . $addButton . '

                </td>



            </tr>

        </form>';
        }
    }
    echo '
        </table>
    </div>

 ';

}


function searchSpecies($linkPage, $dbh) {
    if(isset($_POST["SEARCHSTRINGHEADSPECIES"])) {
        $searchString = $_POST["SEARCHSTRINGHEADSPECIES"];
        $headSpeciesstmt = $dbh->prepare("EXEC proc_SearchHeadSpecies ?");
        $headSpeciesstmt->bindParam(1,$searchString);
        $headSpeciesstmt->execute();
        $headSpecies = $headSpeciesstmt->fetchAll();
    } else {
        $headSpeciesstmt = $dbh->prepare("EXEC proc_SearchHeadSpecies ''");
        $headSpeciesstmt->execute();
        $headSpecies = $headSpeciesstmt->fetchAll();
    }

    if(isset($_POST["SEARCHSTRINGSUBSPECIES"])){
        $searchStringHeadSpecies = '';
        if(isset($_POST["SEARCHSTRINGHEADSPECIES"])) {
            $searchStringHeadSpecies = $_POST["SEARCHSTRINGHEADSPECIES"];
        }
        $searchString = $_POST["SEARCHSTRINGSUBSPECIES"];
        $subspeciesstmt = $dbh->prepare("EXEC proc_SearchSubSpecies ?,?");
        $subspeciesstmt->bindParam(1,$searchStringHeadSpecies);
        $subspeciesstmt->bindParam(2,$searchString);
        $subspeciesstmt->execute();
        $subspecies = $subspeciesstmt->fetchAll();
    } else {
        $subspeciesstmt = $dbh->prepare("EXEC proc_SearchSubSpecies '',''");
        $subspeciesstmt->execute();
        $subspecies = $subspeciesstmt->fetchAll();
    }


    echo '

<div class="row">
    <div class="col-lg-6">
    <form action="index.php?page=feeding" method="post">


    <div class="input-group">
      <input name="SEARCHSTRINGHEADSPECIES" type="text" class="form-control" placeholder="Zoek op hoofdsoort">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" >Zoek</button>
      </span>

    </div><!-- /input-group -->

  <!-- /.col-lg-6 -->

  <br><br>
</form>';
    echo '
<table class="table table-hover"><tr>
            <th>Hoofdsoort</th>
            </tr>';
    foreach($headSpecies as $headSpeciesRow) {
//    if($_SESSION['FUNCTION'])
        echo '<tr><td><form action="index.php?page=feeding" method="post"><input type="hidden" name="SEARCHSTRINGSUBSPECIES" value="">
    <button class="btn btn-link" type="submit" name="SEARCHSTRINGHEADSPECIES" value="'.$headSpeciesRow["HeadSpeciesName"].'">'.$headSpeciesRow["HeadSpeciesName"].'</button></form> </td>
        ';

        echo'</tr>';
    };
    echo '
</table></div>
<div class="col-lg-6">
    <form action="index.php?page=feeding" method="post">


    <div class="input-group">
      <input name="SEARCHSTRINGSUBSPECIES" type="text" class="form-control" placeholder="Zoek op subsoort">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" >Zoek</button>
      </span>

    </div><!-- /input-group -->

  <!-- /.col-lg-6 -->

  <br><br>
</form>';
    echo '
<table class="table table-hover"><tr>
            <th>Subsoort</th>
</tr>';
    foreach($subspecies as $subspeciesRow) {
//    if($_SESSION['FUNCTION'])
        echo '<tr>
        <td><a role="button" class="btn btn-link" href="?page='.$linkPage.'&headspecies='.$subspeciesRow["HeadSpeciesName"].'&subspecies='.$subspeciesRow["SubSpeciesName"].'">'.$subspeciesRow["SubSpeciesName"].'</a></td>
';

        echo'</tr>';
    };
    echo '
</table></div>
</div>';
}