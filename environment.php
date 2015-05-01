<?php
/**
 * Created by PhpStorm.
 * User: dirk
 * Date: 1-5-2015
 * Time: 10:20
 */

$allEnvironments = $dbh->prepare("EXEC proc_getEnvironment");
$allEnvironments->execute();
$environments = $allEnvironments->fetchAll();

echo '

<hr>
<table class="table table-hover">
    <tr>
        <th>Environment</th>
    </tr>';
foreach($environments as $environment) {
    echo '<tr>
            <td><a href="?page=area&environmentName='.$environment["EnvironmentName"].'">'.$environment["EnvironmentName"].'</a></td>
          </tr>';
}
echo ' </table>';
?>

<div class="btn-group" role="group">
    <a href="?page=addSubSpecies"> <button type="button" class="btn btn-default" >Subsoort toevoegen</button></a>
</div>