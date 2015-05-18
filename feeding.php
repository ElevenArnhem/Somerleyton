<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 13-5-2015
 * Time: 12:40
 */

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
<a class="btn btn-primary" href="index.php?page=feedingHistory">Voedings geschiedenis</a>
<hr>
<div class="row">
    <div class="col-lg-6">
    <form action="index.php?page=feeding" method="post">


    <div class="input-group">
      <input name="SEARCHSTRINGHEADSPECIES" type="text" class="form-control" placeholder="Zoek subsoort op: hoofdsoort">
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