<?php
if(isset($_POST["SEARCHCRITERIA"])) {
    $searchCriteria = $_POST["SEARCHCRITERIA"];
    $isActive = $_POST["SEARCHISACTIVE"];
    $medewerkerStatement = $dbh->prepare("EXEC proc_searchStaffMembers ?,?");
    $medewerkerStatement->bindParam(1, $searchCriteria);
    $medewerkerStatement->bindParam(2, $isActive);
    $medewerkerStatement->execute();
    $medewerkers = $medewerkerStatement->fetchAll();
}

if(!isset($_POST["SEARCHCRITERIA"])) {
    $isActive = '1';
    $medewerkerStatement = $dbh->prepare("EXEC proc_getStaffMembers ?");
    $medewerkerStatement->bindParam(1, $isActive);
    $medewerkerStatement->execute();
    $medewerkers = $medewerkerStatement->fetchAll();
}

echo '
<hr>
    <form action="index.php?page=medewerkers" method="post">
      <div class="col-lg-6">
        <div class="input-group">
          <input name="SEARCHCRITERIA" type="text" class="form-control" placeholder="Zoek medewerkers op: id, naam of functie">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Zoek</button>
          </span>
        </div>
      </div>
    <br /><br />
    <div class="radio">
      <label>
        <input type="radio" name="SEARCHISACTIVE" id="1" value="1" checked>
            Actieve medewerkers
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="SEARCHISACTIVE" id="0" value="0">
                Niet actieve medewerkers
        </label>
    </div>
      <br><br>
    </form>';
echo '
    <table class="table table-hover">
        <tr>
            <th>Nummer</th>
            <th>Naam</th>
            <th>Functie</th>
            <th></th>
        </tr>';

        foreach($medewerkers as $medewerker) {
            echo '<tr>
                    <td>'.$medewerker["StaffID"].'</td>
                    <td>'.$medewerker["StaffName"].'</td>
                    <td>'.$medewerker["Function"].'</td>';

        if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
            echo '<td>
                    <a href="?page=alterStaff&staffID='.$medewerker["StaffID"].'">
                        <button type="button" class="btn btn-default">Aanpassen</button>
                    </a>
                  </td>';
        }
            else{
                echo '<td></td>';
            }
echo'</tr>';
}
echo '
</table>';

if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
    echo '<div class="btn-group" role="group">
            <a href="index.php?page=addStaff"><button type="button" class="btn btn-default">Toevoegen</button></a>
          </div>';
}
