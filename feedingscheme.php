<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 13-5-2015
 * Time: 13:35
 */



$headSpeciesName = $_GET['headspecies'];
$subSpeciesName = $_GET['subspecies'];

if(isset($_POST['ADDGENERICFEEDINGSCHEMEROW'])) {
    $addGenericFeedingSchemestmt = $dbh->prepare("EXEC proc_AddGeneriekVoerschema ?,?,?,?,?");
    $addGenericFeedingSchemestmt->bindParam(1,$headSpeciesName);
    $addGenericFeedingSchemestmt->bindParam(2,$subSpeciesName);
    $addGenericFeedingSchemestmt->bindParam(3,$feedingRecipeID);
    $addGenericFeedingSchemestmt->bindParam(4,$dayGeneral);
    $addGenericFeedingSchemestmt->bindParam(5,$timeGeneral);
    $addGenericFeedingSchemestmt->execute();
}
$genericFeedingSchemestmt = $dbh->prepare("EXEC proc_GetGeneriekVoerSchema ?,?");
$genericFeedingSchemestmt->bindParam(1,$subSpeciesName);
$genericFeedingSchemestmt->bindParam(2,$headSpeciesName);
$genericFeedingSchemestmt->execute();
$genericFeedingScheme = $genericFeedingSchemestmt->fetchAll();

$recipestmt = $dbh->prepare("EXEC proc_GetRecipe");
$recipestmt->execute();
$recipe = $recipestmt->fetchAll();


echo '<h2>Voedingsschema</h1>
    <h3>Hoofdsoort: '.$_GET['headspecies'].'</h2>
    <h3>Subsoort: '.$_GET['subspecies'].'</h2>
     <hr>
     <div class="row">
    <div class="col-lg-4">
    <h4>Generiek voedingsschema</h4>
<table class="table table-hover"><tr>
            <th>Dag</th>
            <th>Tijdstip</th>
            <th>Recept</th>
            </tr>';
foreach($genericFeedingScheme as $genericFeedingSchemeRow) {
//    if($_SESSION['FUNCTION'])
    echo '<tr>
<td>'.$genericFeedingSchemeRow['DayGeneral'].'</td>
<td>'.$genericFeedingSchemeRow['TimeGeneral'].'</td>
<td>'.$genericFeedingSchemeRow['FeedingRecipeID'].'</td>
        ';

    echo'</tr>';
};
echo '
<tr><form action="index.php?page=feedingscheme&headspecies='.$_GET['headspecies'].'&subspecies='.$_GET['headspecies'].'" method="post">
<td><select name="DayGeneral" type="text" class="form-control" required>
    <option>maandag</option>
    <option>dinsdag</option>
    <option>woensdag</option>
    <option>donderdag</option>
    <option>vrijdag</option>
    <option>zaterdag</option>
    <option>zondag</option>
    </select></td>
<td><input name="TimeGeneral" type="time" class="form-control" required></td>
<td><select name="FeedingRecipeID"  type="text" class="form-control" required>';
foreach($recipe as $recipeRow) {
    echo '
    <option>'.$recipeRow['FeedingRecipeID'].'</option>';
}
echo '
    </select></td>
    </tr>
    <tr><td></td>
    <td><button name="ADDGENERICFEEDINGSCHEMEROW" type="button" class="btn btn-default">Voeg toe</button></td>
</tr></form>
</table>

    </div></div>';