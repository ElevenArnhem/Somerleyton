<?php
try {
    $hostname = "95.96.69.114";
    $dbname = "SomerleytonAnimalPark";
    $port = "1433";
    $username = "Eleven";
    $pw = "asdfasdf";

    $dbh = new PDO ("sqlsrv:Server=$hostname,$port;Database=$dbname","$username","$pw");
} catch (PDOException $e) {

    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
//    include 'conn.inc2.php';
    exit;
}

