<?php
/**
 * Created by PhpStorm.
 * User: koen
 * Date: 1-5-2015
 * Time: 10:20
 */

$staffID = $_SESSION["STAFFID"];

if(isset($_POST['ADDENCLOSURE'])) {
    $environmentName = $_POST['ENVIRONMENT'];
    $areaName = $_POST['AREA'];
    $addEnclosurestmt = $dbh->prepare("proc_addEnclosure ?,?,?");
    $addEnclosurestmt->bindParam(1, $staffID);
    $addEnclosurestmt->bindParam(2, $areaName);
    $addEnclosurestmt->bindParam(3, $environmentName);
    $addEnclosurestmt->execute();
    spErrorCaching($addEnclosurestmt);
}

$allEnvironments = $dbh->prepare("EXEC proc_GetEnvironment");
$allEnvironments->execute();
$environments = $allEnvironments->fetchAll();
$selectedEnivornment = null;
if(isset( $_POST['ENVIRONMENT'])) {
    $selectedEnivornment = $_POST['ENVIRONMENT'];
}

$allAreas = $dbh->prepare("EXEC proc_GetAreaNameByEnvironment ?");
$allAreas->bindParam(1,$selectedEnivornment);
$allAreas->execute();
$areas = $allAreas->fetchAll();
//echo $areas[0];
$selectedArea = null;
if(isset( $_POST['AREA'])) {
    $selectedArea = $_POST['AREA'];
}
$allEnclosures = $dbh->prepare("EXEC proc_GetEnclosureByArea ?");
$allEnclosures->bindParam(1,$selectedArea);
$allEnclosures->execute();
$enclosures = $allEnclosures->fetchAll();


echo ' <div class="row"><div class="col-lg-4"><table class="table table-hover">
    <tr>
        <th>Omgeving</th>
    </tr>';
foreach($environments as $environment) {
    echo '<tr>
            <form action="index.php?page=environment"  method="post"><input type="hidden" name="ENVIRONMENT" value="'.$environment["EnvironmentName"].'">  <td><button class="btn btn-link" type="submit" value="'.$environment["EnvironmentName"].'">'.$environment["EnvironmentName"].'</button><button class="btn btn-default" type="submit" >Gebied aanpassen</button></td></form>
          </tr>';
}

echo "</table><a href='?page=addEnvironment'> <button type='button' class='btn btn-default' >Omgeving toevoegen</button></a></div><div class='col-lg-4'><table class='table table-hover'>
    <tr>
        <th>Gebied: ".$selectedEnivornment."</th>
    </tr>";
foreach($areas as $area) {
    echo '<tr>
           <form action="index.php?page=environment"  method="post"><input type="hidden" name="ENVIRONMENT" value="'.$selectedEnivornment.'"><input type="hidden" name="AREA" value="'.$area["AreaName"].'"> <td><button class="btn btn-link" type="submit" value="'.$area["AreaName"].'">'.$area["AreaName"].'</button><button class="btn btn-default" type="submit" >Omgeving aanpassen</button></td></form>
          </tr>';
}

echo "</table><a href='index.php?page=addArea'> <button type='button' class='btn btn-default'>Gebied toevoegen</button></a></div><div class='col-lg-4'><table class='table table-hover'>
    <tr>
        <th>Verblijf: ".$selectedArea."</th>
    </tr>";
foreach($enclosures as $enclosure) {
    echo '<tr>
            <td>'.$enclosure["EnclosureID"].'     <button class="btn btn-default" type="submit" >Verwijderen</button></td>
          </tr>';
}

echo ' </table>
 <form action="index.php?page=environment"  method="post"><input type="hidden" name="ENVIRONMENT" value="'.$selectedEnivornment.'"><input type="hidden" name="AREA" value="'.$selectedArea.'"><input type="hidden" name="ADDENCLOSURE" value="true"> <td><button class="btn btn-default" type="submit" >Verblijf toevoegen</button></td></form>
</div></div>';
//echo "hohoohoo";
?>
