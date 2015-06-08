<?php
    /**
     * Created by PhpStorm.
     * User: thom
     * Date: 29-4-2015
     * Time: 11:44
     */
    $staffID = $_SESSION['STAFFID'];
    date_default_timezone_set('Europe/Amsterdam');
    if(canRead() && canCreate()) {
        if(isset($_GET['headspecies']) && isset($_GET['subspecies']) && !empty($_GET['headspecies']) ) {
            $picaName = null;
            if (isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
                $tmpTargetFileName = '.' . explode('.', $_FILES['fileToUpload']['name'])[1];
                $picaName = $tmpTargetFileName;
            }
            if (isset($_POST['ANIMALNAME'])) {
                $staffID = $_SESSION['STAFFID'];
                $animalName = $_POST['ANIMALNAME'];
                $gender = $_POST['GENDER'];
                $environmentName = $_POST['ENVIRONMENTNAME'];
                $areaName = $_POST['AREANAME'];
                $enclosureID = $_POST['ENCLOSUREID'];
                $latinName = $_POST['LATINNAME'];
                $subSpeciesName = $_POST['SUBSPECIESNAME'];
                $birthPlace = null;
                if (isset($_POST['BIRTHPLACE']) && !empty($_POST['BIRTHPLACE'])) {
                    $birthPlace = $_POST['BIRTHPLACE'];
                }
                $birthDate = null;
                if (isset($_POST['BIRTHDATE']) && !empty($_POST['BIRTHDATE'])) {
                    $birthDate = $_POST['BIRTHDATE'];
                }
                $fatherID = null;
                if (isset($_POST['FATHER']) && !empty($_POST['FATHER'])) {
                    $fatherID = $_POST['FATHER'];
                }
                $motherID = null;
                if (isset($_POST['MOTHER']) && !empty($_POST['MOTHER'])) {
                    $motherID = $_POST['MOTHER'];

                    $addAnimalstmt = $dbh->prepare("proc_InsertAnimal ?,?,?,?,?,?,?,?,?,?,?,?,?");
                    $addAnimalstmt->bindParam(1, $staffID);
                    $addAnimalstmt->bindParam(2, $animalName);
                    $addAnimalstmt->bindParam(3, $gender);
                    $addAnimalstmt->bindParam(4, $birthDate);
                    $addAnimalstmt->bindParam(5, $birthPlace);
                    $addAnimalstmt->bindParam(6, $environmentName);
                    $addAnimalstmt->bindParam(7, $areaName);
                    $addAnimalstmt->bindParam(8, $enclosureID);
                    $addAnimalstmt->bindParam(9, $latinName);
                    $addAnimalstmt->bindParam(10, $subSpeciesName);
                    $addAnimalstmt->bindParam(11, $picaName);
                    $addAnimalstmt->bindParam(12, $fatherID);
                    $addAnimalstmt->bindParam(13, $motherID);
                    $addAnimalstmt->execute();

                    spErrorCaching($addAnimalstmt);
                    $addAnimalstmt->nextRowset();
                    $newAnimal = $addAnimalstmt->fetch();
                    $newAnimalid = $newAnimal[0];
                    if (!empty($newAnimalid)) {
                        if (isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
                            addPicture($newAnimalid);
                        }

                        echo '
                        <div class="alert alert-success" role="alert">
                            <a href="index.php?page=changeAnimal&animalID=' . $newAnimalid . '"> Klik hier om het toegevoegde dier aan te passen. </a>
                        </div>';
                    }

                }
            }
            $areastmt = $dbh->prepare("proc_getAreaName");
            $areastmt->execute();
            $areas = $areastmt->fetchAll();

            $environmontstmt = $dbh->prepare("proc_getEnvironment");
            $environmontstmt->execute();
            $environmentNames = $environmontstmt->fetchAll();

            $headSpecies = $_GET['headspecies'];
            $subSpecies = $_GET['subspecies'];
            $gender = 'F';
            $getMotherstmt = $dbh->prepare("proc_getParents ?,?,?,?");
            $getMotherstmt->bindParam(1,$staffID);
            $getMotherstmt->bindParam(2,$gender);
            $getMotherstmt->bindParam(3,$headSpecies);
            $getMotherstmt->bindParam(4,$subSpecies);
            $getMotherstmt->execute();
            $getMothers = $getMotherstmt->fetchAll();

            $gender = 'M';
            $getFatherstmt = $dbh->prepare("proc_getParents ?,?,?,?");
            $getFatherstmt->bindParam(1,$staffID);
            $getFatherstmt->bindParam(2,$gender);
            $getFatherstmt->bindParam(3,$headSpecies);
            $getFatherstmt->bindParam(4,$subSpecies);
            $getFatherstmt->execute();
            $getFathers = $getFatherstmt->fetchAll();

            echo '        <br>
            <div class="row">
                <div class="col-lg-6">
                    <h2>Dier Info</h2>
                    <form action="index.php?page=addAnimal&headspecies='.$_GET['headspecies'].'&subspecies='.$_GET['subspecies'].'" method="post" enctype="multipart/form-data">
                    <dl class="dl-horizontal">
                        <dt>Naam</dt><dd><input name="ANIMALNAME" type="text" class="form-control" placeholder="Naam" required="" maxlength="50"></dd><br>
                        <dt>Geslacht</dt>
                        <dd>
                            <select name="GENDER" type="text" class="form-control" required>
                                <option value="F">Vrouwtje</option><option value="M">Mannetje </option>
                                <option value="U">Nog niet bekend</option>
                            </select>
                        </dd><br>
                        <dt>Soort</dt>
                        <dd>
                            <input name="LATINNAME"  type="hidden" value="'.$_GET['headspecies'].'" required>'.$_GET['headspecies'].'
                        </dd><br>
                        <dt>Sub soort</dt>
                        <dd>
                            <input name="SUBSPECIESNAME" type="hidden" value="'.$_GET['subspecies'].'" class="form-control" required>'.$_GET['subspecies'].'
                        </dd><br><br>
                        <dt>Moeder </dt>
                        <dd>
                            <select name="MOTHER" type="text" class="form-control" required>';
                                foreach ($getMothers as $mother) {
                                    echo '<option value="' . $mother['AnimalID'] . '">' . $mother['AnimalName'] . '</option>';
                                }
                                echo '
                                <option selected="" value="">niet bekend</option>
                            </select>
                        </dd><br>
                        <dt>Vader </dt>
                        <dd>
                            <select name="FATHER" type="text" class="form-control" required>';
                                foreach ($getFathers as $father) {
                                    echo '<option value="' . $father['AnimalID'] . '">' . $father['AnimalName'] . '</option>';
                                }
                                echo '
                                <option selected value="">niet bekend</option>
                            </select>
                        </dd><br>
                        <dt>Geboorte plaats</dt>
                        <dd>
                            <input name="BIRTHPLACE" type="text" class="form-control" placeholder="Geboorte plaats" maxlength="50">
                        </dd><br>
                        <dt>Geboorte datum</dt>
                        <dd>
                            <input name="BIRTHDATE" type="date" class="form-control" max="' . date('Y-m-d') . '">
                        </dd><br>
                        <dt>Omgeving </dt>
                        <dd>
                            <select name="ENVIRONMENTNAME" type="text" class="form-control" required>';
                                foreach ($environmentNames as $environmentName) {
                                    echo '<option value="' . $environmentName['EnvironmentName'] . '">' . $environmentName['EnvironmentName'] . '</option>';
                                }
                                echo '
                            </select>
                        </dd><br><br>
                        <dt>Gebied </dt>
                        <dd>
                            <select name="AREANAME" type="text" class="form-control" required>';
                                foreach ($areas as $area) {
                                    echo '<option value="' . $area['AreaName'] . '">' . $area['AreaName'] . '</option>';
                                }
                                echo '
                            </select>
                        </dd><br>
                        <dt>verblijf nummer</dt>
                        <dd>
                            <input name="ENCLOSUREID" type="number" class="form-control" required placeholder="Verblijf nummer" maxlength="10" max="2147483646">
                        </dd><br><br>
                        <dt>Beschrijving </dt>
                    </dl><br>
                    <textarea name="DESCRIPTION" class="form-control" rows="5" placeholder="Beschrijving"></textarea><br><br>
                </div>
                <div class="col-lg-6">
                    <br><br>Selecteer een foto:<br><br>
                    <input class="btn btn-default"  type="file" name="fileToUpload" ><br>
                    <button class="btn btn-primary" type="submit">Toevoegen</button>
                </form>
            </div>
        </div>';
        }
        else {
            $linkPage = 'addAnimal';
            searchSpecies($linkPage, $dbh);
        }
    }


