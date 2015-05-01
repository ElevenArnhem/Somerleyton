<?php

$allSubSpecies = $dbh->prepare("EXEC proc_getSubSpecies");
$allSubSpecies->execute();
$subSpecies = $allSubSpecies->fetchAll();


echo '<h1>Diersoorten beheren</h1>';

echo '
<table class="table table-hover">
    <tr>
        <th>Hoofdsoort</th>
        <th>Subsoort</th>
    </tr>';
    foreach($subSpecies as $fetchSubSpecies) {
    echo '<tr>
            <td>'.$fetchSubSpecies["LatinName"].'</td>
            <td>'.$fetchSubSpecies["SubSpeciesName"].'</td>
            </tr>';
    }
    echo ' </table>';
?>

<div class="btn-group" role="group">
    <a href="?page=addSubSpecies"> <button type="button" class="btn btn-default" >Subsoort toevoegen</button></a>
</div>
