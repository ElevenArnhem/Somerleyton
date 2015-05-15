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

function feedingSchedule($feedingScheme, $addButton, $dbh) {

    $recipestmt = $dbh->prepare("EXEC proc_GetRecipe");
    $recipestmt->execute();
    $recipe = $recipestmt->fetchAll();
    echo '
    <div class="col-lg-4">
        <h4>Specifiek voedingsschema</h4>
        <table class="table table-hover">
            <tr>
                <th>Dag</th>
                <th>Tijdstip</th>
                <th>Recept</th>
            </tr>';
        foreach ($feedingScheme as $feedingSchemeRow) {
            //    if($_SESSION['FUNCTION'])
            echo '
                <tr>
                    <td>' . $feedingSchemeRow['DayGeneral'] . '</td>
                    <td>';
            echo explode('.', $feedingSchemeRow['TimeGeneral'])[0];
            echo '</td>
                    <td>' . $feedingSchemeRow['FeedingRecipeID'] . '
                        <form action="index.php?page=feedingscheme&headspecies=' . $_GET['headspecies'] . '&subspecies=' . $_GET['subspecies'] . '" method="post">
                            <button type="submit" class="btn btn-link btn-xs" aria-label="Left Align">
                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                            </button>
                        </form>
                    </td>
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
                <td>'.$addButton.'

                </td>
            </tr>
        </form>
    </table>

    </div>';
}