<?php

if(!isset($_POST["SEARCHSTRING"])) {
    $animalstmt = $dbh->prepare("Select * from Animal ");
    $animalstmt->execute();
    $animals = $animalstmt->fetchAll();
} else {
    $searchString = $_POST["SEARCHSTRING"];
    $aliveAnimal = $_POST["ALIVEANIMAL"];
    $animalstmt = $dbh->prepare("EXEC proc_searchAnimal ?,?");
    $animalstmt->bindParam(1,$searchString);
    $animalstmt->bindParam(2,$aliveAnimal);
    $animalstmt->execute();
    $animals = $animalstmt->fetchAll(PDO::FETCH_ASSOC);
//    while($row = $animalstmt->fetch()) {
//
//       echo $row[0];
//}

}


echo '
</div>
<hr>
<form action="index.php" method="post">
  <div class="col-lg-6">

    <div class="input-group">
      <input name="SEARCHSTRING" type="text" class="form-control" placeholder="Zoek dieren op: id, naam, soort, verblijf">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" >Zoek</button>
      </span>

    </div><!-- /input-group -->

  </div><!-- /.col-lg-6 -->
<br /><br />
<div class="radio">
  <label>
    <input  type="radio" name="ALIVEANIMAL" id="optionsRadios1" value="1" checked>
    Levende dieren
</label>
</div>
<div class="radio">
  <label>
    <input type="radio" name="ALIVEANIMAL" id="0" value="option2">
   Overleden dieren
</label>
</div>
  <br><br>
</form>
<table class="table table-hover"><tr>
            <th>AnimalID</th>
            <th>AnimalName</th>
            <th>BirthDate</th>
            <th>BirthPlace</th>
            <th>LatinName</th>
            <th>SubSpeciesName</th>
            <th>AreaName</th>
            <th>EnclosureID</th>
</tr>';
foreach($animals as $animal) {
    echo '<tr><td>'.$animal["AnimalID"].'</td>
        <td>'.$animal["AnimalName"].'</td>
        <td>'.$animal["BirthDate"].'</td>
        <td>'.$animal["BirthPlace"].'</td>
        <td>'.$animal["LatinName"].'</td>
        <td>'.$animal["SubSpeciesName"].'</td>
        <td>'.$animal["AreaName"].'</td>
        <td>'.$animal["EnclosureID"].'</td></tr>';
};
echo '
</table>';
if($_SESSION['FUNCTION'] == 'HeadKeeper') {
    echo '

    <div class="btn-group" role="group">
       <a href="?page=addAnimal"> <button type="button" class="btn btn-default" >Toevoegen</button></a>
    </div>
';
}