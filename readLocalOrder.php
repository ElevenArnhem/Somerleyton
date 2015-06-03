<?php
if($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
    echo '
    <form action="index.php?page=readLocalOrder" method="POST">
      <div class="col-lg-4">
        <br><br><input name="Year_Week" type="week" class="form-control" required>
        <br><br><button class="btn btn-primary" type="submit">Weergeven</button><br><br>
      </div>
    </form>
    ';

    if (isset($_POST["Year_Week"])) {
        $StaffID = $_SESSION["STAFFID"];
        $week = explode("-W", $_POST["Year_Week"])[1];
        $jaar = explode("-W", $_POST["Year_Week"])[0];

        echo '<h3>Week: '.$week.'</h3>';
        echo '<h3>Jaar: '.$jaar.'</h3>';

        $readLocalOrderStatement = $dbh->prepare("EXEC proc_getLocalOrders ?, ?,?");
        $readLocalOrderStatement->bindParam(1, $StaffID);
        $readLocalOrderStatement->bindParam(2, $jaar);
        $readLocalOrderStatement->bindParam(3, $week);
        $readLocalOrderStatement->execute();
        $readLocalOrders = $readLocalOrderStatement->fetchAll();

        echo '
        <table class="table table-hover" >
        <tr>
            <th>ProductID</th>
            <th>Naam</th>
            <th>Aantal</th>
            <th>Eenheid</th>
            <th>Hoofdverzorger</th>
        </tr>';

        foreach($readLocalOrders as $LocalOrder) {
            echo '<tr>
                <td>' . $LocalOrder["ItemID"] . '</td>
                <td>' . $LocalOrder["ItemName"] . '</td>
                <td>' . $LocalOrder["Amount"] . '</td>
                <td>' . $LocalOrder["Unit"] . '</td>
                <td>' . $LocalOrder["StaffName"] . '</td>
            </tr>';
        }
        echo '</table>';
    }
}