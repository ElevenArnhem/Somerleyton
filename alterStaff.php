<?php
$currentStaffID = $_SESSION["STAFFID"];
$staffID = $_GET['staffID'];

$medewerkerStatement = $dbh -> prepare("proc_GetMedewerker ?, ?");
$medewerkerStatement -> bindParam(1, $currentStaffID);
$medewerkerStatement -> bindParam(2, $staffID);
$medewerkerStatement -> execute();
$medewerker = $medewerkerStatement -> fetch();

$functionStatement = $dbh -> prepare("proc_getAllFunctions");
$functionStatement -> execute();
$functions = $functionStatement -> fetchAll();

$areasForUserIDStatement = $dbh -> prepare("proc_getAreasFromStaffID ?");
$areasForUserIDStatement -> bindParam(1, $staffID);
$areasForUserIDStatement -> execute();
$areasForUserID = $areasForUserIDStatement -> fetchAll();

echo '
        <br>
            <div class="col-lg-6">
              <h2>Medewerker gegevens</h2>
              <form action="index.php?page=alterStaff&animalID='.$staffID.'" method="post">
              <dl class="dl-horizontal">
                <dt>
                    Nummer:
                </dt>
                <dd>
                    <label>'.$staffID.'</label>
                </dd>
                <br>
                <dt>
                    Naam:
                </dt>
                <dd>
                    <input name="STAFFNAME" type="text" class="form-control" value="'.$medewerker['StaffName'].'">
                </dd>
                <br>

                <dt>
                    Telefoon nummer:
                </dt>
                <dd>
                    <input name="STAFFPHONENUMBER" type="text" class="form-control" value="'.$medewerker['PhoneNumber'].'">
                </dd>
                <br>

                <dt>
                    Adres:
                </dt>
                <dd>
                    <input name="STAFFADDRESS" type="text" class="form-control" value="'.$medewerker['Address'].'">
                </dd>
                <br>

                <dt>
                    Postcode:
                </dt>
                <dd>
                    <input name="STAFFZIPCODE" type="text" class="form-control" value="'.$medewerker['ZipCode'].'">
                </dd>
                <br>

                <dt>
                    Stad:
                </dt>
                <dd>
                    <input name="STAFFCITY" type="text" class="form-control" value="'.$medewerker['City'].'">
                </dd>
                <br>

                <dt>
                    E-mail:
                </dt>
                <dd>
                    <input name="STAFFEMAIL" type="text" class="form-control" value="'.$medewerker['Email'].'">
                </dd>
                <br>
                <dt>
                    Geboorte datum:
                </dt>
                <dd>
                    <input name="STAFFBIRTHDATE" type="date" class="form-control" value="'.$medewerker['Birthdate'].'">
                </dd>
                <br>
                <dt>
                    Functie:
                </dt>
                <dd>
                <select name="STAFFFUNCTION" type="text" class="form-control" value="'.$medewerker['Functie'].'">';
                    foreach($functions as $function){
                        echo '<option value="'.$function["name"].'">'.$function["name"].'</option>';
                     }
                    echo '</select>
                </dd>
                <br>
                <dt>
                    Verzorger van:
                </dt>
                <dd>
                     <table class="table table-hover">
                        <tr>
                            <th>Gebied</th>
                        </tr>';
                    foreach($areasForUserID as $areaForUserID){
                        echo    '<tr>
                                    <td>'.$areaForUserID["AreaName"].'</td>
                                 </tr>';
                    }
                    echo '</select>
                </table>
                </dd>
                <br>
                <dt></dt>
                <dd>';

                if($medewerker['IsActive'] == 0)
                    echo '<input type="checkbox" name="STAFFISACTIVE" value="IsSelected">Actief?<br>';
                else
                    echo '<input type="checkbox" name="STAFFISACTIVE" value="IsSelected" checked>Actief?<br>
                </dd>
';
echo '</div>';
?>