<?php
    /**
     * Created by PhpStorm.
     * User: thom
     * Date: 30-4-2015
     * Time: 12:07
     */
    if(canRead()) {
        $animalID = $_GET['animalID'];
        $animalstmt = $dbh->prepare("proc_getAnimal ?");
        $animalstmt->bindParam(1, $animalID);
        $animalstmt->execute();
        $animal = $animalstmt->fetch();

        $gender = $animal['Gender'];
        $getChildrenstmt = $dbh->prepare("proc_getChildrenFromAnimal ?");
        $getChildrenstmt->bindParam(1, $animalID);
        $getChildrenstmt->execute();
        $children = $getChildrenstmt->fetchAll();

        echo '
        <h1>' . $animal['AnimalName'] . '</h1>
        <br>
        <div class="row">
            <div class="col-lg-6">
                <h2>Dier Info</h2>
                <dl class="dl-horizontal">
                    <dt>Geslacht</dt><dd>' . $animal['Gender'] . '</dd><br>
                    <dt>Soort</dt><dd>' . $animal['LatinName'] . '</dd><br>
                    <dt>Sub soort</dt><dd>' . $animal['SubSpeciesName'] . '</dd><br>
                    <dt>Latijnse naam</dt><dd>' . $animal['LatinName'] . '</dd><br>
                    <dt>Geboorte plaats</dt><dd>' . $animal['BirthPlace'] . '</dd><br>
                    <dt>Geboorte datum</dt><dd>' . $animal['BirthDate'] . '</dd><br>
                    <dt>Omgeving </dt><dd>' . $animal['EnvironmentName'] . '</dd><br>
                    <dt>Gebied </dt><dd>' . $animal['AreaName'] . '</dd><br>';
                    if (!isset($_POST['PRINTVERSION'])) {
                        echo '
                        <dt>Verblijf </dt><dd>' . $animal['EnclosureID'] . '</dd><br>
                        <dt>Voedingsschema </dt>
                        <dd><a role="button" href="index.php?page=feedingscheme&headspecies=' . $animal['LatinName'] . '&subspecies=' . $animal['SubSpeciesName'] . '">' . $animal['SubSpeciesName'] . '</a></dd><br>
                        <dt>Moeder </dt>
                        <dd><a role="button" href="index.php?page=animalCard&animalID=' . $animal['MotherID'] . '">' . $animal['MotherName'] . '</a></dd><br>
                        <dt>Vader </dt>
                        <dd><a role="button" href="index.php?page=animalCard&animalID=' . $animal['FatherID'] . '">' . $animal['FatherName'] . '</a></dd><br>';
                    }
                    echo '
                    <br>
                    <dt>Beschrijving </dt></dl> <br> ' . $animal['Description'] . '<br><br>';
                if (!isset($_POST['PRINTVERSION'])) {
                    echo '
                    <form action="index.php?page=animalCard&animalID=' . $animalID . '" method="post">
                        <div class="btn-group" role="group" >';
                            if(canUpdate()) {
                                echo '<a class="btn btn-default" role="button" href="?page=changeAnimal&animalID=' . $animal["AnimalID"] . '"> Aanpassen </a>';
                            }
                            echo '<button name="PRINTVERSION" type="submit" class="btn btn-default" >Print versie</button>
                        </div>
                    </form><br><br> ';
                }
                echo '
            </div>
            <div class="col-lg-6">';
                if (isset($animal['Image']) && !empty($animal['Image']) && $animal['Image'] != null) {
                    echo '
                    <img src="/pictures/' . $animal['Image'] . '" width="300" height="300"><br><br>';
                }
                echo '
            </div>
        </div>';
        if (!isset($_POST["PRINTVERSION"])) {
            echo '
            <div class="row">
                <h2>Nakomeling </h2><br>
                <table class="table table-hover">
                    <tr>
                        <th>Naam</th>
                        <th>Geslacht</th>
                        <th>Geboorte datum</th>
                        <th>';
                            if ($gender == 'M') {
                                echo 'Moeder';
                            } elseif ($gender == 'F') {
                                echo 'Vader';
                            }
                            echo '
                        </th>
                    </tr>';
                    foreach ($children as $child) {
                        echo '
                        <tr>
                            <td><a role="button" href="index.php?page=animalCard&animalID=' . $child['AnimalID'] . '">' . $child['AnimalName'] . '</a></td>
                            <td>' . $child['Gender'] . '</td>
                            <td>' . $child['BirthDate'] . '</td>
                            <td><a role="button" href="index.php?page=animalCard&animalID=' . $child['MateID'] . '">' . $child['MateName'] . '</a></td>
                        </tr>';
                    }
                    echo '
                </table>';

                $StaffID = $_SESSION["STAFFID"];
                $animalID = $_GET['animalID'];
                $getExchangeHistoryStatement = $dbh->prepare("EXEC proc_getExchangeHistory ?,?");
                $getExchangeHistoryStatement->bindParam(1, $StaffID);
                $getExchangeHistoryStatement->bindParam(2, $animalID);
                $getExchangeHistoryStatement->execute();
                $getExchangeHistorys = $getExchangeHistoryStatement->fetchAll();

                if (isset($getExchangeHistorys) && isset($getExchangeHistorys[0])) {
                    echo '
                    <br><br><h2>Uitwisselingsgeschiedenis</h2><br>
                    <table class="table table-hover" >
                        <tr>
                            <th>Datum</th>
                            <th>Terugkomst Datum</th>
                            <th>Geleend aan/van</th>
                            <th>Dierentuin</th>
                            <th>Notities</th>
                        </tr>';
                        foreach ($getExchangeHistorys as $getExchangeHistory) {
                            echo '
                            <tr>
                                <td>' . $getExchangeHistory["SendDate"] . '</td>
                                <td>' . $getExchangeHistory["ReturnDate"] . '</td>
                                <td>';
                                    if ($getExchangeHistory["ExchangeType"] == 'from') {
                                        echo "Van";
                                    } else {
                                        echo 'Naar';
                                    }
                                    echo '
                                </td>
                                <td>' . $getExchangeHistory["ZooName"] . '</td>
                                <td>' . $getExchangeHistory["Comment"] . '</td>
                            </tr>';
                        }
                        echo '
                    </table><br><br><br></div> ';
                }
            echo '</div>';
        }
    }


