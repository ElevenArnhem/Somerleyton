<?php
    if($_SESSION['FUNCTION'] != 'KantoorPersoneel'){
        echo '<div class="alert alert-danger" role="alert">U heeft geen rechten om deze pagina te bekijken.</div>';
    }
    else {
    $staffID = $_SESSION['STAFFID'];

    $getAllZooStatement = $dbh -> prepare('proc_getAllZoos');
    $getAllZooStatement -> execute();
    $dierentuinen = $getAllZooStatement -> fetchAll();
?>

<h1>Dierentuinen</h1>
<hr>
<table class="table table-hover">
    <tr>
        <th>Naam</th>
        <th></th>
    </tr>

    <?php foreach($dierentuinen as $dierentuin) { ?>
        <tr>
            <td><?php echo $dierentuin['ZooName'] ?></td>
            <td>
                <form action="index.php?page=alterZoo" method="post">
                    <input type="hidden" name="originalName" value="<?php echo $dierentuin['ZooName'] ?>">
                    <button type="submit" class="btn btn-default">Bewerken</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<form action="index.php?page=addZoo" method="post">
    <button type="submit" class="btn btn-default">Toevoegen</button>
</form>
<?php } ?>