<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 24-4-2015
 * Time: 12:18
 */
include 'conn.inc.php';
include 'pageHead.php';

echo '<title>Somerleyton Animal Park</title>';

include 'home.php';
$stmt = $dbh->prepare("select * from Animal");
$stmt->execute();
while ($row = $stmt->fetch()) {
    print_r($row);
}
unset($dbh); unset($stmt);

include 'footer.php';