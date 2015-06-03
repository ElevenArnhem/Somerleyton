<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 13-5-2015
 * Time: 12:40
 */


echo '
<a class="btn btn-primary" href="index.php?page=feedingHistory">Voedings geschiedenis</a>
<a class="btn btn-primary" href="index.php?page=recipes">Bekijk recepten</a>
<hr>';
$linkPage = 'feedingscheme';
searchSpecies($linkPage, $dbh);
