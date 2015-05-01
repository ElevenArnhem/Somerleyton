<?php

include 'conn.inc.php';

$latinName = $_POST["LATINNAME"];
$animalstmt = $dbh->prepare("EXEC proc_addHeadSpecies ?");
$animalstmt->bindParam(1,$latinName);
$animalstmt->execute();

header("Location: index.php?page=addSubSpecies");
exit;

?>