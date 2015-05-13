<?php
$headSpeciesProc = $dbh->prepare('EXEC proc_getHeadSpecies');
$headSpeciesProc->execute();
$headSpecies = $headSpeciesProc->fetchAll();

if(isset($_POST["submit"])) {

    $description = $_POST["DESCRIPTION"];
    $subspecies = $_POST["SUBSPECIESNAME"];
    $headSpecies = $_POST["LATINNAME"];
    $staffID = $_SESSION["STAFFID"];
    $imageName = null;

    if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
        $imageName = $subspecies . $headSpecies;
        $newFileName = addSpeciesPicture($imageName);
        $imageName = $newFileName;
    }

    $speciesStatement = $dbh->prepare("proc_addSubSpecies ?, ?, ?, ?, ?");
    $speciesStatement->bindParam(1, $staffID);
    $speciesStatement->bindParam(2, $headSpecies);
    $speciesStatement->bindParam(3, $subspecies);
    $speciesStatement->bindParam(4, $description);
    $speciesStatement->bindParam(5, $imageName);
    $speciesStatement->execute();
    spErrorCaching($speciesStatement);
}
?>
<h1>Subsoort toevoegen</h1>

<form action="?page=addSubSpecies" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Hoofdsoort</label>
            <select name="LATINNAME" id="selectbox" class="form-control" type="text" placeholder="Hoofdsoort" required>
            <?php
                foreach ($headSpecies as $fetchHeadSpecies) {
                    echo '<option value="'.$fetchHeadSpecies["LatinName"].'">'.$fetchHeadSpecies["LatinName"].'</option>';
                }
            ?>
            </select>
        </div>
    <div class="form-group">
        <label>Subsoort</label>
        <input class="form-control textbox" name="SUBSPECIESNAME" placeholder="Naam Subsoort" type="text" required />
    </div>
    <div class="form-group">
            <label>Beschrijving</label>
            <textarea class="form-control textbox" rows="4" cols="50" name="DESCRIPTION" placeholder="Beschrijving"  required ></textarea>
    </div>
    <div class="form-group">
          <label>Selecteer een foto:</label>
          <input class="btn btn-default" type="file" name="fileToUpload" >
    </div>
    <button class="btn btn-primary" type="submit" name="submit">Toevoegen</button>
</form>
