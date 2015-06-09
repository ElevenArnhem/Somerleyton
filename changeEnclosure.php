<?php
    if(canRead()) {
        if (isset($_POST['ADDSUBSPECIES'])) {
            $staffID = $_SESSION['STAFFID'];
            $environmentName = $_POST['ENVIRONMENT'];
            $areaName = $_POST['AREA'];
            $enclosureID = $_POST['ENCLOSURE'];
            $species = explode(' | ', $_POST['SPECIES']);
            $headSpecies = $species[0];
            $subSpecies = $species[1];

            $addSpeciesToEnclosurestmt = $dbh->prepare("EXEC proc_addSubSpeciesToEnclosure ?,?,?,?,?,?");
            $addSpeciesToEnclosurestmt->bindParam(1, $staffID);
            $addSpeciesToEnclosurestmt->bindParam(2, $headSpecies);
            $addSpeciesToEnclosurestmt->bindParam(3, $subSpecies);
            $addSpeciesToEnclosurestmt->bindParam(4, $enclosureID);
            $addSpeciesToEnclosurestmt->bindParam(5, $areaName);
            $addSpeciesToEnclosurestmt->bindParam(6, $environmentName);
            $addSpeciesToEnclosurestmt->execute();

            spErrorCaching($addSpeciesToEnclosurestmt);
        }

        if (isset($_POST['DELETE'])) {
            $staffID = $_SESSION['STAFFID'];
            $environmentName = $_POST['ENVIRONMENT'];
            $areaName = $_POST['AREA'];
            $enclosureID = $_POST['ENCLOSURE'];
            $headSpecies = $_POST['HEADSPECIES'];
            $subSpecies = $_POST['SUBSPECIES'];

            $deleteSubSpeciesFromEnclosurestmt = $dbh->prepare("EXEC proc_DeleteSpeciesFromEnclosure ?,?,?,?,?,?");
            $deleteSubSpeciesFromEnclosurestmt->bindParam(1, $staffID);
            $deleteSubSpeciesFromEnclosurestmt->bindParam(2, $headSpecies);
            $deleteSubSpeciesFromEnclosurestmt->bindParam(3, $subSpecies);
            $deleteSubSpeciesFromEnclosurestmt->bindParam(4, $enclosureID);
            $deleteSubSpeciesFromEnclosurestmt->bindParam(5, $areaName);
            $deleteSubSpeciesFromEnclosurestmt->bindParam(6, $environmentName);
            $deleteSubSpeciesFromEnclosurestmt->execute();

            spErrorCaching($deleteSubSpeciesFromEnclosurestmt);
        }

        if (isset($_POST['ENVIRONMENT']) && isset($_POST['AREA']) && isset($_POST['ENCLOSURE'])) {
            $environmentName = $_POST['ENVIRONMENT'];
            $areaName = $_POST['AREA'];
            $enclosureID = $_POST['ENCLOSURE'];

            $getSpeciesInEnclosurestmt = $dbh->prepare("EXEC proc_GetSpeciesInEnclosure ?,?,?");
            $getSpeciesInEnclosurestmt->bindParam(1, $environmentName);
            $getSpeciesInEnclosurestmt->bindParam(2, $areaName);
            $getSpeciesInEnclosurestmt->bindParam(3, $enclosureID);
            $getSpeciesInEnclosurestmt->execute();
            $speciesInEnclosure = $getSpeciesInEnclosurestmt->fetchAll();

            $getAnimalsstmt = $dbh->prepare("EXEC proc_getAnimalInEnclosure ?,?,?");
            $getAnimalsstmt->bindParam(1, $enclosureID);
            $getAnimalsstmt->bindParam(2, $areaName);
            $getAnimalsstmt->bindParam(3, $environmentName);
            $getAnimalsstmt->execute();
            $animals = $getAnimalsstmt->fetchAll();
        }

        $getSubSpeciesstmt = $dbh->prepare("EXEC proc_GetSubSpecies");
        $getSubSpeciesstmt->execute();
        $subSpecies = $getSubSpeciesstmt->fetchAll();

echo '
        <h3>Omgeving: ' . $_POST['ENVIRONMENT'] . '</h3>
        <h3>Gebied: ' . $_POST['AREA'] . '</h3>
        <h3>Verblijf: ' . $_POST['ENCLOSURE'] . '</h3>
        <hr>
        <div class="row">
            <div class="col-lg-6">
            <h3>Diersoorten</h3>
                <table class="table table-hover">
                    <tr>
                        <th>Hoofdsoort</th>
                        <th>Subsoort</th>
                        ';
                        if(canUpdate()) {
                            echo '<td></td>';
                        }
               echo '</tr>';
                    foreach ($speciesInEnclosure as $speciesInEnclosureRow) {
                        echo '<tr>
                                    <td>' . $speciesInEnclosureRow['HeadSpecies'] . '</td>
                                    <td>' . $speciesInEnclosureRow['SubSpeciesName'] . '</td>';
                                    if (canUpdate()) {
                            echo '<form action="index.php?page=changeEnclosure" method="post">
                                        <td>
                                            <input type="hidden" name="ENVIRONMENT" value="' . $environmentName . '">
                                            <input type="hidden" name="AREA" value="' . $areaName . '">
                                            <input type="hidden" name="ENCLOSURE" value="' . $enclosureID . '">
                                            <input type="hidden" name="HEADSPECIES" value="' . $speciesInEnclosureRow['HeadSpecies'] . '">
                                            <input type="hidden" name="SUBSPECIES" value="' . $speciesInEnclosureRow['SubSpeciesName'] . '">
                                            <button name="DELETE" type="submit" class="btn btn-link btn-xs" aria-label="Left Align">
                                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </form>';
                                    }
                        echo '</tr>';
                    }
        if (canUpdate()) {
            echo '   <tr>
                        <form action="index.php?page=changeEnclosure" method="post">
                            <td colspan="2">
                                <input type="hidden" name="ENVIRONMENT" value="' . $environmentName . '">
                            <input type="hidden" name="AREA" value="' . $areaName . '">
                            <input type="hidden" name="ENCLOSURE" value="' . $enclosureID . '">
                            <select name="SPECIES" type="text" class="form-control" required>';
                                foreach ($subSpecies as $subSpeciesRow) {
                                    echo '<option>' . $subSpeciesRow['LatinName'] . ' | ' . $subSpeciesRow['SubSpeciesName'] . '</option>';
                                }
            echo '          </select>
                            </td>
                            <td>
                                <button name="ADDSUBSPECIES" class="btn btn-primary" type="submit">Toevoegen</button>
                            </td>
                        </form>
                    </tr>';
        }
        echo '
                </table>
            </div>
            <div class="col-lg-6">
                <h3>Dieren</h3>
                    <table class="table table-hover">
                        <tr>
                            <th>Diernummer</th>
                            <th>Naam</th>
                            <th>Diersoort</th>
                        </tr>';
                foreach ($animals as $animal) {
                    echo '<tr>
                            <td>' . $animal['AnimalID'] . '</td>
                            <td>' . $animal['AnimalName'] . '</td>
                            <td>' . $animal['SubSpeciesName'] . '</td>';
                    echo '</tr>';
                }
            echo '</table>
            </div>
        </div>';
    }
