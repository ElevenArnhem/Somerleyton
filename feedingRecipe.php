<?php

$animalID = -1;
if(isset($_POST['animalID'])) {
    $animalID = $_POST['animalID'];
}
    if(isset($_POST['submit'])){
        $staffID = $_SESSION['STAFFID'];
        $receptID = $_POST['receptID'];
        $headSpecies = $_POST["latinName"];
        $subSpecies = $_POST["subSpecies"];
        $dayGeneral = $_POST["DayGeneral"];
        $timeGeneral = $_POST["TimeGeneral"];



        $addFeedingHistoryStatement = $dbh -> prepare("proc_AddFeedingHistory ?, ?, ?, ?, ?, ?, ?");
        $addFeedingHistoryStatement -> bindParam(1, $staffID);
        $addFeedingHistoryStatement -> bindParam(2, $receptID);
        $addFeedingHistoryStatement -> bindParam(3, $animalID);
        $addFeedingHistoryStatement -> bindParam(4, $headSpecies);
        $addFeedingHistoryStatement -> bindParam(5, $subSpecies);
        $addFeedingHistoryStatement -> bindParam(6, $dayGeneral);
        $addFeedingHistoryStatement -> bindParam(7, $timeGeneral);
        $addFeedingHistoryStatement -> execute();

        spErrorCaching($addFeedingHistoryStatement);
    } elseif(isset($_POST['submitVoorbereiden'])){
        $addPreparedFeedingSchedule = $dbh -> prepare("proc_addPreparedFeedingSchedule ?, ?, ?, ?, ?, ?, ?");
        $addPreparedFeedingSchedule -> bindParam(1, $staffID);
        $addPreparedFeedingSchedule -> bindParam(2, $receptID);
        $addPreparedFeedingSchedule -> bindParam(3, $dayGeneral);
        $addPreparedFeedingSchedule -> bindParam(4, $timeGeneral);
        $addPreparedFeedingSchedule -> bindParam(5, $animalID);
        $addPreparedFeedingSchedule -> bindParam(6, $headSpecies);
        $addPreparedFeedingSchedule -> bindParam(7, $subSpecies);
        $addPreparedFeedingSchedule -> execute();
        spErrorCaching($addPreparedFeedingSchedule);
//        @StaffID      INTEGER,
// @FeedingRecipeID    INTEGER,
// @dag       VARCHAR(9),
// @tijd      VARCHAR(50),
// @AnimalID      INTEGER,
// @HeadSpecies     VARCHAR(50),
// @SubSpecies     VARCHAR(50)

    }
    if(isset( $_POST['feedingSchemeRow'])) {
        $strFeedingScheme = $_POST['feedingSchemeRow'];
        $feedingScheme = unserialize(base64_decode($strFeedingScheme));
        $receptID = $feedingScheme['FeedingRecipeID'];
        $receptDetailsStatement = $dbh -> prepare("EXEC proc_GetVoerRecept ?");
        $receptDetailsStatement -> bindParam(1, $receptID);
        $receptDetailsStatement -> execute();
        $receptDetails = $receptDetailsStatement -> fetchAll();
    }
    if(isset($_POST["latinName"]) && isset($_POST["subSpecies"]) && isset($_POST['feedingSchemeRow'])) {

        $headSpecies = $_POST["latinName"];
        $subSpecies = $_POST["subSpecies"];
        $strFeedingScheme = $_POST['feedingSchemeRow'];
        $feedingScheme = unserialize(base64_decode($strFeedingScheme));
        $receptID = $feedingScheme['FeedingRecipeID'];

        $receptDetailsStatement = $dbh -> prepare("EXEC proc_GetVoerRecept ?");
        $receptDetailsStatement -> bindParam(1, $receptID);
        $receptDetailsStatement -> execute();
        $receptDetails = $receptDetailsStatement -> fetchAll();
    }

    $animalName = "-";
    $animalID = null;

    if(isset($_POST['animalID'])){
        $animalID = $_POST['animalID'];

        $animalDetailsStatement = $dbh -> prepare("proc_getAnimal ?");
        $animalDetailsStatement -> bindParam(1, $animalID);
        $animalDetailsStatement->execute();
        $animal = $animalDetailsStatement->fetch();
        $animalName = $animal['AnimalName'];
        $headSpecies = $animal["LatinName"];
        $subSpecies = $animal["SubSpeciesName"];

    }
$dayGeneral = $feedingScheme['DayGeneral'];
$timeGeneral = explode(".", $feedingScheme['TimeGeneral'])[0];
$getPreparedstmt = $dbh->prepare("proc_getPreparedScheme ?,?,?,?,?,?");
$getPreparedstmt->bindParam(1,$receptID);
$getPreparedstmt->bindParam(2,$dayGeneral);
$getPreparedstmt->bindParam(3,$timeGeneral);
$getPreparedstmt->bindParam(4,$headSpecies);
$getPreparedstmt->bindParam(5,$subSpecies);
$getPreparedstmt->bindParam(6,$animalID);
$getPreparedstmt->execute();
$getPrepared = $getPreparedstmt->fetchAll();



?>
<div class="row">
    <div class="col-lg-6">
        <div>
            <h1>Voedingsrecept weergeven</h1>
            <table  class="table table-hover">
                <tr>
                    <td>
                        Hoofdsoort
                    </td>
                    <td>
                        <?php echo $headSpecies ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Subsoort
                    </td>
                    <td>
                        <?php echo $subSpecies ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Dier
                    </td>
                    <td>
                        <?php echo $animalName ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Recept
                    </td>
                    <td>
                        <?php echo $receptID ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Dag
                    </td>
                    <td>
                        <?php echo ucfirst($feedingScheme['DayGeneral']) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Tijd
                    </td>
                    <td>
                        <?php echo explode(".", $feedingScheme['TimeGeneral'])[0] ?>
                    </td>
                </tr>
            </table>
        </div>

        <div>
            <form action="index.php?page=feedingRecipe" method="post">
                <input type="hidden" name="receptID" value="<?php echo $receptID ?>">
                <input type="hidden" name="latinName" value="<?php echo $headSpecies ?>">
                <input type="hidden" name="subSpecies" value="<?php echo $subSpecies ?>">
                <input type="hidden" name="TimeGeneral" value="<?php echo explode(".", $feedingScheme['TimeGeneral'])[0]; ?> ">
                <input type="hidden" name="DayGeneral" value="<?php echo ucfirst($feedingScheme['DayGeneral']); ?> ">


                <?php if($animalID != null) { ?>
                    <input type="hidden" name="animalID" value="<?php echo $animalID ?>">
                <?php
                }
                ?>

                <input type="hidden" name="feedingSchemeRow" value="<?php echo $strFeedingScheme ?>">
                <?php if(isset($getPrepared) && isset($getPrepared[0]["FeedingRecipeID"])) {
                    echo '
                <button type="submit" name="submit" class="btn btn-default">Voeren</button>';
                } else {
                    echo '
                <button type="submit" name="submitVoorbereiden" class="btn btn-default">Voorbereiden</button>';
                }

                ?>
            </form>
        </div>
        <hr/>
        <div>
            <h3>Recept</h3>
            <table class="table table-hover">
                <tr>
                    <th>
                        Item
                    </th>
                    <th>
                        Hoeveelheid
                    </th>
                </tr>
                <?php

                foreach($receptDetails as $detail) {
                ?>
                <tr>
                    <td>
                       <?php echo $detail["ItemName"] ?>
                    </td>
                    <td>
                        <?php echo $detail["Amount"] . ' '. $detail["Unit"] ?>
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>

            <!--<form action="index.php?page=editFeedingRecipe" method="post">
                <input type="hidden" name="latinName" value="<?php //echo $headSpecies ?>">
                <input type="hidden" name="subSpecies" value="<?php //echo $subSpecies ?>">

                <?php// if($animalID != null) { ?>
                    <input type="hidden" name="animalID" value="<?php //echo $animalID ?>">
                <?php
                //}
                ?>

                <input type="hidden" name="feedingSchemeRow" value="<?php //echo $strFeedingScheme ?>">
                <button type="submit" name="submit" class="btn btn-default">Aanpassen</button>
            </form>-->
        </div>
    </div>
</div>