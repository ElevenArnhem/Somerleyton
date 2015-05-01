<?php

include 'conn.inc.php';

$latinName = $_POST["LATINNAME"];

echo $latinName;

$animalstmt = $dbh->prepare("EXEC proc_addHeadSpecies ?");
$animalstmt->bindParam(1,$latinName);
$animalstmt->execute();

?>