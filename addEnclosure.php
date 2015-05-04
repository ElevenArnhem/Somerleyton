<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 00:17
 */
if(isset($_POST['ENVIRONMENTNAME'])) {
    $staffID = $_SESSION['STAFFID'];
    $environmentName = $_POST['ENVIRONMENTNAME'];
    $areaName = $_POST['AREANAME'];
    $headKeeperID = $_POST['HEADKEEPER'];
    $addEnclosurestmt = $dbh->prepare("proc_addEnclosure ?,?,?");
    $addEnclosurestmt->bindParam(1, $staffID);
    $addEnclosurestmt->bindParam(2, $areaName);
    $addEnclosurestmt->bindParam(3, $environmentName);
    $addEnclosurestmt->execute();
    spErrorCaching($addEnclosurestmt);
}

echo '<div class="col-lg-4">

<h1>Verblijf toevoegen</h1>
<form action="index.php?page=addEnclosure" method="post">
 <dl class="dl-horizontal">
<dt>Naam</dt><dd><input name="AREANAME" type="text" class="form-control" value="" placeholder="gebied naam" required></dd><br>
<dt>Omgeving</dt><dd><select name="ENVIRONMENTNAME" type="text" class="form-control" >';
foreach($environments as $environment) {
    echo '<option value="'.$environment["EnvironmentName"].'">'.$environment["EnvironmentName"].'</option>';
}

echo'</select></dd><br>
<dt>Gebied</dt><dd><select name="AREANAME" type="text" class="form-control" >';
foreach($areas as $area) {
    echo '<option value="'.$area["AreaName"].'">'.$area["AreaName"].'</option>';
}

echo'</select></dd><br>

</dl>
<button class="btn btn-primary" type="submit">Toevoegen</button>
</form>
</div>';