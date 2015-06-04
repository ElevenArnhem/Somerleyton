<?php
try {
    $hostname = "eleven.tonynijenhuis.nl";
    $dbname = "Somerleyton";


    $port = "1433";

    $username = "groepEleven";
    $pw = "mQcLhPjW7Ocx";

    $dbh = new PDO ("sqlsrv:Server=$hostname,$port;Database=$dbname","$username","$pw");
} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
}

