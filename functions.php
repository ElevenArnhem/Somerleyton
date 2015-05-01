<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 1-5-2015
 * Time: 11:14
 */

function spErrorCaching($stmt) {
    $explodedStr = explode(']',$stmt->errorInfo()[2]);
    $errorMessage = end($explodedStr);

    if($errorMessage != '')
        echo '<div class="alert alert-danger" role="alert">'.$errorMessage.'</div>';
    else
        echo '<div class="alert alert-success" role="alert">Geslaagd</div>';
}