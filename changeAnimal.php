<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 30-4-2015
 * Time: 13:07
 */
if($_SESSION['FUNCTION'] == 'HeadKeeper') {

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
        $image = 'test';

//        echo $staffID, $animalID, $environmentName, $areaName, $enclosureID, $latinName, $subSpeciesName,$animalName, $gender, $birthDate, $birthPlace,  $image;
        $changeAnimalstmt = $dbh->prepare("proc_AlterAnimal");

//            $changeAnimalstmt = $dbh->prepare("proc_AlterAnimal ?,?,?,?,?,?,?,?,?,?,?,?");
//            $changeAnimalstmt->bindParam(1, $staffID);
//            $changeAnimalstmt->bindParam(2, $animalID);
//            $changeAnimalstmt->bindParam(3, $environmentName);
//            $changeAnimalstmt->bindParam(4, $areaName);
//            $changeAnimalstmt->bindParam(5, $enclosureID);
//            $changeAnimalstmt->bindParam(6, $latinName);
//            $changeAnimalstmt->bindParam(7, $subSpeciesName);
//            $changeAnimalstmt->bindParam(8, $animalName);
//            $changeAnimalstmt->bindParam(9, $gender);
//            $changeAnimalstmt->bindParam(10, $birthDate);
//            $changeAnimalstmt->bindParam(11, $birthPlace);
//            $changeAnimalstmt->bindParam(12, $image);
//        $changeAnimalstmt = $dbh->prepare("update Animal set Gender = 'F' where AnimalID = 3");
//        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $changeAnimalstmt->execute();
        //if(!$changeAnimalstmt) {
        $changeAnimalstmt->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        echo $changeAnimalstmt->errorInfo()[0];
        echo $changeAnimalstmt->errorInfo()[1];
        echo $changeAnimalstmt->errorInfo()[2];
       // }
//        $msg = $changeAnimalstmt->fetch();
//        echo $msg['animalID'];
//        echo sqlsrv_errors();
//        echo "changed something";


        //StaffID, AnimalID, AnimalName, Gender, BirthDate, BirthPlace, EnvironmentName, AreaName, EnclosureID, LatinName, SubSpeciesName, Image
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
            <div class="col-lg-6">
              <h2>Dier Info</h2>
              <form action="index.php?page=changeAnimal&animalID='.$animal['AnimalID'].'" method="post">
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

        echo '   <button class="btn btn-lg btn-primary " type="submit">Aanpassen</button></form>';

    echo'</div>';
}