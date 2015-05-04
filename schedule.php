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
    <td>09:00</td>
    <td>12:00</td>
    <td>SeaLife zee vissen</td>
    </tr>
    </table>
';