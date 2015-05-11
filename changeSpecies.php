<?php

$DIERSOORT = null;
if(isset($_POST['DIERSOORT'])) {
    $environment = $_POST['DIERSOORT'];
}

$subSpecies = $_GET['SubSpecies'];
$subSpeciesstmt = $dbh->prepare("proc_getSubSpecies ?");
$subSpeciesstmt->bindParam(1, $subSpecies);
$subSpeciesstmt->execute();
$subSpecies = $subSpeciesstmt->fetch();

    foreach ($subSpecies as $subSpecie) {
        echo '<option value="' . $subSpecie["LatinName"] . '">' . $subSpecie["LatinName"] . '</option>';
    }
?>