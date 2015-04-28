<?php
try {
    $hostname = "95.96.69.114";
    $dbname = "SomerleytonAnimalPark";
    $username = "Eleven";
    $pw = "asdfasdf";

    $dbh = new PDO ("sqlsrv:Server=$hostname;Database=$dbname","$username","$pw");
} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
}

