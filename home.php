<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 24-4-2015
 * Time: 12:19
 */

echo '<div class="row">';
if($_SESSION['FUNCTION'] == 'HeadKeeper') {
    include 'headKeeperHome.php';
} elseif($_SESSION['FUNCTION'] == 'Keeper') {
    include 'keeperHome.php';
} elseif($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
    include 'OfficeStaff.php';
}




