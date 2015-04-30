<?php

$allHeadSpecies = $dbh->prepare("EXEC proc_getHeadSpecies");
$allHeadSpecies->execute();
$headSpecies = $allHeadSpecies->fetchAll();


echo '<h1>Diersoorten beheren</h1>';

echo '
<table class="table table-hover">
    <tr>
        <th>Hoofdsoort</th>
    </tr>';
    foreach($headSpecies as $fetchHeadSpecies) {
    echo '<tr>
            <td>'.$fetchHeadSpecies["LatinName"].'</td>
            </tr>';
    }
    echo ' </table>';
?>

<div class="btn-group" role="group">
    <a href="?page=addSubSpecies"> <button type="button" class="btn btn-default" >Subsoort toevoegen</button></a>
</div>
