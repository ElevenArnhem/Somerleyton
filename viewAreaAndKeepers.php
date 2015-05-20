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
            </table>
        </div>';
}