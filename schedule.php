<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 14:41
 */
if(isset($_GET['day'])) {

}
date_default_timezone_set('UTC+1');
$staffID = $_SESSION['STAFFID'];
$date = date("Y-m-d H:i:s");
if(isset($_POST['DATE'])) {
    $date = $_POST['DATE'];
}
$length = 1;
if(isset($_POST['LENGTH'])) {
    $length = $_POST['LENGTH'];
}
$schedulestmt = $dbh->prepare("EXEC proc_getSchedule ?,?,?");
$schedulestmt->bindParam(1, $date);
$schedulestmt->bindParam(2, $length);
$schedulestmt->bindParam(3, $staffID);

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
<form method="post" action="index.php?page=schedule">
 <dt>Datum</dt><dd><input name="DATE" type="date" class="form-control" value="01-01-2015"></dd><br>
  <dt>Lengte</dt><dd><select name="LENGTH" type="text" class="form-control">
  <option value="1">1 Dag</option>
  <option value="7">1 Week</option>
  <option value="30">1 Maand</option>
  </select></dd><br>
 <button class="btn btn-primary" type="submit">Check rooster</button>
 </form><br>
<table class="table table-hover"><tr>
            <th>Keeper</th>
            <th>Begintijd</th>
            <th>Eindtijd</th>
            <th>Omgeving</th>
            <th>Gebied</th>
</tr>';
foreach($schedule as $scheduleRow) {
    echo '<tr>
            <td>'.$scheduleRow["StaffName"].'</td>
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