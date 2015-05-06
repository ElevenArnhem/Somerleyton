<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 14:41
 */
if(isset($_GET['day'])) {

}
$staffID = $_SESSION['STAFFID'];

$schedulestmt = $dbh->prepare("EXEC proc_getSchedule ?");
$schedulestmt->bindParam(1, $staffID);
$schedulestmt->execute();
$schedule = $schedulestmt->fetchAll();

$keepersstmt = $dbh->prepare("EXEC proc_getKeepers");
$keepersstmt->execute();
$keepers = $keepersstmt->fetchAll();

$allEnvironments = $dbh->prepare("EXEC proc_GetEnvironment");
$allEnvironments->execute();
$environments = $allEnvironments->fetchAll();

$allAreas = $dbh->prepare("EXEC proc_GetAreaName");
$allAreas->execute();
$areas = $allAreas->fetchAll();

echo '
<table class="table table-hover"><tr>
            <th>Keeper</th>
            <th>Begintijd</th>
            <th>Eindtijd</th>
            <th>Omgeving</th>
            <th>Gebied</th>
</tr>';
foreach($schedule as $scheduleRow) {
    echo '<tr>
            <td>'.$scheduleRow["StaffID"].'</td>
            <td>'.$scheduleRow["StartTime"].'</td>
            <td>'.$scheduleRow["EndTime"].'</td>
            <td>'.$scheduleRow["EnvironmentName"].'</td>
            <td>'.$scheduleRow["AreaName"].'</td>
</tr>';
}
echo '
<tr><form action="index.php?page=schedule" method="post">
    <td><select name="KEEPER" type="text" class="form-control">';
foreach($keepers as $keeper) {
    echo '<option value="'.$keeper["StaffID"].'">'.$keeper["StaffName"].'</option>';
}
echo'</select></td>
    <td><input name="STARTTIME" type="time" class="form-control" ></td>
    <td><input name="ENDTIME" type="time" class="form-control" ></td>
    <td><select name="ENVIRONMENTNAME" type="text" class="form-control">';
foreach($environments as $environment) {
    echo '<option value="'.$environment["EnvironmentName"].'">'.$environment["EnvironmentName"].'</option>';
}
echo'</select></td>
    <td><select name="AREANAME" type="text" class="form-control">';
foreach($areas as $area) {
    echo '<option value="'.$area["AreaName"].'">'.$area["AreaName"].'</option>';
}
echo'</select></td>
<td><button class="btn btn-primary" type="submit">Voeg toe</button>
</form></td>
    </tr>
    </table>
';