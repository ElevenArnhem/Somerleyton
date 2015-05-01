<?php
if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
    $currentStaffID = $_SESSION["STAFFID"];

    if (isset($_POST['STAFFNAME'])) {
        $newStaffName = $_POST['STAFFNAME'];
        $newStaffPhoneNumber = $_POST['STAFFPHONENUMBER'];
        $newStaffAddress = $_POST['STAFFADDRESS'];
        $newStaffZipCode = $_POST['STAFFZIPCODE'];
        $newStaffCity = $_POST['STAFFCITY'];
        $newStaffEmail = $_POST['STAFFEMAIL'];
        $newStaffBirthDate = $_POST['STAFFBIRTHDATE'];
        $newStaffFunction = $_POST['STAFFFUNCTION'];

        if(isset($_POST['STAFFISACTIVE']))
            $newStaffIsActive = 1;
        else
            $newStaffIsActive= 0;

        if(isset($_POST['STAFFPASSWORD'])) {

            $salt = substr(str_replace('+','.',base64_encode(md5(mt_rand(), true))),0,16);
            $rounds = 1000;
            $staffPassword = crypt($_POST['STAFFPASSWORD'], sprintf('$6$rounds=%d$%s$', $rounds, $salt));;
        }
        else
            $staffPassword = '';

        $alterMedewerkerStatement = $dbh->prepare("proc_addStaff ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
        $alterMedewerkerStatement->bindParam(1, $currentStaffID);
        $alterMedewerkerStatement->bindParam(2, $newStaffName);
        $alterMedewerkerStatement->bindParam(3, $staffPassword);
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

    $functionStatement = $dbh->prepare("proc_getAllFunctions");
    $functionStatement->execute();
    $functions = $functionStatement->fetchAll();

    echo '
        <br>
            <div class="col-lg-6">
              <h2>Medewerker toevoegen</h2>
              <form action="index.php?page=addStaff" method="post">
              <dl class="dl-horizontal">
                <dt>
                    Naam:
                </dt>
                <dd>
                    <input name="STAFFNAME" type="text" class="form-control" required>
                </dd>
                <br>

                <dt>
                    Telefoon nummer:
                </dt>
                <dd>
                    <input name="STAFFPHONENUMBER" type="text" class="form-control" required>
                </dd>
                <br>

                <dt>
                    Adres:
                </dt>
                <dd>
                    <input name="STAFFADDRESS" type="text" class="form-control" required>
                </dd>
                <br>

                <dt>
                    Postcode:
                </dt>
                <dd>
                    <input name="STAFFZIPCODE" type="text" class="form-control" required>
                </dd>
                <br>

                <dt>
                    Stad:
                </dt>
                <dd>
                    <input name="STAFFCITY" type="text" class="form-control" required>
                </dd>
                <br>

                <dt>
                    E-mail:
                </dt>
                <dd>
                    <input name="STAFFEMAIL" type="text" class="form-control" required>
                </dd>
                <br>
                <dt>
                    Geboorte datum:
                </dt>
                <dd>
                    <input name="STAFFBIRTHDATE" type="date" class="form-control" value="1990-01-01">
                </dd>
                <br>
                <dt>
                    Functie:
                </dt>
                <dd>
                <select name="STAFFFUNCTION" type="text" class="form-control" required>';
                foreach ($functions as $function) {
                    echo '<option value="' . $function["name"] . '">' . $function["name"] . '</option>';
                }
            echo '
                <br>
                <dt></dt>
                <dd>
                    <input type="checkbox" name="STAFFISACTIVE" checked>Actief?<br>
                </dd>
                <br>
                <dt>
                    Wachtwoord:
                </dt>
                <dd>
                    <input name="STAFFPASSWORD" type="password" class="form-control">
                </dd>
                <br>

                <button class="btn btn-lg btn-primary" type="submit">Toevoegen</button>
        </form>
</div>';
}