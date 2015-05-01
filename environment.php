<?php
/**
 * Created by PhpStorm.
 * User: koen
 * Date: 1-5-2015
 * Time: 10:20
 */

echo $_SESSION["STAFFID"];

$allEnvironments = $dbh->prepare("EXEC proc_GetEnvironment");
$allEnvironments->execute();
$environments = $allEnvironments->fetchAll();

$allAreas = $dbh->prepare("EXEC proc_GetAreaName");
$allAreas->execute();
$areas = $allAreas->fetchAll();

$allEnclosures = $dbh->prepare("EXEC proc_GetEnclosure");
$allEnclosures->execute();
$enclosures = $allEnclosures->fetchAll();

echo ' <div class="row"><div class="col-lg-4"><table class="table table-hover">
    <tr>
        <th>Omgeving</th>
    </tr>';
foreach($environments as $environment) {
    echo '<tr>
            <td>'.$environment["EnvironmentName"].'</td>
          </tr>';
}

echo "</table><button type='button' class='btn btn-default' >Omgeving toevoegen</button></div><div class='col-lg-4'><table class='table table-hover'>
    <tr>
        <th>Gebied</th>
    </tr>";
foreach($areas as $area) {
    echo '<tr>
            <td>'.$area["AreaName"].'</td>
          </tr>';
}

echo "</table><button type='button' class='btn btn-default' >Gebied toevoegen</button></div><div class='col-lg-4'><table class='table table-hover'>
    <tr>
        <th>Verblijf</th>
    </tr>";
foreach($enclosures as $enclosure) {
    echo '<tr>
            <td>'.$enclosure["EnclosureID"].'</td>
          </tr>';
}

echo ' </table><button type="button" class="btn btn-default" >Verblijf toevoegen</button></div></div>';
?>
