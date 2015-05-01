<?php

$allSubSpecies = $dbh->prepare("EXEC proc_getSubSpecies");
$allSubSpecies->execute();
$subSpecies = $allSubSpecies->fetchAll();
$count = count($subSpecies);
if(!isset($_GET ['number'])) {
    $page = 1;
} else {
    $page = $_GET['number'];
}

$pp = 2;

$paging_info = get_paging_info($count,$pp,$page);

function get_paging_info($tot_rows,$pp,$curr_page)
{
    $pages = ceil($tot_rows / $pp); // calc pages

    $data = array(); // start out array
    $data['si']        = ($curr_page * $pp) - $pp; // what row to start at
    $data['pages']     = $pages;                   // add the pages
    $data['curr_page'] = $curr_page;               // Whats the current page

    return $data; //return the paging data

}


echo '<h1>Diersoorten beheren</h1>';

echo '
<table class="table table-hover">
    <tr>
        <th>Hoofdsoort</th>
        <th>Subsoort</th>
    </tr>';
    foreach($subSpecies as $fetchSubSpecies) {
            echo '<tr>';
            $bla = $fetchSubSpecies["LatinName"];
            echo '<td>' . $fetchSubSpecies["LatinName"] . '</td>
            <td>' . $fetchSubSpecies["SubSpeciesName"] . '</td>
            </tr>';
    }
    echo ' </table>';
?>

<p>
    <!-- If the current page is more than 1, show the First and Previous links -->
    <?php if($paging_info['curr_page'] > 1) : ?>
        <a href='index.php?page=species&number=1' title='Page 1'>Eerst</a>
        <a href='index.php?page=species&number=<?php echo $paging_info['curr_page'] - 1; ?>' title='Page <?php echo ($paging_info['curr_page'] - 1); ?>'>Vorige</a>
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

        <a href='' title='Pagina 1'>1</a>
        ..

    <?php endif; ?>

    <!-- Loop though max number of pages shown and show links either side equal to $max / 2 -->
    <?php for($i = $sp; $i <= ($sp + $max -1);$i++) : ?>

        <?php
        if($i > $paging_info['pages'])
            continue;
        ?>

        <?php if($paging_info['curr_page'] == $i) : ?>

            <span class='bold'><?php echo $i; ?></span>

        <?php else : ?>

            <a href='' title='Page <?php echo $i; ?>'><?php echo $i; ?></a>

        <?php endif; ?>

    <?php endfor; ?>


    <!-- If the current page is less than say the last page minus $max pages divided by 2-->
    <?php if($paging_info['curr_page'] < ($paging_info['pages'] - floor($max / 2))) : ?>

        ..
        <a href='' title='Page <?php echo $paging_info['pages']; ?>'><?php echo $paging_info['pages']; ?></a>

    <?php endif; ?>

    <!-- Show last two pages if we're not near them -->
    <?php if($paging_info['curr_page'] < $paging_info['pages']) : ?>

        <a href='index.php?page=species&number=<?php echo $paging_info['curr_page'] + 1; ?>' title='Page <?php echo ($paging_info['curr_page'] + 1); ?>'>Volgende</a>

        <a href='index.php?page=species&number=<?php echo $paging_info['pages']; ?>'>Laatste</a>

    <?php endif; ?>
</p>



<div class="btn-group" role="group">
    <a href="?page=addSpecies"> <button type="button" class="btn btn-default" >Diersoort toevoegen</button></a>
</div>
