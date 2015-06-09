<?php
    if(canRead() && canUpdate()) {

        $currentStaffID = $_SESSION["STAFFID"];
        $staffID = $_GET['staffID'];

        if (isset($_POST['STAFFID'])) {
            $currentStaffID = $_SESSION["STAFFID"];
            $staffID = $_POST['STAFFID'];
            $newStaffName = $_POST['STAFFNAME'];
            $newStaffPhoneNumber = $_POST['STAFFPHONENUMBER'];
            $newStaffAddress = $_POST['STAFFADDRESS'];
            $newStaffZipCode = $_POST['STAFFZIPCODE'];
            $newStaffCity = $_POST['STAFFCITY'];
            $newStaffEmail = $_POST['STAFFEMAIL'];
            $newStaffBirthDate = $_POST['STAFFBIRTHDATE'];
            $newStaffFunction = $_POST['STAFFFUNCTION'];

            if(isset($_POST['STAFFISACTIVE'])) {
                $newStaffIsActive = 1;
            } else {
                $newStaffIsActive = 0;
            }
            $alterMedewerkerStatement = $dbh->prepare("proc_alterStaff ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
            $alterMedewerkerStatement->bindParam(1, $currentStaffID);
            $alterMedewerkerStatement->bindParam(2, $staffID);
            $alterMedewerkerStatement->bindParam(3, $newStaffName);
            $alterMedewerkerStatement->bindParam(4, $newStaffPhoneNumber);
            $alterMedewerkerStatement->bindParam(5, $newStaffAddress);
            $alterMedewerkerStatement->bindParam(6, $newStaffZipCode);
            $alterMedewerkerStatement->bindParam(7, $newStaffCity);
            $alterMedewerkerStatement->bindParam(8, $newStaffEmail);
            $alterMedewerkerStatement->bindParam(9, $newStaffBirthDate);
            $alterMedewerkerStatement->bindParam(10, $newStaffFunction);
            $alterMedewerkerStatement->bindParam(11, $newStaffIsActive);
            $alterMedewerkerStatement->execute();

            spErrorCaching($alterMedewerkerStatement);
        }

        $medewerkerStatement = $dbh->prepare("proc_GetMedewerker ?, ?");
        $medewerkerStatement->bindParam(1, $currentStaffID);
        $medewerkerStatement->bindParam(2, $staffID);
        $medewerkerStatement->execute();
        $medewerker = $medewerkerStatement->fetch();

        $functionStatement = $dbh->prepare("proc_getAllFunctions");
        $functionStatement->execute();
        $functions = $functionStatement->fetchAll();

        $areasForUserIDStatement = $dbh->prepare("proc_getAreasFromStaffID ?");
        $areasForUserIDStatement->bindParam(1, $staffID);
        $areasForUserIDStatement->execute();
        $areasForUserID = $areasForUserIDStatement->fetchAll();

        echo '
        <br>
        <div class="col-lg-6">
            <h2>Medewerker gegevens</h2>
            <form action="index.php?page=alterStaff&staffID=' . $staffID . '" method="post">
                <dl class="dl-horizontal">
                    <dt>
                        Nummer:
                    </dt>
                    <dd>
                        <label>' . $staffID . '</label>
                        <input type="hidden" name="STAFFID" value="' . $staffID . '">
                    </dd>
                    <br>
                    <dt>
                        Naam:
                    </dt>
                    <dd>
                        <input name="STAFFNAME" type="text" class="form-control" value="' . $medewerker['StaffName'] . '"  maxlength="50" required>
                    </dd>
                    <br>
                    <dt>
                        Telefoon nummer:
                    </dt>
                    <dd>
                        <input name="STAFFPHONENUMBER" type="text" class="form-control" value="' . $medewerker['PhoneNumber'] . '" maxlength="20" required>
                    </dd>
                    <br>
                    <dt>
                        Adres:
                    </dt>
                    <dd>
                        <input name="STAFFADDRESS" type="text" class="form-control" value="' . $medewerker['Address'] . '" maxlength="50" required>
                    </dd>
                    <br>
                    <dt>
                        Postcode:
                    </dt>
                    <dd>
                        <input name="STAFFZIPCODE" type="text" class="form-control" value="' . $medewerker['ZipCode'] . '" maxlength="50" required>
                    </dd>
                    <br>
                    <dt>
                        Stad:
                    </dt>
                    <dd>
                        <input name="STAFFCITY" type="text" class="form-control" value="' . $medewerker['City'] . '" maxlength="50" required>
                    </dd>
                    <br>
                    <dt>
                        E-mail:
                    </dt>
                    <dd>
                        <input name="STAFFEMAIL" type="email" class="form-control" value="' . $medewerker['Email'] . '" maxlength="50">
                    </dd>
                    <br>
                    <dt>
                        Geboorte datum:
                    </dt>
                    <dd>
                        <input name="STAFFBIRTHDATE" type="date" class="form-control" value="' . $medewerker['Birthdate'] . '" required>
                    </dd>
                    <br>
                    <dt>
                        Functie:
                    </dt>
                    <dd>
                        <select name="STAFFFUNCTION" type="text" class="form-control" value="' . $medewerker['Functie'] . '">
                            <option value="' . $medewerker['Functie'] . '" selected>' . $medewerker['Functie'] . '</option>';
                            foreach ($functions as $function) {
                                echo '<option value="' . $function["name"] . '">' . $function["name"] . '</option>';
                            }
                            echo '
                        </select>
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
                            foreach ($areasForUserID as $areaForUserID) {
                                echo '
                                <tr>
                                    <td>' . $areaForUserID["AreaName"] . '</td>
                                 </tr>';
                            }
                            echo '
                        </table>
                    </dd>
                    <br>
                    <dt></dt>
                    <dd>';
                        if ($medewerker['IsActive'] == 0) {
                            echo '<input type="checkbox" name="STAFFISACTIVE" value="0">Actief?<br>';
                        }else {
                            echo '<input type="checkbox" name="STAFFISACTIVE" value="1" checked>Actief?<br>';
                        }
                        echo'
                    </dd>
                    <button class="btn btn-lg btn-primary" type="submit">Opslaan</button>
                </dl>
            </form>
        </div>';
    }