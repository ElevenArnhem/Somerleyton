<?php
    if(canRead() && canCreate()) {
        $staffID = $_SESSION['STAFFID'];
        $searchCriteria = '';
        $isActive = 1;

        $leverancierstmt = $dbh->prepare("EXEC proc_searchLeveranciers ?,?,?");
        $leverancierstmt->bindParam(1, $staffID);
        $leverancierstmt->bindParam(2, $searchCriteria);
        $leverancierstmt->bindParam(3, $isActive);
        $leverancierstmt->execute();
        $leveranciers = $leverancierstmt->fetchall();

        echo '
        <hr>
        <form action="index.php?page=alterItem" method="post">
            <div class="col-lg-6">
                <input name="ItemName" type="text" class="form-control" placeholder="Productnaam" maxlength="50" required>
                <br>
                <input name="Unit" type="text" class="form-control" placeholder="Eenheid" maxlength="50" required>
                <br>
                <select name="Supplier" class="form-control" required>';
                    foreach ($leveranciers as $leverancier) {
                        echo '<option>' . $leverancier['SupplierName'] . '</option>';
                    }
                    echo '
                </select>
                <br>
                <button type="submit" class="btn btn-default" value="opslaan">Opslaan</button>
                <br><br><br>
            </div>
        </form>
        ';
    }


