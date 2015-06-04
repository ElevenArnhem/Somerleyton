<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 20-5-2015
 * Time: 10:31
 */
if(isset($_POST['ENVIRONMENT']) && isset($_POST['AREA'])) {
    $areaName = $_POST['AREA'];
    $environmentName = $_POST['ENVIRONMENT'];
    $getStaffstmt = $dbh->prepare("EXEC proc_GetStaffByArea ?,?");
    $getStaffstmt->bindParam(1, $areaName);
    $getStaffstmt->bindParam(2, $environmentName);
    $getStaffstmt->execute();

    $keepers = $getStaffstmt->fetchAll();
    $getKeepersstmt = $dbh->prepare(" proc_getKeepers");
    $getKeepersstmt->execute();
    $allKeepers = $getKeepersstmt->fetchAll();

    if(isset($_POST['newKeeper'])) {
        $newKeeperID = $_POST['newKeeper'];
        $staffID = $_SESSION['STAFFID'];
        $addKeeperstmt = $dbh->prepare(" proc_addKeeperToArea ?,?,?,?");
        $addKeeperstmt->bindParam(1, $staffID);
        $addKeeperstmt->bindParam(2, $newKeeperID);
        $addKeeperstmt->bindParam(3, $environmentName);
        $addKeeperstmt->bindParam(4, $areaName);
        $addKeeperstmt->execute();
    }


    echo '
    <div class="col-lg-6">
     <h2>Medewerkers</h2>
    <h3>Omgeving: '.$_POST['ENVIRONMENT'].'</h3>
<h3>Gebied: '.$_POST['AREA'].'</h3><br>
            <hr>
            <table class="table table-hover">
                <tr>
                    <th>Medewerkernummer</th>
                    <th>Naam</th>
                    <th>Functie</th>
                </tr>';
    foreach($keepers as $keeper) {
        echo '<tr>
                    <td>'.$keeper['StaffID'].'</td>
                    <td>'.$keeper['StaffName'].'</td>
                    <td>'.$keeper['Function'].'</td>';


        echo '    </tr>';
    }
        echo '
                <tr><form action="index.php?page=viewAreaAndKeepers" method="post">
                    <td></td><td><input type="hidden" name="ENVIRONMENT" value="'.$_POST['ENVIRONMENT'].'">
                    <input type="hidden" name="AREA" value="'.$_POST['AREA'].'">
                        <select name="newKeeper" class="form-control" required>';
                            foreach($allKeepers as $newKeeper) {
                                $keeperExists = false;
                                foreach($keepers as $keeper) {
                                    if($newKeeper['StaffID'] == $keeper['StaffID']) {
                                        $keeperExists = true;
                                    }
                                }
                                if(!$keeperExists) {
                                    echo '<option value="' . $newKeeper['StaffID'] . '">' . $newKeeper['StaffName'] . '</option>';
                                }
                            }
                    echo '</select>
                    </td>
                    <td>
                        <button type="submit" name="btnAddItem" class="btn btn-link" aria-label="Left Align">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" />
                        </button>
                    </td>

                </form></tr>';

     echo '
            </table>
        </div>';
}