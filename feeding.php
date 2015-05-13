<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 13-5-2015
 * Time: 12:40
 */

if(isset($_POST["SEARCHSTRING"])) {
    $searchString = $_POST["SEARCHSTRING"];
    $subspeciesstmt = $dbh->prepare("EXEC proc_SearchSpecies ?");
    $subspeciesstmt->bindParam(1,$searchString);
    $subspeciesstmt->execute();
    $subspecies = $subspeciesstmt->fetchAll(PDO::FETCH_ASSOC);


} if(!isset($_POST["SEARCHSTRING"])) {
    $subspeciesstmt = $dbh->prepare("EXEC proc_SearchSpecies ''");
    $subspeciesstmt->execute();
    $subspecies = $subspeciesstmt->fetchAll();

}


echo '

<hr>
<form action="index.php?page=feeding" method="post">
  <div class="col-lg-6">

    <div class="input-group">
      <input name="SEARCHSTRING" type="text" class="form-control" placeholder="Zoek subsoort op: hoofdsoort of subsoort">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" >Zoek</button>
      </span>

    </div><!-- /input-group -->

  </div><!-- /.col-lg-6 -->

  <br><br>
</form>';
echo '
<table class="table table-hover"><tr>
            <th>Hoofdsoort</th>
            <th>Subsoort</th>
</tr>';
foreach($subspecies as $subspeciesRow) {
//    if($_SESSION['FUNCTION'])
    echo '<tr><td>'.$subspeciesRow["HeadSpeciesName"].'</td>
        <td><a href="?page=subspecies&headspecies='.$subspeciesRow["HeadSpeciesName"].'&subspecies='.$subspeciesRow["SubSpeciesName"].'">'.$subspeciesRow["SubSpeciesName"].'</a></td>
';

    echo'</tr>';
};
echo '
</table>';