<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 30-4-2015
 * Time: 13:07
 */
if($_SESSION['FUNCTION'] == 'HeadKeeper') {
    $picaName = null;
    if(isset($_POST['fileName'])) {
        $picaName = $_POST['fileName'];
    }

    if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
        $target_dir = "../pictures/";

        $targetFileName = $_POST['ANIMALID'];
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
    if(isset($_POST['ANIMALNAME'])) {
        $staffID = $_SESSION['STAFFID'];
        $animalID = $_POST['ANIMALID'];
        $animalName = $_POST['ANIMALNAME'];
        $gender = $_POST['GENDER'];
        $birthDate = $_POST['BIRTHDATE'];
        $birthPlace = $_POST['BIRTHPLACE'];
        $environmentName = $_POST['ENVIRONMENTNAME'];
        $areaName = $_POST['AREANAME'];
        $enclosureID = $_POST['ENCLOSUREID'];
        $latinName = $_POST['LATINNAME'];
        $subSpeciesName = $_POST['SUBSPECIESNAME'];
//        $image = $_POST['fileToUpload'];


            $changeAnimalstmt = $dbh->prepare("proc_AlterAnimal ?,?,?,?,?,?,?,?,?,?,?,?");
            $changeAnimalstmt->bindParam(1, $staffID);
            $changeAnimalstmt->bindParam(2, $animalID);
            $changeAnimalstmt->bindParam(3, $environmentName);
            $changeAnimalstmt->bindParam(4, $areaName);
            $changeAnimalstmt->bindParam(5, $enclosureID);
            $changeAnimalstmt->bindParam(6, $latinName);
            $changeAnimalstmt->bindParam(7, $subSpeciesName);
            $changeAnimalstmt->bindParam(8, $animalName);
            $changeAnimalstmt->bindParam(9, $gender);
            $changeAnimalstmt->bindParam(10, $birthDate);
            $changeAnimalstmt->bindParam(11, $birthPlace);
            $changeAnimalstmt->bindParam(12, $picaName);

            $changeAnimalstmt->execute();
        spErrorCaching($changeAnimalstmt);


    }

    $animalID = $_GET['animalID'];
    $animalstmt = $dbh->prepare("proc_getAnimal ?");
    $animalstmt->bindParam(1, $animalID);
    $animalstmt->execute();
    $animal = $animalstmt->fetch();

    $latinNamestmt = $dbh->prepare("proc_getHeadSpecies");
    $latinNamestmt->execute();
    $latinNames = $latinNamestmt->fetchAll();

    $subSpeciesstmt = $dbh->prepare("proc_getSubSpecies");
    $subSpeciesstmt->execute();
    $subSpecies = $subSpeciesstmt->fetchAll();

    $areastmt = $dbh->prepare("proc_getAreaName");
    $areastmt->execute();
    $areas = $areastmt->fetchAll();

    $environmontstmt = $dbh->prepare("proc_getEnvironment");
    $environmontstmt->execute();
    $environmentNames = $environmontstmt->fetchAll();

    echo '        <br>
        <div class="row">
            <div class="col-lg-6">
              <h2>Dier Info</h2>
              <form action="index.php?page=changeAnimal&animalID='.$animal['AnimalID'].'" method="post" enctype="multipart/form-data">
              <dl class="dl-horizontal">
              <input type="hidden" name="ANIMALID" value="'.$animal['AnimalID']. '">
               <dt>Naam</dt><dd><input name="ANIMALNAME" type="text" class="form-control" value="'.$animal['AnimalName'].'"></dd><br>
              <dt>Geslacht</dt><dd><select name="GENDER" type="text" class="form-control" value="'.$animal['Gender'].'">
                                        <option value="F">Vrouwtje</option><option value="M"';if($animal['Gender'] == "M"){ echo'selected'; } echo'>Mannetje </option></select></dd><br>
               <dt>Soort</dt><dd><select name="LATINNAME" type="text" class="form-control" value="'.$animal['LatinName'].'"><option value="'.$animal['LatinName'].'">'.$animal['LatinName'].'</option>';
               foreach($latinNames as $latinName){
                   echo '<option value="'.$latinName["LatinName"].'">'.$latinName["LatinName"].'</option>';
               }
               echo'</select></dd><br>
               <dt>Sub soort</dt><dd><select name="SUBSPECIESNAME" type="text" class="form-control" value="'.$animal['SubSpeciesName'].'"><option value="'.$animal['SubSpeciesName'].'">'.$animal['SubSpeciesName'].'</option>';
                foreach($subSpecies as $subSpeciesName) {
                 echo '<option value="'.$subSpeciesName["SubSpeciesName"].'">'.$subSpeciesName["SubSpeciesName"].'</option>';
                }
                echo'</select></dd><br>
               <dt>Latijnse naam</dt><dd><select name="LATINNAME" type="text" class="form-control" value="'.$animal['LatinName'].'"><option value="'.$animal['LatinName'].'">'.$animal['LatinName'].'</option>';
        foreach($latinNames as $latinName){
            echo '<option value="'.$latinName["LatinName"].'">'.$latinName["LatinName"].'</option>';
        }
        echo'</select></dd><br>
               <dt>Geboorte plaats</dt><dd><input name="BIRTHPLACE" type="text" class="form-control" value="'.$animal['BirthPlace'].'"></dd><br>
               <dt>Geboorte datum</dt><dd><input name="BIRTHDATE" type="date" class="form-control" value="'.$animal['BirthDate'].'"></dd><br>
               <dt>Omgeving </dt><dd><select name="ENVIRONMENTNAME" type="text" class="form-control" value="'.$animal['EnvironmentName'].'"><option value="'.$animal['EnvironmentName'].'">'.$animal['EnvironmentName'].'</option>';
                foreach($environmentNames as $environmentName) {
                    echo '<option value="'.$environmentName['EnvironmentName'].'">'.$environmentName['EnvironmentName'].'</option>';
                }
                 echo '</select></dd><br> <br>
               <dt>Gebied </dt><dd><select name="AREANAME" type="text" class="form-control" value="'.$animal['AreaName'].'"><option value="'.$animal['AreaName'].'">'.$animal['AreaName'].'</option>';
                foreach($areas as $area) {
                    echo '<option value="'.$area['AreaName'].'">'.$area['AreaName'].'</option>';
                }
            echo '</select></dd><br>
                <dt>verblijf nummer</dt><dd><input name="ENCLOSUREID" type="text" class="form-control" value="'.$animal['EnclosureID'].'"></dd><br><br>
               <dt>Beschrijving </dt></dl> <br> <textarea name="DESCRIPTION" class="form-control" rows="5">'.$animal['Description'].'</textarea><br><br>';

        echo '  </div>
   <div class="col-lg-6">
   <br><br>';
    if(isset($animal['Image'])) {
        echo '
<img src="/pictures/' . $animal['Image'] . '" width="300" height="300"><br><br>';
    }
    echo'
    Selecteer een foto:<br><br>
    <input type="hidden" name="fileName" value="'.$animal['Image'].'">
    <input class="btn btn-default"  type="file" name="fileToUpload" >
   <br>
     <button class="btn btn-primary" type="submit">Aanpassen</button>
</form></div>';

    echo'</div>';
}