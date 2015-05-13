<?php
if(isset($_POST["submit"])) {

    $staffID = $_SESSION['STAFFID'];
    $headSpeciesName = $_POST['headSpecies'];
    $subSpeciesName = $_POST["subSpecies"];
	$description =  $_POST["description"];
	$imageName = null;

    if(isset($_POST['fileName'])) {
        $imageName = $_POST['fileName'];
    }

    if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])) {

        $target_dir = isLocal()."/pictures/";

        $targetFileName = $subSpeciesName . $headSpeciesName;
        $tmpTargetFileName = $targetFileName.'.'.explode('.', $_FILES['fileToUpload']['name'])[1];
        $targetFileName = $tmpTargetFileName;
        $target_file = $target_dir . basename($targetFileName);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {

            $check = getimagesize($_FILES["fileToUpload"].$targetFileName);
            if ($check !== false) {

                $uploadOk = 1;
            } else {

                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            unlink('pictures/'.$targetFileName);
//            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo '<div class="alert alert-danger" role="alert">Sorry, het bestand is te groot.</div>';
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo '<div class="alert alert-danger" role="alert">Sorry, alleen JPG, JPEG, PNG & GIF bestanden zijn toegestaan.</div>';
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo '<div class="alert alert-danger" role="alert">Sorry, er iets mis gegaan tijden het uploaden.</div>';
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                $imageName = $targetFileName;
            } else {
                echo '<div class="alert alert-danger" role="alert">Sorry, er iets mis gegaan tijden het uploaden.</div>';
            }
        }
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
    <br><br>
    <label>Afbeelding</label>
       <?php
        if(isset($subSpecies["image"])) {
        echo '
        <img src="..Somerleyton/pictures/'. $subSpecies["image"].'" width="300" height="300"><br><br>';
        }
        echo'
        Selecteer een foto:<br><br>
        <input type="hidden" name="fileName">
        <input class="btn btn-default"  type="file" name="fileToUpload" >
        <br>';
        ?>
    </div>
    </div>
    <button class="btn btn-primary" type="submit" name="submit">Opslaan</button>
</form>