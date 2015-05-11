<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 4-5-2015
 * Time: 00:12
 */
$environment = null;
if(isset($_POST['ENVIRONMENT'])) {
    $environment = $_POST['ENVIRONMENT'];
}
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
            $environment = $environmentName;
        }
    } elseif($_POST['TYPE'] == 'changeEnvironment'){
        if(isset($_POST['ENVIRONMENTNAME'])) {
            $staffID = $_SESSION['STAFFID'];
            $environmentName = $_POST['ENVIRONMENTNAME'];
            $oldEnvironmentName = $_POST['OLDENVIRONMENTNAME'];
            $changeEnvironment = $dbh->prepare("proc_alterEnvironment ?,?,?");
            $changeEnvironment->bindParam(1, $staffID);
            $changeEnvironment->bindParam(2, $oldEnvironmentName);
            $changeEnvironment->bindParam(3, $environmentName);
            $changeEnvironment->execute();
            spErrorCaching($changeEnvironment);
            $environment = $environmentName;
        }
    } elseif($_POST['TYPE'] == 'deleteEnvironment'){
        if(isset($_POST['OLDENVIRONMENTNAME'])) {
            $staffID = $_SESSION['STAFFID'];
            $environmentName = $_POST['ENVIRONMENTNAME'];
            $oldEnvironmentName = $_POST['OLDENVIRONMENTNAME'];
            $changeEnvironment = $dbh->prepare("proc_deleteEnvironment ?,?");
            $changeEnvironment->bindParam(1, $staffID);
            $changeEnvironment->bindParam(2, $oldEnvironmentName);
            $changeEnvironment->execute();
            spErrorCaching($changeEnvironment);
            header('index.php?page=environment');
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
<dt>Naam</dt><dd><input name="ENVIRONMENTNAME" type="text" class="form-control" value="'; if(isset($_POST['ENVIRONMENT']) || isset($_POST['ENVIRONMENTNAME'])) {echo $environment;} echo'" placeholder="omgeving naam" required></dd><br>
</dl>
<input name="OLDENVIRONMENTNAME" type="hidden"  value="'; if(isset($_POST['ENVIRONMENT']) || isset($_POST['ENVIRONMENTNAME'])) {echo $environment;} echo'" >
<button class="btn btn-primary" name="TYPE" value="'; if(isset($_POST['ENVIRONMENT'])) {echo 'changeEnvironment';} else{echo 'addEnvironment';} echo'" type="submit">Opslaan</button>
<button class="btn btn-primary" name="TYPE" value="deleteEnvironment" type="submit">Verwijder omgeving</button>
</form>
</div>';