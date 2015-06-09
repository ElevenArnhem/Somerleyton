<?php
if(canRead()) {
    $staffID = $_SESSION["STAFFID"];

    if (isset($_POST['ADDENCLOSURE'])) {
        $environmentName = $_POST['ENVIRONMENT'];
        $areaName = $_POST['AREA'];
        $addEnclosurestmt = $dbh->prepare("proc_addEnclosure ?,?,?");
        $addEnclosurestmt->bindParam(1, $staffID);
        $addEnclosurestmt->bindParam(2, $areaName);
        $addEnclosurestmt->bindParam(3, $environmentName);
        $addEnclosurestmt->execute();
        spErrorCaching($addEnclosurestmt);
    }

    if (isset($_POST['DELETEENCLOSURE'])) {
        $environmentName = $_POST['ENVIRONMENT'];
        $enclosureID = $_POST['ENCLOSURE'];
        $areaName = $_POST['AREA'];
        $addEnclosurestmt = $dbh->prepare("proc_deleteEnclosure ?,?,?,?");
        $addEnclosurestmt->bindParam(1, $staffID);
        $addEnclosurestmt->bindParam(2, $enclosureID);
        $addEnclosurestmt->bindParam(3, $areaName);
        $addEnclosurestmt->bindParam(4, $environmentName);
        $addEnclosurestmt->execute();
        spErrorCaching($addEnclosurestmt);
    }

    $allEnvironments = $dbh->prepare("EXEC proc_GetEnvironment");
    $allEnvironments->execute();
    $environments = $allEnvironments->fetchAll();

    $selectedEnivornment = null;
    if (isset($_POST['ENVIRONMENT'])) {
        $selectedEnivornment = $_POST['ENVIRONMENT'];
    }

    $allAreas = $dbh->prepare("EXEC proc_GetAreaNameByEnvironment ?");
    $allAreas->bindParam(1, $selectedEnivornment);
    $allAreas->execute();
    $areas = $allAreas->fetchAll();

    $selectedArea = null;
    if (isset($_POST['AREA'])) {
        $selectedArea = $_POST['AREA'];
    }
    $allEnclosures = $dbh->prepare("EXEC proc_GetEnclosureByArea ?,?");
    $allEnclosures->bindParam(1, $selectedArea);
    $allEnclosures->bindParam(2, $selectedEnivornment);
    $allEnclosures->execute();
    $enclosures = $allEnclosures->fetchAll();

    echo '
<div class="row">
    <div class="col-lg-4"><table class="table table-hover">
        <tr>
            <th>Omgeving</th>
            <th></th>
        </tr>';
    foreach ($environments as $environment) {
        echo '<tr ';
        if ($selectedEnivornment == $environment['EnvironmentName']) {
            echo 'class="active" ';
        }
        echo '>
                <form action="index.php?page=environment" method="post">
                    <input type="hidden" name="ENVIRONMENT" value="' . $environment["EnvironmentName"] . '">
                    <td>
                        <button class="btn btn-link" type="submit" value="' . $environment["EnvironmentName"] . '">' . $environment["EnvironmentName"] . '</button>
                    </td>
                </form>';
        if (canUpdate()) {
            echo '<td>
                        <form action="index.php?page=addEnvironment" method="post">
                            <button class="btn btn-default" name="ENVIRONMENT" value="' . $environment["EnvironmentName"] . '" type="submit" >Omgeving aanpassen</button>
                            </form>
                    </td>';
        }
        else{
            echo '<td></td>';
        }
        echo '</tr>';
    }

    echo "</table>";
    if (canCreate()) {
        echo "<a href='?page=addEnvironment'> <button type='button' class='btn btn-default' >Omgeving toevoegen</button></a>";
    }
echo "</div>

    <div class='col-lg-4'>
        <table class='table table-hover'>
            <tr>
                <th>Gebied: " . $selectedEnivornment . "</th>
                <th></th>
                ";
                if(canUpdate()) {
                    echo '<th></th>';
                }

    echo "
            </tr>";
            foreach ($areas as $area) {
                echo '<tr ';
                if ($selectedArea == $area['AreaName']) {
                    echo 'class="active" ';
                }
                echo '>
                    <form action="index.php?page=environment"  method="post">
                        <input type="hidden" name="ENVIRONMENT" value="' . $selectedEnivornment . '">
                        <input type="hidden" name="AREA" value="' . $area["AreaName"] . '">
                        <td>
                            <button class="btn btn-link" type="submit" value="' . $area["AreaName"] . '">' . $area["AreaName"] . '</button>
                        </td>
                    </form>
                    <td>
                        <form action="index.php?page=viewAreaAndKeepers" method="post">
                            <input type="hidden" name="ENVIRONMENT" value="' . $selectedEnivornment . '">
                            <button class="btn btn-default" name="AREA" value="' . $area["AreaName"] . '" type="submit" >Verzorgers</button>
                        </form>
                    </td>
                   ';
                if (canUpdate()) {
                    echo '
                    <td>
                        <form action="index.php?page=addArea" method="post">
                            <input type="hidden" name="HEADKEEPER" value="' . $area['HeadkeeperID'] . '">
                            <input type="hidden" name="ENVIRONMENT" value="' . $selectedEnivornment . '">
                            <button class="btn btn-default" name="AREA" value="' . $area["AreaName"] . '" type="submit">Gebied aanpassen</button>
                        </form>
                    </td>';
                }
                echo '</tr>';
            }

    echo "</table>";
    if (canCreate()) {
        echo "<a href='index.php?page=addArea'>
                    <button type='button' class='btn btn-default'>Gebied toevoegen</button>
              </a>";
    }
    echo "</div>
    <div class='col-lg-4'>
        <table class='table table-hover'>
        <tr>
            <th>Verblijf: " . $selectedArea . "</th>
            <th></th>
        </tr>";
        foreach ($enclosures as $enclosure) {
            echo '<tr>
               <td>
                <form action="index.php?page=changeEnclosure" method="post">
                    <input type="hidden" name="ENVIRONMENT" value="' . $selectedEnivornment . '" >
                    <input type="hidden" name="AREA" value="' . $selectedArea . '" >
                    <button type="submit" name="ENCLOSURE" value="' . $enclosure["EnclosureID"] . '" class="btn btn-link"> ' . $enclosure["EnclosureID"] . '</button>
                 </form>
                </td> ';
        if (canDelete()) {
            echo '
                <td>
                    <form action="index.php?page=environment" method="post">
                        <input type="hidden" name="ENCLOSURE" value="' . $enclosure["EnclosureID"] . '">
                        <input type="hidden" name="ENVIRONMENT" value="' . $selectedEnivornment . '">
                        <input type="hidden" name="AREA" value="' . $selectedArea . '">
                        <input type="hidden" name="DELETEENCLOSURE" value="true">
                        <button class="btn btn-default" type="submit" >Verwijderen</button>
                    </form>
                </td>';
        }
            else{
                '<td></td>';
            }
        echo '
          </tr>';
    }
    echo '</table>';
    if (canCreate() || $_SESSION['FUNCTION'] == 'HeadKeeper') {
        if (isset($selectedEnivornment) && isset($selectedArea)) {
            echo '
        <form action="index.php?page=environment"  method="post">
            <input type="hidden" name="ENVIRONMENT" value="' . $selectedEnivornment . '">
            <input type="hidden" name="AREA" value="' . $selectedArea . '">
            <input type="hidden" name="ADDENCLOSURE" value="true"> <td>
            <button class="btn btn-default" type="submit" >Verblijf toevoegen</button>
        </form>';
        }
    }
    echo '
</div>
</div>';
}
?>
