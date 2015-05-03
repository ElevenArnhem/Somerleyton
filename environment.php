<?php
/**
 * Created by PhpStorm.
 * User: koen
 * Date: 1-5-2015
 * Time: 10:20
 */

$staffID = $_SESSION["STAFFID"];


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
            <form action="index.php?page=environment"  method="post"><input type="hidden" name="ENVIRONMENT" value="'.$environment["EnvironmentName"].'">  <td><button class="btn btn-link" type="submit" value="'.$environment["EnvironmentName"].'">'.$environment["EnvironmentName"].'</button></td></form>
          </tr>';
}

echo "</table><a href='?page=addEnvironment'> <button type='button' class='btn btn-default' >Omgeving toevoegen</button></a></div><div class='col-lg-4'><table class='table table-hover'>
    <tr>
        <th>Gebied: ".$selectedEnivornment."</th>
    </tr>";
foreach($areas as $area) {
    echo '<tr>
           <form action="index.php?page=environment"  method="post"><input type="hidden" name="ENVIRONMENT" value="'.$selectedEnivornment.'"><input type="hidden" name="AREA" value="'.$area["AreaName"].'"> <td><button class="btn btn-link" type="submit" value="'.$area["AreaName"].'">'.$area["AreaName"].'</button></td></form>
          </tr>';
}

echo "</table><a href='addEnvironment.php'> <button type='button' class='btn btn-default'>Gebied toevoegen</button></a></div><div class='col-lg-4'><table class='table table-hover'>
    <tr>
        <th>Verblijf: ".$selectedArea."</th>
    </tr>";
foreach($enclosures as $enclosure) {
    echo '<tr>
            <td>'.$enclosure["EnclosureID"].'</td>
          </tr>';
}

echo ' </table><button type="button" class="btn btn-default" >Verblijf toevoegen</button></div></div>';
//echo "hohoohoo";
?>
