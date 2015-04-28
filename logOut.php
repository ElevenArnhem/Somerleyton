<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 28-4-2015
 * Time: 15:38
 */
session_start();
$_SESSION = array();
$_SESSION = null;

// get session parameters
$params = session_get_cookie_params();

// Delete the actual cookie.
setcookie(session_name(),
    '', time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]);

// Destroy session
session_destroy();
header('Location:/');