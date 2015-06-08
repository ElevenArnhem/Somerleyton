<?php

    /**
     * Created by PhpStorm.
     * User: thom
     * Date: 4-6-2015
     * Time: 10:53
     */


    $animalID = $_GET['animalid'];
    $animalstmt = $dbh->prepare("proc_getAnimal ?");
    $animalstmt->bindParam(1, $animalID);
    $animalstmt->execute();
    $animal = $animalstmt->fetch();

    $StaffID = $_SESSION["STAFFID"];


    $getAllZoosStatement = $dbh->prepare("EXEC proc_getAllZoos");
    $getAllZoosStatement->execute();
    $getAllZoos = $getAllZoosStatement->fetchall();




    echo '<form action="?page=changeAnimal&animalID='.$animalID.'" method="post">
          <div class="col-lg-8">
              <h2>Uitwisseling toevoegen<h2>
              <h2>'.$animal['AnimalName'].'</h2>
              <br><br>
              <p>Start datum</p>
              <input name="senddate" type="Date" class="form-control" required>
              <br><br>
              <p>Dierentuin</p>';

    echo'<select name="ZooName" type="text" class="form-control" required>';

    foreach($getAllZoos as $zoo) {
        echo ' <option>';  echo $zoo["ZooName"]; echo' </option>';
    }
    echo '</select>
              <br><br>
              <p>Uitwisseling type</p>
              <select name="ExchangeType" type="text" class="form-control" required>
                    <option value="from" >Van</option>
                    <option value="to">Naar</option>
              </select>
              <br><br>
              <p>Commentaar</p>
              <textarea name="Comment" class="form-control" rows="5" maxlength="250" placeholder="Notities"></textarea>
              <br><br>



              <button type="submit" class="btn btn-default">Opslaan</button>
            </div>
          </form>
    ';