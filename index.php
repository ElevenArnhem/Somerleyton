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
    $pageParts = explode('&',$page);

    if($pageParts[0] != 'addAnimal' && $pageParts[0] != 'findAnimal' && $pageParts[0] != 'animalCard' && $pageParts[0] != 'changeAnimal') {
        include 'home.php';
    } else {
        include 'navbar.php';
        include $pageParts[0] . '.php';
    }
} else {
    include 'login.php';
}

//include 'addSubSpecie.php';

include 'footer.php';