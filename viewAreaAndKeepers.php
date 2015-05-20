<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 20-5-2015
 * Time: 10:31
 */
if(isset($_POST['ENVIRONMENT']) && isset($_POST['AREA'])) {
    $getStaffstmt = $dbh->prepare("EXEC proc_GetStaffByArea ?,?");
    $getStaffstmt->bindParam(1, $areaName);
    $getStaffstmt->bindParam(2, $environmentName);
    $getStaffstmt->execute();
    $keepers = $getStaffstmt->fetchAll();


    echo '
    <div class="col-lg-6">
        <h3>Dieren</h3>
            <table class="table table-hover">
                <tr>
                    <th>Diernummer</th>
                    <th>Naam</th>
                    <th>Diersoort</th>
                </tr>';
    foreach($keepers as $keeper) {
        echo '<tr>
                    <td>'.$keeper['StaffID'].'</td>
                    <td>'.$keeper['StaffName'].'</td>
                    <td>'.$keeper['Function'].'</td>';


        echo '    </tr>';
    }
     echo '
            </table>
        </div>';
}