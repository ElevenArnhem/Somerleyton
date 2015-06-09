<?php
    /**
     * Created by PhpStorm.
     * User: koen
     * Date: 3-6-2015
     * Time: 14:27
     */

    if(canRead() && canUpdate()) {
        if (isset($_GET["senddate"])) {
            $animalID = $_GET['animalid'];
            $animalstmt = $dbh->prepare("proc_getAnimal ?");
            $animalstmt->bindParam(1, $animalID);
            $animalstmt->execute();
            $animal = $animalstmt->fetch();

            $sendDate = $_GET["senddate"];
            $StaffID = $_SESSION["STAFFID"];

            $getExchangeHistoryStatement = $dbh->prepare("EXEC proc_getExchangeHistory ?,?,?");
            $getExchangeHistoryStatement->bindParam(1, $StaffID);
            $getExchangeHistoryStatement->bindParam(2, $animalID);
            $getExchangeHistoryStatement->bindParam(3, $sendDate);
            $getExchangeHistoryStatement->execute();
            $getExchangeHistorys = $getExchangeHistoryStatement->fetch();

            $getAllZoosStatement = $dbh->prepare("EXEC proc_getAllZoos");
            $getAllZoosStatement->execute();
            $getAllZoos = $getAllZoosStatement->fetchall();
        }

        if (isset($_POST['ReturnDate']) && isset($_POST['ZooName']) && isset($_POST['ExchangeType'])) {
            $alterEchangestmt = $dbh->prepare("EXEC proc_alterExchangeHistory ?, ?, ?, ?, ?, ?, ?");
            $alterEchangestmt->bindParam(1, $_SESSION['STAFFID']);
            $alterEchangestmt->bindParam(2, $_GET['animalID']);
            $alterEchangestmt->bindParam(3, $_GET["senddate"]);
            $alterEchangestmt->bindParam(4, $_POST["ReturnDate"]);
            $alterEchangestmt->bindParam(5, $_POST["ZooName"]);
            $alterEchangestmt->bindParam(6, $_POST["ExchangeType"]);
            $alterEchangestmt->bindParam(7, $_POST["Comment"]);

            $alterEchangestmt->execute();
            spErrorCaching($alterEchangestmt);
        }

        echo '
        <form action="?page=changeAnimal&animalID=' . $animalID, '&senddate=', $sendDate . '" method="post">
            <div class="col-lg-8">
                <h2>Uitwisseling aanpassen<h2>
                <h2>' . $animal['AnimalName'] . '</h2>
                <br><br>
                <p>Einddatum</p>
                <input name="ReturnDate" type="Date" class="form-control" Value="' . $getExchangeHistorys["ReturnDate"] . '" required>
                <br><br>
                <p>Dierentuin</p>';
                $nZoos = count($getAllZoos);
                echo '
                <select name="ZooName" type="text" class="form-control" required>';
                for ($i = 0; $i < $nZoos; $i++) {
                    echo ' <option ';
                    if ($getExchangeHistorys["ZooName"] == $getAllZoos[$i]["ZooName"]) {
                        echo 'selected';
                    }
                    echo '>';
                    echo $getAllZoos[$i]["ZooName"];
                    echo ' </option>';
                }
                echo '
                </select>
                <br><br>
                <p>Uitwisseling type</p>
                <select name="ExchangeType" type="text" class="form-control" required>
                    <option value="from" ';
                    if ($getExchangeHistorys["ExchangeType"] == 'from') {
                        echo 'selected';
                    }
                    echo
                    ' >Van</option>
                    <option value="to" ';
                        if ($getExchangeHistorys["ExchangeType"] == 'to') {
                            echo 'selected';
                        }
                    echo
                    ' >Naar</option>
                </select>
                <br><br>
                <p>Commentaar</p>
                <textarea name="Comment" class="form-control" rows="5" maxlength="250">' . $getExchangeHistorys["Comment"] . '</textarea>
                <br><br>
                <button type="submit" class="btn btn-default">Opslaan</button>
            </div>
        </form>
        ';
    }