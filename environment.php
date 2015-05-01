<?php
/**
 * Created by PhpStorm.
 * User: dirk
 * Date: 1-5-2015
 * Time: 10:20
 */

$allEnclosures = $dbh->prepare("EXEC proc_getEnclosure");
$allEnclosures->execute();
$enclosures = $allEnclosures->fetchAll();

echo '

<hr>
<table class="table table-hover">
    <tr>
        <th>Environment</th>
        <th>Area</th>
        <th>Environment</th>
    </tr>';
foreach($enclosures as $enclosure) {
    echo '<tr>
            <td>'.$enclosure["EnvironmentName"].'</td>
            <td>'.$enclosure["AreaName"].'</td>
            <td>'.$enclosure["EnclosureID"].'</td>
          </tr>';
}
echo ' </table>';
?>

<div class="btn-group" role="group">
    <a href="?page=addEnvironment"> <button type="button" class="btn btn-default" >Environment toevoegen</button></a>
    <a href="?page=addArea"> <button type="button" class="btn btn-default" >Area toevoegen</button></a>
    <button type="button" class="btn btn-default" >Enclosure toevoegen</button>
</div>