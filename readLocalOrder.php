<?php
if(canRead()) {
    if ($_SESSION['FUNCTION'] == 'KantoorPersoneel') {
        $staffID = $_SESSION['STAFFID'];
        $currentWeek = getCurrentWeekNumber();
        $currentYear = getCurrentYear();
        $selectedWeek = $currentYear . '-W' . $currentWeek;
        if (isset($_POST["Year_Week"])) {
            $selectedWeek = $_POST['Year_Week'];
            $currentWeek = explode("-W", $_POST["Year_Week"])[1];
            $currentYear = explode("-W", $_POST["Year_Week"])[0];
        }
        echo '<h3>Week: ' . $currentWeek . '</h3>';
        echo '<h3>Jaar: ' . $currentYear . '</h3>';
        $readLocalOrderStatement = $dbh->prepare("EXEC proc_getLocalOrders ?, ?, ?");
        $readLocalOrderStatement->bindParam(1, $staffID);
        $readLocalOrderStatement->bindParam(2, $currentYear);
        $readLocalOrderStatement->bindParam(3, $currentWeek);
        $readLocalOrderStatement->execute();
        $readLocalOrders = $readLocalOrderStatement->fetchAll();
        echo '
        <form action="index.php?page=readLocalOrder" method="POST">
          <div class="col-lg-4">
            <br><br><input name="Year_Week" type="week" value="' . $selectedWeek . '" class="form-control" required>
            <br><br><button class="btn btn-primary" type="submit">Weergeven</button><br><br>
          </div>
        </form>
        <table class="table table-hover" >
            <tr>
                <th>ProductID</th>
                <th>Naam</th>
                <th>Aantal</th>
                <th>Eenheid</th>
            </tr>';
        foreach ($readLocalOrders as $LocalOrder) {
            echo '<tr>
                <td>' . $LocalOrder["ItemID"] . '</td>
                <td>' . $LocalOrder["ItemName"] . '</td>
                <td>' . $LocalOrder["Amount"] . '</td>
                <td>' . $LocalOrder["Unit"] . '</td>
            </tr>';
        }
        echo '</table>';
    }
}