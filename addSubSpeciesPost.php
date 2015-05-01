<?php

include 'conn.inc.php';

$staffID = $_POST["STAFFID"];
$latinName = $_POST["LATINNAME"];
$subSpeciesName = $_POST["SUBSPECIESNAME"];
$description = $_POST["DESCRIPTION"];

$image = 'sdfsdf';


echo $staffID;
echo "<br />";
echo $latinName;
echo "<br />";
echo $subSpeciesName;
echo "<br />";
echo $description;
echo "<br />";
echo $image;

$speciesStatement = $dbh->prepare("proc_addSubSpecies ?, ?, ?, ?, ?");
$speciesStatement->bindParam(1, $staffID);
$speciesStatement->bindParam(2, $latinName);
$speciesStatement->bindParam(3, $subSpeciesName);
$speciesStatement->bindParam(4, $description);
$speciesStatement->bindParam(5, $image);
$speciesStatement->execute();


?>