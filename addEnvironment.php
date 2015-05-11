<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 00:12
 */

if(isset($_POST['TYPE'])) {
    if($_POST['TYPE'] == 'addEnvironment'){
        if(isset($_POST['ENVIRONMENTNAME'])) {
            $staffID = $_SESSION['STAFFID'];
            $environmentName = $_POST['ENVIRONMENTNAME'];
            $addEnvironment = $dbh->prepare("proc_addEnvironment ?,?");
            $addEnvironment->bindParam(1, $staffID);
            $addEnvironment->bindParam(2, $environmentName);
            $addEnvironment->execute();
            spErrorCaching($addEnvironment);
        }
    } elseif($_POST['TYPE'] == 'changeEnvironment'){
        if(isset($_POST['ENVIRONMENTNAME'])) {
            $staffID = $_SESSION['STAFFID'];
            $environmentName = $_POST['ENVIRONMENTNAME'];
            $oldEnvironmentName = $_POST['OLDENVIRONMENTNAME'];
            $addEnvironment = $dbh->prepare("proc_alterEnvironment ?,?,?");
            $addEnvironment->bindParam(1, $staffID);
            $addEnvironment->bindParam(2, $oldEnvironmentName);
            $addEnvironment->bindParam(3, $environmentName);
            $addEnvironment->execute();
            spErrorCaching($addEnvironment);
        }
    }
}
if(isset($_POST['CHANGEENVIRONMENTNAME'])) {
//    @staffID INT,
//	@originalName VARCHAR(50),
//	@environmentName VARCHAR(50)
}
if(isset($_POST['DELETEENVIRONMENTNAME'])) {}



echo '<div class="col-lg-4">

<h1>Omgeving toevoegen</h1>
<form action="index.php?page=addEnvironment" method="post">
 <dl class="dl-horizontal">
<dt>Naam</dt><dd><input name="ENVIRONMENTNAME" type="text" class="form-control" value="'; if(isset($_POST['ENVIRONMENT'])) {echo $_POST['ENVIRONMENT'];} echo'" placeholder="gebied naam" required></dd><br>
</dl>
<input name="OLDENVIRONMENTNAME" type="hidden"  value="'; if(isset($_POST['ENVIRONMENT'])) {echo $_POST['ENVIRONMENT'];} echo'" >
<button class="btn btn-primary" name="TYPE" value="'; if(isset($_POST['ENVIRONMENT'])) {echo 'changeEnvironment';} else{echo 'addEnvironment';} echo'" type="submit">Opslaan</button>
</form>
</div>';