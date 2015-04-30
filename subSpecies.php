<?php

$allSubSpecies = $dbh->prepare("EXEC proc_getSubSpecies");
$allSubSpecies->execute();
$subSpecies = $allSubSpecies->fetchAll();

$allHeadSpecies = $dbh->prepare("EXEC proc_getHeadSpecies");
$allHeadSpecies->execute();
$headSpecies = $allHeadSpecies->fetchAll();

echo '
<table class="table table-hover">
    <tr>
        <th>Subsoort</th>
    </tr>';
    foreach($subSpecies as $fetchSubSpecies) {
    echo '<tr>
            <td>'.$fetchSubSpecies["SubSpeciesName"].'</td>
            </tr>';
    }
    echo ' </table>';
?>

<div class="btn-group" role="group">
    <a href="?page=addSubSpecies"> <button type="button" class="btn btn-default" >Toevoegen</button></a>
</div>
