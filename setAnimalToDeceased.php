<?php
    if(isset($_POST['btnSave'])){

        $staffID = $_SESSION['STAFFID'];
        $animalID = $_POST['animalID'];
        $deceasedDate = $_POST['deceasedDate'];
        $deceasedDescription = $_POST['deceasedDescription'];

        $setAnimalToDeceasedStatement = $dbh->prepare(" EXEC proc_setAnimalToDeceased ?, ?, ?, ?");
        $setAnimalToDeceasedStatement ->bindParam(1, $staffID);
        $setAnimalToDeceasedStatement ->bindParam(2, $animalID);
        $setAnimalToDeceasedStatement ->bindParam(3, $deceasedDate);
        $setAnimalToDeceasedStatement ->bindParam(4, $deceasedDescription);
        $setAnimalToDeceasedStatement ->execute();

        spErrorCaching($setAnimalToDeceasedStatement);
    }

    $deceasedDate = date('Y-m-d');


    if(isset($_POST['animalID'])) {
        $animalID = $_POST['animalID'];

        $animalStatement = $dbh->prepare("EXEC proc_getAnimal ?");
        $animalStatement->bindParam(1, $animalID);
        $animalStatement->execute();
        $animal = $animalStatement->fetch();


        $deceasedInfoStatement = $dbh->prepare("EXEC proc_getDeceasedInfo ?");
        $deceasedInfoStatement -> bindParam(1, $animalID);
        $deceasedInfoStatement -> execute();
        $deceasedInfo = $deceasedInfoStatement -> fetch();

        if(!empty($deceasedInfo['DeceasedDate'])){
            $deceasedDate = $deceasedInfo['DeceasedDate'];
        }
    } ?>

<form action="index.php?page=setAnimalToDeceased" method="post">
    <dl class="dl-horizontal">
        <dt>
            Nummer:
        </dt>
        <dd>
            <?php echo $animalID ?>
        </dd>
        <dt>
            Naam:
        </dt>
        <dd>
            <?php echo $animal['AnimalName'] ?>
        </dd>
        <dt>
            Overlijdensdatum:
        </dt>
        <dd>
            <input type="date" name="deceasedDate" class="form-control" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $deceasedDate ?>">
        </dd>
        <dt>
            Beschrijving:
        </dt>
        <dd>
            <textarea name="deceasedDescription" class="form-control" rows="5"><?php echo $deceasedInfo['Comment'] ?></textarea>
        </dd>
        <dt>
        </dt>
        <dd>
            <input type="hidden" name="animalID" value="<?php echo $animalID ?>">
            <button class="btn btn-lg btn-primary" type="submit" name="btnSave">Opslaan</button>
        </dd>
    </dl>
</form>