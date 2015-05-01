<?php

include 'conn.inc.php';

$staffID = $_POST["STAFFID"];
$latinName = $_POST["LATINNAME"];
$subSpeciesName = $_POST["SUBSPECIESNAME"];
$description = $_POST["DESCRIPTION"];

echo $staffID;
echo '<br/>';
echo $latinName;
echo '<br/>';
echo $subSpeciesName;
echo '<br/>';
echo $description;

$image = 'ssdfsfd.jpg';

$animalstmt = $dbh->prepare("EXEC proc_addSubSpecies ?,?,?,?,?");
$animalstmt->bindParam(1,$staffID);
$animalstmt->bindParam(2,$latinName);
$animalstmt->bindParam(3,$subSpeciesName);
$animalstmt->bindParam(4,$description);
$animalstmt->bindParam(5,$image);
$animalstmt->execute();

?>