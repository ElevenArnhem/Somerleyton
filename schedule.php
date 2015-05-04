<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 14:41
 */
if(isset($_GET['day'])) {

}

$keepersstmt = $dbh->prepare("EXEC proc_getKeepers");
$keepersstmt->execute();
$keepers = $keepersstmt->fetchAll();

$allAreas = $dbh->prepare("EXEC proc_GetAreaName");
//$allAreas->bindParam(1,$selectedEnivornment);
$allAreas->execute();
$areas = $allAreas->fetchAll();

echo '
<table class="table table-hover"><tr>
            <th>Keeper</th>
            <th>Begintijd</th>
            <th>EindTijd</th>
            <th>Waar</th>
</tr>
<tr>
    <td><select name="KEEPER" type="text" class="form-control">';
foreach($keepers as $keeper) {
    echo '<option value="'.$keeper["StaffID"].'">'.$keeper["StaffName"].'</option>';
}
echo'</select></td>
    <td><input name="STARTTIME" type="time" class="form-control" ></td>
    <td><input name="ENDTIME" type="time" class="form-control" ></td>
    <td><td><select name="AREANAME" type="text" class="form-control">';
foreach($areas as $area) {
    echo '<option value="'.$area["AreaName"].'">'.$area["AreaName"].'</option>';
}
echo'</select></td>
    </tr>
    </table>
';