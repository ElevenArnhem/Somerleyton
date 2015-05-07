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
            $picaName =$targetFileName;
        } else {
            echo '<div class="alert alert-danger" role="alert">Sorry, er iets mis gegaan tijden het uploaden.</div>';
        }
    }

}