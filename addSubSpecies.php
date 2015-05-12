<?php
$headSpeciesProc = $dbh->prepare('EXEC proc_getHeadSpecies');
$headSpeciesProc->execute();
$headSpecies = $headSpeciesProc->fetchAll();

//if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
//    $tmpTargetFileName = explode('.', $_FILES['fileToUpload']['name'])[1];
//    $image = $tmpTargetFileName;
//
//
//
//}

$picaName = null;
if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
    //$picaName = $_POST['fileName'];
    //$targetFileName = $animalID;
    $tmpTargetFileName = '.'.explode('.', $_FILES['fileToUpload']['name'])[1];
    $picaName = $tmpTargetFileName;

}
if(isset($_POST["submit"])) {
    echo $_POST["STAFFID"];
    echo $_POST["LATINNAME"];
    echo $_POST["SUBSPECIESNAME"];
    echo $_POST["DESCRIPTION"];
    $subSpeciePhotoName = $_POST["SUBSPECIESNAME"];
    $image = $subSpeciePhotoName.$picaName;
    echo $image;

    $speciesStatement = $dbh->prepare("proc_addSubSpecies ?, ?, ?, ?, ?");
    $speciesStatement->bindParam(1, $_POST["STAFFID"]);
    $speciesStatement->bindParam(2, $_POST["LATINNAME"]);
    $speciesStatement->bindParam(3, $_POST["SUBSPECIESNAME"]);
    $speciesStatement->bindParam(4, $_POST["DESCRIPTION"]);
    $speciesStatement->bindParam(5, $image);
    $speciesStatement->execute();
    spErrorCaching($speciesStatement);
    if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {
        addPicture($image);
    }

}
?>
<h1>Subsoort toevoegen</h1>

<form action="?page=addSubSpecies" method="post" enctype="multipart/form-data">
    <input type='hidden' name='STAFFID' value='<?php echo $_SESSION['STAFFID']; ?>'>
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
          <input class="btn btn-default"  type="file" name="fileToUpload" >
    </div>
    <button class="btn btn-primary" type="submit" name="submit">Toevoegen</button>
</form>
