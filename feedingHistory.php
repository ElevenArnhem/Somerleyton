<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 18-5-2015
 * Time: 14:08
 */

$searchString = '';
//$speciesstmt = $dbh->prepare("EXEC proc_SearchFeedingHistoryGeneriek ?");
//$speciesstmt->bindParam(1,$searchString);
//$speciesstmt->execute();
//$species = $speciesstmt->fetchAll();

//if(isset($_POST['SEARCHSTRINGSPECIES'])){
    $startSearchDate = null;
    if(isset($_POST['STARTDATE']) && !empty($_POST['STARTDATE'])) {
        $startSearchDate = $_POST['STARTDATE'];
    }
    $endSearchDate = null;
    if(isset($_POST['ENDDATE'])&& !empty($_POST['ENDDATE'])) {
        $endSearchDate = $_POST['ENDDATE'];
    }
    $searchStringSubspecies = '';
    if(isset($_POST['SEARCHSTRINGSPECIES'])&& !empty($_POST['SEARCHSTRINGSPECIES'])) {
        $searchStringSubspecies = $_POST["SEARCHSTRINGSPECIES"];
    }

    $speciesstmt = $dbh->prepare("EXEC proc_SearchFeedingHistoryGeneriek ?,?,?");
    $speciesstmt->bindParam(1, $searchStringSubspecies);
    $speciesstmt->bindParam(2, $startSearchDate);
    $speciesstmt->bindParam(3, $endSearchDate);
    $speciesstmt->execute();
    $species = $speciesstmt->fetchAll();
  //  spErrorCaching($speciesstmt);

    $startSearchDateAnimals = null;
    if(isset($_POST['STARTDATEANIMALS']) && !empty($_POST['STARTDATEANIMALS'])) {
        $startSearchDateAnimals = $_POST['STARTDATEANIMALS'];
    }
    $endSearchDateAnimals = null;
    if(isset($_POST['ENDDATEANIMALS'])&& !empty($_POST['ENDDATEANIMALS'])) {
        $endSearchDateAnimals = $_POST['ENDDATEANIMALS'];
    }
    $searchStringAnimals = '';
    if(isset($_POST['SEARCHSTRINGANIMALS'])&& !empty($_POST['SEARCHSTRINGANIMALS'])) {
        $searchStringAnimals = $_POST["SEARCHSTRINGANIMALS"];
    }

    $animalsstmt = $dbh->prepare("EXEC proc_SearchFeedingHistorySpecifiek ?,?,?");
    $animalsstmt->bindParam(1, $searchStringAnimals);
    $animalsstmt->bindParam(2, $startSearchDateAnimals);
    $animalsstmt->bindParam(3, $endSearchDateAnimals);
    $animalsstmt->execute();
    $animals = $animalsstmt->fetchAll();
   // spErrorCaching($animalsstmt);




echo '

<hr>
<div class="row">
    <div class="col-lg-6">
    <form action="index.php?page=feedingHistory" method="post">



    <dl class="dl-horizontal">

               <dt>Van</dt><dd><input name="STARTDATE" type="date" class="form-control"></dd><br>
               <dt>Tot</dt><dd> <input name="ENDDATE" type="date" class="form-control"></dd><br>
               </dl>
<div class="input-group">
      <input name="SEARCHSTRINGSPECIES" type="text" class="form-control" placeholder="Zoek subsoort of recept">
<span class="input-group-btn">
        <button class="btn btn-default" type="submit" >Zoek</button>
        </span>
        </div>



  <!-- /.col-lg-6 -->

  <br><br>
</form>';
echo '
<table class="table table-hover"><tr>
            <th>Subdiersoort</th>
            <th>Recept id</th>
            <th>Datum</th>
            </tr>';
foreach($species as $speciesRow) {
//    if($_SESSION['FUNCTION'])
    echo '<tr>   <td><form action="index.php?page=feedingHistory" method="post"><input type="hidden" name="SEARCHSTRINGSPECIES" value="">
    <button class="btn btn-link" type="submit" name="SEARCHSTRINGHEADSPECIES" value="'.$speciesRow["SubSpeciesName"].'">'.$speciesRow["SubSpeciesName"].'</button></form>
     </td>
     <td>'.$speciesRow['FeedingRecipeID'].'</td>
     <td>'. explode('.', $speciesRow['GeneralDateTime'])[0].'</td>
        ';

    echo'</tr>';
};
echo '
        </table>
    </div>
    <div class="col-lg-6">
    <form action="index.php?page=feedingHistory" method="post">

    <dl class="dl-horizontal">

               <dt>Van</dt><dd><input name="STARTDATEANIMALS" type="date" class="form-control"></dd><br>
               <dt>Tot</dt><dd> <input name="ENDDATEANIMALS" type="date" class="form-control"></dd><br>
               </dl>
    <div class="input-group">
      <input name="SEARCHSTRINGANIMALS" type="text" class="form-control" placeholder="Zoek subsoort op: dier id, diernaam of ingredient">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" >Zoek</button>
      </span>

    </div><!-- /input-group -->

  <!-- /.col-lg-6 -->

  <br><br>
</form>';
echo '
<table class="table table-hover"><tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Recept id</th>
            <th>Datum</th>
</tr>';
foreach($animals as $animal) {
    echo '<tr>
<td>'.$animal["AnimalID"].'</td>
        <td>
            <a href="?page=feedingRecipe">'.$animal["AnimalName"].'</a>
        </td>
        <td>'.$animal["FeedingRecipeID"].'</td>
        <td>'.explode('.', $animal["SpecificDateTime"])[0].'</td>
';

    echo'</tr>';
};
echo '
        </table>
    </div>
</div>';