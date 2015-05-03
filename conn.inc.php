<?php
try {
    $hostname = "95.96.69.114";
    $dbname = "SomerleytonAnimalPark";
<<<<<<< HEAD
=======
    $port = "1433";
>>>>>>> ee5bb542702ed20020bee8d85edcc5f3fa804caa
    $username = "Eleven";
    $pw = "asdfasdf";

    $dbh = new PDO ("sqlsrv:Server=$hostname,$port;Database=$dbname","$username","$pw");
} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
}

