<?php
    if(isset( $_POST['recipeID']) && isset($_POST["latinName"]) && isset($_POST["subSpecies"])) {

        $headSpecies = $_POST["latinName"];
        $subSpecies = $_POST["subSpecies"];
        $receptID = $_POST['recipeID'];
        $animalName = "-";

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
    </table>
</div>

<div>
    DATUM 10:00 <button>Voeren</button>
</div>

<div>
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