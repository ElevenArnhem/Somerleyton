<?php

include 'conn.inc.php';
try {

$animalstmt = $dbh->prepare('EXEC proc_addSubSpecies(?,?,?,?,?)');

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

$animalstmt->bindParam(1, $staffID, PDO::PARAM_INT);
$animalstmt->bindParam(2, $latinName, PDO::PARAM_STR,50);
$animalstmt->bindParam(3, $subSpeciesName, PDO::PARAM_STR,50);
$animalstmt->bindParam(4, $description, PDO::PARAM_STR,8000);
$animalstmt->bindParam(5, $image, PDO::PARAM_STR,50);
$animalstmt->execute();

} catch( PDOEXception $e ) {


    echo $e->getMessage(); // display error
    exit();

}

?>