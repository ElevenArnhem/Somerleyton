<?php
    if(canRead() && canUpdate()) {
        if (isset($_POST["submit"])) {
            $staffID = $_SESSION['STAFFID'];
            $headSpeciesName = $_POST['headSpecies'];
            $subSpeciesName = $_POST["subSpecies"];
            $description = $_POST["description"];
            $imageName = $_POST["IMAGE"];

            if (isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
                $imageName = $subSpeciesName . $headSpeciesName;
                $newFileName = addSpeciesPicture($imageName);
                $imageName = $newFileName;
            }

            $speciesStatement = $dbh->prepare("proc_alterSubSpecies ?, ?, ?, ?, ?");
            $speciesStatement->bindParam(1, $staffID);
            $speciesStatement->bindParam(2, $headSpeciesName);
            $speciesStatement->bindParam(3, $subSpeciesName);
            $speciesStatement->bindParam(4, $description);
            $speciesStatement->bindParam(5, $imageName);
            $speciesStatement->execute();

            spErrorCaching($speciesStatement);
        }

        $headSpeciesName = $_POST['headSpecies'];
        $subSpeciesName = $_POST['subSpecies'];

        $subSpeciesProc = $dbh->prepare("EXEC proc_GetSpecificSubSpecies ?, ?");
        $subSpeciesProc->bindParam(1, $headSpeciesName);
        $subSpeciesProc->bindParam(2, $subSpeciesName);
        $subSpeciesProc->execute();

        $subSpecies = $subSpeciesProc->fetch();
?>
<h1>Subsoort aanpassen</h1>

<div class="col-lg-6">
    <form action="?page=changeSubSpecies" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Hoofdsoort</label>
                   <input class="form-control textbox" name="headSpecies" value="<?php echo $subSpecies["LatinName"]; ?>" readonly />
        </div>
        <div class="form-group">
            <label>Subsoort</label>
            <input class="form-control textbox" placeholder="Naam Subsoort" name="subSpecies" type="text" value="<?php echo $subSpecies["SubSpeciesName"]; ?>" readonly />
        </div>
        <div class="form-group">
            <label>Beschrijving</label>
            <textarea class="form-control textbox" rows="10" cols="50" name="description" placeholder="Beschrijving" required ><?php echo $subSpecies["Description"]; ?></textarea>
        </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <br>
                <br>
                <input type="hidden" name="IMAGE" value="<?php echo $subSpecies["Image"] ?>">
                   <?php
                    if(isset($subSpecies["Image"])) {
                    echo '
                        <img src="'. isLocal() .'/pictures/'. $subSpecies["Image"].'" width="300" height="300"><br><br>';
                    }
            echo 'Selecteer een foto:
                    <br>
                    <br>
                    <input class="btn btn-default" type="file" name="fileToUpload" >
                    <br>';
                    ?>
            </div>
        </div>
        <button class="btn btn-primary" type="submit" name="submit">Opslaan</button>
    </form>
<?php } ?>