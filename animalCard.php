<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 30-4-2015
 * Time: 12:07
 */

$animalID = $_GET['animalID'];
$animalstmt = $dbh->prepare("proc_getAnimal ?");
$animalstmt->bindParam(1, $animalID);
$animalstmt->execute();
$animal = $animalstmt->fetch();
echo '<h1>'.$animal['AnimalName'].'</h1>
        <br>
        <div class="row">
        <div class="col-lg-6">
          <h2>Dier Info</h2>
          <dl class="dl-horizontal">
          <dt>Geslacht</dt><dd>'.$animal['Gender'].'</dd><br>
           <dt>Soort</dt><dd>'.$animal['LatinName'].'</dd><br>
           <dt>Sub soort</dt><dd>'.$animal['SubSpeciesName'].'</dd><br>
           <dt>Latijnse naam</dt><dd>'.$animal['LatinName'].'</dd><br>
           <dt>Geboorte plaats</dt><dd>'.$animal['BirthPlace'].'</dd><br>
           <dt>Geboorte datum</dt><dd>'.$animal['BirthDate'].'</dd><br>
           <dt>Omgeving </dt><dd>'.$animal['EnvironmentName'].'</dd><br>
           <dt>Gebied </dt><dd>'.$animal['AreaName'].'</dd><br>';
            if($_SESSION['FUNCTION'] == 'HeadKeeper' || $_SESSION['FUNCTION'] == 'Keeper' ||$_SESSION['FUNCTION'] == 'Vet' ) {
               if(!isset($_POST['PRINTVERSION'])) {
           echo'<dt>Verblijf </dt><dd>'.$animal['EnclosureID'].'</dd><br>
           <dt>Voedingsschema </dt><dd>nnb</dd><br>
           <dt>Medische gegevens </dt><dd>nnb</dd><br>'; } }echo'<br>
           <dt>Beschrijving </dt></dl> <br> '.$animal['Description'].'<br><br>';
        if($_SESSION['FUNCTION'] == 'HeadKeeper' && !isset($_POST['PRINTVERSION'])) {
        echo '<form action="index.php?page=animalCard&animalID='.$animalID.'" method="post"><div class="btn-group" role="group" ><a class="btn btn-default" role="button" href="?page=changeAnimal&animalID='.$animal["AnimalID"].'"> Aanpassen </a>
        <button name="PRINTVERSION" type="submit" class="btn btn-default" >Print versie</button></div></form><br><br><br> ';
        }
       echo'</div>
   <div class="col-lg-6">
   <br><br>';
if(isset($animal['Image']) && !empty($animal['Image']) && $animal['Image'] != null) {
    echo '
<img src="/pictures/' . $animal['Image'] . '" width="300" height="300"><br><br>';
}
echo '</div></div>';