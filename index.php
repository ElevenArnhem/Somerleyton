<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 24-4-2015
 * Time: 12:18*/
 include 'conn.inc.php';
include 'pageHead.php';

if(!isset($_GET ['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
session_start();
if(empty($_SESSION['STAFFNAME'])) {
    $_SESSION['STAFFNAME'] = null;
}
if( $_SESSION['STAFFNAME'] != null) {
    include "navbar.php";
    echo '
    <br><br><br>
    <div class="container">';
    if($page != 'addAnimal' && $page != 'findAnimal') {
        include 'container.php';
    } else {
        include 'navbar.php';
        include $page . '.php';
    }
} else {
    include 'login.php';
}


include 'footer.php';