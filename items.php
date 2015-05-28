<?php

$staffID = $_SESSION['STAFFID'];
$searchCriteria = '';
if(isset($_POST["SEARCHCRITERIA"])) {
    $searchCriteria = $_POST["SEARCHCRITERIA"];
}

$itemstmt = $dbh->prepare("EXEC proc_searchItems ?, ?");
$itemstmt->bindParam(1, $staffID);
$itemstmt->bindParam(2, $searchCriteria);

$itemstmt->execute();
$items = $itemstmt->fetchAll();

if($_SESSION['FUNCTION'] != 'KantoorPersoneel' && $_SESSION['FUNCTION'] != 'HeadKeeper') {
    spErrorCaching($leverancierstmt);
}
if($_SESSION['FUNCTION'] == 'KantoorPersoneel' || $_SESSION['FUNCTION'] != 'HeadKeeper') {
    echo '
<hr>
<form action="index.php?page=items" method="post">
      <div class="col-lg-6">
        <div class="input-group">
          <input name="SEARCHCRITERIA" type="text" class="form-control" placeholder="Zoek producten op ID, naam of eenheid: ">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Zoek</button>
          </span>
        </div>
      </div>
</form>
<br><br>
<table class="table table-hover" >
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Eenheid</th>
            <th>Voorraad</th>
            <th></th>
        </tr>';
    foreach($items as $item) {
        echo '<tr>
          <td>' . $item["ItemID"] . '</td>
          <td>' . $item["ItemName"] . '</td>
          <td>' . $item["Unit"] . '</td>
          <td>' . $item["Stock"] . '</td>';

        if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
            echo '<td>
                    <a href="?page=alterItem='.$item["ItemID"].'">
                        <button type="button" class="btn btn-default">Aanpassen</button>
                    </a>
                  </td>';
        }echo '</tr>';
    }echo '</table>';

    echo '<div class="btn-group" role="group">
            <a href="index.php?page=addItem"><button type="button" class="btn btn-default">Toevoegen</button></a>
          </div>';
}