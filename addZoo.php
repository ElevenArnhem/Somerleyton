<?php
    $staffID = $_SESSION['STAFFID'];

    if(isset($_POST['btnSave'])){
        $zooName = $_POST['ZooName'];
        $addZooStatement = $dbh -> prepare('proc_addZoo ?, ?');
        $addZooStatement -> bindParam(1, $staffID);
        $addZooStatement -> bindParam(2, $zooName);
        $addZooStatement -> execute();

        spErrorCaching($addZooStatement);
    }
?>

<h1>Dierentuin toevoegen</h1>
<form action="index.php?page=addZoo" method="post">
    <dt>Naam</dt>
    <dd>
        <input name="ZooName" type="text" class="form-control" maxlength="50">
    </dd>
    <button type="submit" name="btnSave" class="btn btn-default">Opslaan</button>
</form>
