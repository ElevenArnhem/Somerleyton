<?php
    if(isset($_POST["latinName"]) && isset($_POST["subSpecies"]) && isset($_POST['feedingSchemeRow'])) {

        $headSpecies = $_POST["latinName"];
        $subSpecies = $_POST["subSpecies"];
        $strFeedingScheme = $_POST['feedingSchemeRow'];
        $feedingScheme = unserialize(base64_decode($strFeedingScheme));
        $receptID = $feedingScheme['FeedingRecipeID'];


        $selectedDay = ucfirst($feedingScheme['DayGeneral']);


        $animalName = "-";
        $animalID = null;

        if(isset($_POST['animalID'])){
            $animalID = $_POST['animalID'];

            $animalDetailsStatement = $dbh -> prepare("proc_getAnimal ?");
            $animalDetailsStatement -> bindParam(1, $animalID);
            $animalDetailsStatement->execute();
            $animal = $animalDetailsStatement->fetch();
            $animalName = $animal['AnimalName'];
        }

        $receptDetailsStatement = $dbh -> prepare("EXEC proc_GetVoerRecept ?");
        $receptDetailsStatement -> bindParam(1, $receptID);
        $receptDetailsStatement -> execute();
        $receptDetails = $receptDetailsStatement -> fetchAll();
    }
?>

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
                <select name="DayGeneral" class="form-control" required>
                    <option value="Maandag" <?php if($selectedDay == "Maandag") echo ' selected' ?>>Maandag</option>
                    <option value="Dinsdag" <?php if($selectedDay == "Dinsdag") echo ' selected' ?>>Dinsdag</option>
                    <option value="Woensdag" <?php if($selectedDay == "Woensdag") echo ' selected' ?>>Woensdag</option>
                    <option value="Donderdag" <?php if($selectedDay == "Donderdag") echo ' selected' ?>>Donderdag</option>
                    <option value="Vrijdag" <?php if($selectedDay == "Vrijdag") echo ' selected' ?>>Vrijdag</option>
                    <option value="Zaterdag" <?php if($selectedDay == "Zaterdag") echo ' selected' ?>>Zaterdag</option>
                    <option value="Zondag" <?php if($selectedDay == "Zondag") echo ' selected' ?>>Zondag</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                Tijd
            </td>
            <td>
                <input type="time" name="TimeGeneral" class="form-control" value="<?php echo explode(".", $feedingScheme['TimeGeneral'])[0] ?>"/>
            </td>
        </tr>
    </table>

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
</div>