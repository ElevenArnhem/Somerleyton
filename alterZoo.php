<?php
if(canRead() && canUpdate()) {
    $staffID = $_SESSION['STAFFID'];
    $originalName = '';

    if(isset($_POST['originalName']))
        $originalName = $_POST['originalName'];

    if(isset($_POST['btnSave'])){
        $newZooName = $_POST['ZooName'];

        $alterZooStatement = $dbh -> prepare('proc_alterZoo ?, ?, ?');
        $alterZooStatement -> bindParam(1, $staffID);
        $alterZooStatement -> bindParam(2, $originalName);
        $alterZooStatement -> bindParam(3, $newZooName);
        $alterZooStatement -> execute();
        spErrorCaching($alterZooStatement);
        $originalName = $newZooName;
    }
    ?>

    <h1>Dierentuin aanpassen</h1>
    <form action="index.php?page=alterZoo" method="post">
        <dt>Naam</dt>
        <dd>
            <input type="hidden" name="originalName" value="<?php echo $originalName ?>">
            <input name="ZooName" type="text" class="form-control" value="<?php echo $originalName ?>" maxlength="50">
        </dd>
        <button type="submit" name="btnSave" class="btn btn-default">Opslaan</button>
    </form>
<?php } ?>