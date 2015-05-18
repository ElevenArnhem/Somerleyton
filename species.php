<?php

include 'conn.inc.php';

if(!isset($_GET ['number'])) {
    $page = 1;
} else {
    $page = $_GET['number'];
}

$rowsAtPage = 8;
$rownumber = ($page - 1) * ($rowsAtPage + 1)  + 1;

$allSubSpeciesByNumber = $dbh->prepare("EXEC proc_getSubSpeciesByNumber ?, ?");
$allSubSpeciesByNumber->bindParam(1, $rownumber);
$allSubSpeciesByNumber->bindParam(2, $rowsAtPage);
$allSubSpeciesByNumber->execute();
$subSpeciesByNumber = $allSubSpeciesByNumber->fetchAll();

$allSubSpecies = $dbh->prepare("EXEC proc_getSubSpecies");
$allSubSpecies->execute();
$subSpecies = $allSubSpecies->fetchAll();
$count = count($subSpecies);

$allHeadSpecies = $dbh->prepare("EXEC proc_getHeadSpecies");
$allHeadSpecies->execute();
$species = $allHeadSpecies->fetchAll();

$paging_info = get_paging_info($count,$rowsAtPage,$page);

function get_paging_info($tot_rows,$rowsAtPage,$curr_page)
{
    $pages = ceil($tot_rows / ($rowsAtPage + 1)); // calc pages

    $data = array(); // start out array
    $data['si']        = ($curr_page * ($rowsAtPage + 1) ) - ($rowsAtPage + 1); // what row to start at
    $data['pages']     = $pages;                   // add the pages
    $data['curr_page'] = $curr_page;               // Whats the current page

    return $data; //return the paging data

}
?>


<h1>Hoofdsoorten beheren</h1>
<br>
<table class="table table-hover">
    <tr>
        <th>Hoofdsoort</th>
        <th></th>
    </tr>
    <?php
    foreach($species as $fetchHeadSpecies) {
        echo '<tr>
            <td>
                <label> '. $fetchHeadSpecies["LatinName"] . '</label>
            </td>
            <td>
                <form action="?page=changeHeadSpecies" method="post">
                    <button type="submit" class="btn btn-default" name="headSpecies" value="'. $fetchHeadSpecies["LatinName"] . '">Aanpassen</button>
                </form>
            </td>
        </tr>';
    }
    ?>
</table>
<div class="btn-group" role="group">
    <a href="?page=addHeadSpecies"> <button type="button" class="btn btn-default" >Hoofdsoort toevoegen</button></a>
</div>

<hr>
<h1>Subsoorten beheren</h1>
<br>
<table class="table table-hover">
    <tr>
        <th>Hoofdsoort</th>
        <th>Subsoort</th>
        <th></th>
    </tr>
    <?php
    foreach($subSpeciesByNumber as $fetchSubSpecies) {
            echo '<tr>
            <td>' . $fetchSubSpecies["LatinName"] . '</td>
            <td>' . $fetchSubSpecies["SubSpeciesName"] . '</td>
            <td>
                <form action="?page=changeSubSpecies" method="post">
                    <input type="hidden" name="headSpecies" value="'. $fetchSubSpecies["LatinName"] . '" />
                    <button type="submit" class="btn btn-default" name="subSpecies" value="'. $fetchSubSpecies["SubSpeciesName"] . '">Aanpassen</button>
                </form>
            </td>
            </tr>';
    }
    ?>
</table>


<ul class="pagination">
    <!-- If the current page is more than 1, show the First and Previous links -->
    <?php if($paging_info['curr_page'] > 1) : ?>
        <li><a href='?page=species&number=1' title='Page 1'>Eerst</a></li>
        <li><a href='?page=species&number=<?php echo $paging_info['curr_page'] - 1; ?>' title='Page <?php echo ($paging_info['curr_page'] - 1); ?>'>Vorige</a></li>
    <?php endif; ?>

    <?php
    //setup starting point

    //$max is equal to number of links shown
    $max = 7;
    if($paging_info['curr_page'] < $max)
        $sp = 1;
    elseif($paging_info['curr_page'] >= ($paging_info['pages'] - floor($max / 2)) )
        $sp = $paging_info['pages'] - $max + 1;
    elseif($paging_info['curr_page'] >= $max)
        $sp = $paging_info['curr_page']  - floor($max/2);
    ?>

    <!-- If the current page >= $max then show link to 1st page -->
    <?php if($paging_info['curr_page'] >= $max) : ?>
        <li><a href='?page=species&number=1' title='Pagina 1'>1</a></li>
        ..
    <?php endif; ?>

    <!-- Loop though max number of pages shown and show links either side equal to $max / 2 -->
    <?php for($i = $sp; $i <= ($sp + $max -1);$i++) : ?>

        <?php
        if($i > $paging_info['pages'])
            continue;
        ?>

        <?php if($paging_info['curr_page'] == $i) : ?>
            <li><a href='?page=species&number=<?php echo $i; ?>'><?php echo $i; ?></a></li>
        <?php else : ?>
        <li><a href='?page=species&number=<?php echo $i; ?>' title='Page <?php echo $i; ?>'><?php echo $i; ?></a></li>
        <?php endif; ?>
    <?php endfor; ?>


    <!-- If the current page is less than say the last page minus $max pages divided by 2-->
    <?php if($paging_info['curr_page'] < ($paging_info['pages'] - floor($max / 2))) : ?>
        ..
        <li><a href='?page=species&number=<?php echo $paging_info['pages']; ?>' title='Page <?php echo $paging_info['pages']; ?>'><?php echo $paging_info['pages']; ?></a></li>
    <?php endif; ?>

    <!-- Show last two pages if we're not near them -->
    <?php if($paging_info['curr_page'] < $paging_info['pages']) : ?>
        <li><a href='?page=species&number=<?php echo $paging_info['curr_page'] + 1; ?>' title='Page <?php echo ($paging_info['curr_page'] + 1); ?>'>Volgende</a></li>
        <li><a href='?page=species&number=<?php echo $paging_info['pages']; ?>'>Laatste</a></li>
    <?php endif; ?>
    </ul>
<br>
<br>
<div class="btn-group" role="group">
    <a href="?page=addSubSpecies"> <button type="button" class="btn btn-default" >Subsoort toevoegen</button></a>
</div>