<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 00:12
 */

if(isset($_POST['ENVIRONMENTNAME'])) {
    $staffID = $_SESSION['STAFFID'];
    $environmentName = $_POST['ENVIRONMENTNAME'];
    $addEnvironment = $dbh->prepare("proc_addEnvironment ?,?");
    $addEnvironment->bindParam(1, $staffID);
    $addEnvironment->bindParam(2, $environmentName);
    $addEnvironment->execute();
    spErrorCaching($addEnvironment);
}

echo '<div class="col-lg-4">

<h1>Omgeving toevoegen</h1>
<form action="index.php?page=addEnvironment" method="post">
 <dl class="dl-horizontal">
<dt>Naam</dt><dd><input name="ENVIRONMENTNAME" type="text" class="form-control" value="" placeholder="gebied naam" required></dd><br>
</dl>
<button class="btn btn-primary" type="submit">Toevoegen</button>
</form>
</div>';