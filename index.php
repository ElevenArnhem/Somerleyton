<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 24-4-2015
 * Time: 12:18*/
 include 'conn.inc.php';
include 'pageHead.php';
session_start();
if(empty($_SESSION['STAFFNAME'])) {
    $_SESSION['STAFFNAME'] = null;
}
if( $_SESSION['STAFFNAME'] != null) {
    include 'container.php';
} else {
    include 'login.php';
}
//include 'container.php';
//$stmt = $dbh->prepare("select * from Animal");
//$stmt->execute();
//while ($row = $stmt->fetch()) {
//    print_r($row);
//}
//unset($dbh); unset($stmt);

include 'footer.php';