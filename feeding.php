<?php
if(canRead()) { ?>
<a class="btn btn-primary" href = "index.php?page=feedingHistory" > Voedings geschiedenis </a >
<a class="btn btn-primary" href = "index.php?page=recipes" > Bekijk recepten </a >
<hr >
<?php
    $linkPage = 'feedingscheme';
    searchSpecies($linkPage, $dbh);
}
