<?php
$currentStaffID = $_SESSION["STAFFID"];
$staffID = $_GET['staffID'];

$medewerkerStatement = $dbh -> prepare("proc_GetMedewerker ?, ?");
$medewerkerStatement -> bindParam(1, $currentStaffID);
$medewerkerStatement -> bindParam(2, $staffID);
$medewerkerStatement -> execute();
$medewerker = $medewerkerStatement -> fetch();

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
                    Functie:
                </dt>
                <dd>
                <select name="STAFFFUNCTION" type="text" class="form-control" value="'.$medewerker['Functie'].'">
                    <option value="'.$medewerker['Functie'].'">'.$medewerker['Functie'].'</option>';

                    foreach($latinNames as $latinName){
                        echo '<option value="'.$latinName["LatinName"].'">'.$latinName["LatinName"].'</option>';
                     }
                    echo '</select>
                </dd>
                <br>



                    <input name="STAFFFUNCTION" type="text" class="form-control" value="'.$medewerker['Functie'].'">
                </dd>
                <br>
                <dt>
                    Gebied:
                </dt>
                <dd>
                    <!--<input name="STAFFAREA" type="text" class="form-control" value="'.$medewerker['StaffName'].'">-->
                </dd>
                <br>
                <dt>
                    Wachtwoord:
                </dt>
                <dd>
                    <!--<input name="STAFFPASSWORD" type="text" class="form-control" value="'.$medewerker['StaffName'].'">-->
                </dd>
                <br>
';
echo '</div>';
?>