<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 00:16
 */
$environment = null;
if(isset($_POST['AREA'])) {
    $area = $_POST['AREA'];
}
if(isset($_POST['TYPE'])) {
    if($_POST['TYPE'] == 'addArea' || isset($_POST['ENVIRONMENTNAME'])) {
        $staffID = $_SESSION['STAFFID'];
        $environmentName = $_POST['ENVIRONMENTNAME'];
        $areaName = $_POST['AREANAME'];
        $headKeeperID = $_POST['HEADKEEPER'];
        $addAreastmt = $dbh->prepare("proc_addArea ?,?,?,?");
        $addAreastmt->bindParam(1, $staffID);
        $addAreastmt->bindParam(2, $areaName);
        $addAreastmt->bindParam(3, $environmentName);
        $addAreastmt->bindParam(4, $headKeeperID);
        $addAreastmt->execute();
        spErrorCaching($addAreastmt);
    }else if($_POST['TYPE'] == 'changeArea' || isset($_POST['ENVIRONMENTNAME'])) {
        $staffID = $_SESSION['STAFFID'];
        $environmentName = $_POST['ENVIRONMENTNAME'];
        $areaName = $_POST['AREANAME'];
        $headKeeperID = $_POST['HEADKEEPER'];
        $changeAreastmt = $dbh->prepare("proc_alterArea ?,?,?,?,?");
        $changeAreastmt->bindParam(1, $staffID);
        $changeAreastmt->bindParam(2, $areaName);
        $changeAreastmt->bindParam(3, $headKeeperID);
        $changeAreastmt->bindParam(4, $environmentName);
        $changeAreastmt->bindParam(5, $oldAreaName);
        $changeAreastmt->execute();
        spErrorCaching($changeAreastmt);
        $area = $areaName;
    }
//    @staffID			INT,
//	 @newAreaName		VARCHAR(50),
//	 @newHeadkeeperID	INTEGER,
//	 @environmentName	VARCHAR(50),
//	 @areaName			VARCHAR(50),
//	 @HeadkeeperID		INTEGER
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
<dt>Naam</dt><dd><input name="AREANAME" type="text" class="form-control" value="'; if(isset($_POST['AREA']) || isset($_POST['ENVIRONMENTNAME'])) {echo $area;} echo'" placeholder="gebied naam" required></dd><br>
<dt>Omgeving</dt><dd><select name="ENVIRONMENTNAME" type="text" class="form-control" '; if(isset($_POST['AREA']) || isset($_POST['ENVIRONMENTNAME'])) {echo 'disabled';} echo'>';
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
<input type="hidden" name="OLDAREANAME" value="'; if(isset($_POST['AREA']) || isset($_POST['ENVIRONMENTNAME'])) {echo $area;} echo'">
<button class="btn btn-primary" type="submit">Opslaan</button>
</form>
</div>';