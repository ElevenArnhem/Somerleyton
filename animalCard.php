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
        <div class="col-lg-6">
          <h2>Dier Info</h2>
          <dl class="dl-horizontal">
          <dt>Geslacht</dt><dd>'.$animal['Gender'].'</dd><br>
           <dt>Soort</dt><dd>'.$animal['LatinName'].'</dd><br>
           <dt>Sub soort</dt><dd>'.$animal['SubSpeciesName'].'</dd><br>
           <dt>Latijnse naam</dt><dd>'.$animal['LatinName'].'</dd><br>
           <dt>Geboorte plaats</dt><dd>'.$animal['BirthPlace'].'</dd><br>
           <dt>Geboorte datum</dt><dd>'.$animal['BirthDate'].'</dd><br>
           <dt>Gebied </dt><dd>'.$animal['AreaName'].'</dd><br> <br>
           <dt>Beschrijving </dt></dl> <br> '.$animal['Description'].'<br><br>';
        if($_SESSION['FUNCTION'] == 'HeadKeeper') {
        echo '<a href="?page=changeAnimal&animalID='.$animal["AnimalID"].'"> <button type="button" class="btn btn-default" >Aanpassen</button></a>';
        }
       echo'</div>';