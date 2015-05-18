<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 18-5-2015
 * Time: 14:08
 */

$searchString = '';
$speciesstmt = $dbh->prepare("EXEC proc_SearchFeedingHistoryGeneriek ?");
$speciesstmt->bindParam(1,$searchString);
$speciesstmt->execute();
$species = $speciesstmt->fetchAll();

if(isset($_POST['SEARCHSTRINGSPECIES'])){
    $startSearchDate = $_POST['']
    $endSearchDate
    $searchStringSubspecies = $_POST["SEARCHSTRINGSPECIES"];
    $speciesstmt = $dbh->prepare("EXEC proc_SearchFeedingHistoryGeneriek ?,?,?");
    $speciesstmt->bindParam(1,$searchStringSubspecies);
    $speciesstmt->bindParam(2,$startSearchDate);
    $speciesstmt->bindParam(3,$endSearchDate);
    $speciesstmt->execute();
    $species = $speciesstmt->fetchAll();
}

echo '

<hr>
<div class="row">
    <div class="col-lg-6">
    <form action="index.php?page=feedingHistory" method="post">



    <dl class="dl-horizontal">

               <dt>Van</dt><dd><input name="STARTDATE" type="date" class="form-control" placeholder="Zoek subsoort op: hoofdsoort of subsoort"></dd><br>
               <dt>Tot</dt><dd> <input name="ENDDATE" type="date" class="form-control" placeholder="Zoek subsoort op: hoofdsoort of subsoort"></dd><br>
               </dl>
<div class="input-group">
      <input name="SEARCHSTRINGSPECIES" type="text" class="form-control" placeholder="Zoek subsoort op: hoofdsoort of subsoort">
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
            <th>VoedingsID</th>
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
</table></div>
<div class="col-lg-6">
    <form action="index.php?page=feedingHistory" method="post">


    <div class="input-group">
      <input name="SEARCHSTRINGSUBSPECIES" type="text" class="form-control" placeholder="Zoek subsoort op: subsoort">
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
        <td><a href="?page=feedingscheme&headspecies='.$subspeciesRow["HeadSpeciesName"].'&subspecies='.$subspeciesRow["SubSpeciesName"].'">'.$subspeciesRow["SubSpeciesName"].'</a></td>
';

    echo'</tr>';
};
echo '
</table></div>
</div>';