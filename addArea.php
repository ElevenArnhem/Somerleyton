<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 00:16
 */
if(isset($_POST['ENVIRONMENTNAME'])) {
    $staffID = $_SESSION['STAFFID'];
    $environmentName = $_POST['ENVIRONMENTNAME'];
    $areaName = $_POST['AREANAME'];
    $headKeeperID = $_POST['HEADKEEPER'];
    $addEnvironment = $dbh->prepare("proc_addArea ?,?,?,?");
    $addEnvironment->bindParam(1, $staffID);
    $addEnvironment->bindParam(2, $environmentName);
    $addEnvironment->bindParam(3, $areaName);
    $addEnvironment->bindParam(4, $headKeeperID);
    $addEnvironment->execute();
    spErrorCaching($addEnvironment);
}

$allEnvironments = $dbh->prepare("EXEC proc_GetEnvironment");
$allEnvironments->execute();
$environments = $allEnvironments->fetchAll();

$activeSaff = 1;
$allStaffstmt = $dbh->prepare("EXEC proc_getStaffMembers ?");
$allStaffstmt->bindParam(1, $activeSaff);
$allStaffstmt->execute();
$allStaff = $allStaffstmt->fetchAll();

echo '<div class="col-lg-4">

<h1>Gebied toevoegen</h1>
<form action="index.php?page=addArea" method="post">
 <dl class="dl-horizontal">
<dt>Naam</dt><dd><input name="AREANAME" type="text" class="form-control" value="" placeholder="gebied naam" required></dd><br>
<dt>Omgeving</dt><dd><select name="ENVIRONMENTNAME" type="text" class="form-control" >';
foreach($environments as $environment) {
    echo '<option value="'.$environment["EnvironmentName"].'">'.$environment["EnvironmentName"].'</option>';
}

echo'</select></dd><br>
<dt>Hoofddierverzorger</dt><dd><select name="HEADKEEPER" type="text" class="form-control" >';
foreach($allStaff as $staff) {
    echo '<option value="'.$staff["StaffID"].'">'.$staff["StaffName"].'</option>';
}
echo'</select></dd><br>
</dl>
<button class="btn btn-primary" type="submit">Toevoegen</button>
</form>
</div>';