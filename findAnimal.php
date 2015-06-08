<?php

/**
 * Deze pagina toont een lijst met dieren waarbij standaard alleen de levende dieren worden getoond.
 * Daarnaast kan er gezocht worden en is er voor een headkeeper de mogelijkheid om dieren aan te passen en toe te voegen
 */

    if(canRead()) {
        if (isset($_POST["SEARCHSTRING"])) {
            $searchString = $_POST["SEARCHSTRING"];
            $aliveAnimal = $_POST["ALIVEANIMAL"];
            $animalstmt = $dbh->prepare("EXEC proc_searchAnimal ?,?");
            $animalstmt->bindParam(1, $searchString);
            $animalstmt->bindParam(2, $aliveAnimal);
            $animalstmt->execute();
            $animals = $animalstmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if (!isset($_POST["SEARCHSTRING"])) {
            $animalstmt = $dbh->prepare("EXEC proc_searchAnimal '','1'");
            $animalstmt->execute();
            $animals = $animalstmt->fetchAll();
        }

        if (canCreate()) {
            echo '
            <div class="btn-group" role="group">
               <a href="?page=addAnimal"> <button type="button" class="btn btn-default" >Dier toevoegen</button></a>
            </div>';
        }

        echo '
        <hr>
        <form action="index.php?page=findAnimal" method="post">
            <div class="col-lg-6">
                <div class="input-group">
                    <input name="SEARCHSTRING" type="text" class="form-control" placeholder="Zoek dieren op: id, naam, soort, verblijf">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" >Zoek</button>
                    </span>
                </div>
            </div>
            <br /><br />
            <div class="radio">';
                if (isset($_POST['ALIVEANIMAL']) && $_POST['ALIVEANIMAL'] == 0) {
                    echo '
                    <label>
                        <input  type="radio" name="ALIVEANIMAL" id="optionsRadios1" value="1" >
                        Levende dieren
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="ALIVEANIMAL" id="0" value="0" checked>
                        Overleden dieren
                    </label>';
                } else {
                    echo '
                    <label>
                        <input  type="radio" name="ALIVEANIMAL" id="optionsRadios1" value="1" checked>
                        Levende dieren
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="ALIVEANIMAL" id="0" value="0">
                        Overleden dieren
                    </label>';
                }
                echo '
            </div>
            <br><br>
        </form>';
        echo '
        <table class="table table-hover"><tr>
            <th>Dier nummer</th>
            <th>Naam</th>
            <th>Geboorte datum</th>
            <th>Geboorte plaats</th>
            <th>Hoofdsoort</th>
            <th>Subsoort</th>
            <th>Gebied naam</th>
            <th>Verblijf nummer</th>
            <th></th>
        </tr>';
        foreach ($animals as $animal) {
            echo '
            <tr>
                <td>' . $animal["AnimalID"] . '</td>
                <td><a href="?page=animalCard&animalID=' . $animal["AnimalID"] . '">' . $animal["AnimalName"] . '</a></td>
                <td>' . $animal["BirthDate"] . '</td>
                <td>' . $animal["BirthPlace"] . '</td>
                <td>' . $animal["LatinName"] . '</td>
                <td>' . $animal["SubSpeciesName"] . '</td>
                <td>' . $animal["AreaName"] . '</td>
                <td>' . $animal["EnclosureID"] . '</td>';
                if (canUpdate()) {
                    echo '
                    <td>
                        <a href="?page=changeAnimal&animalID=' . $animal["AnimalID"] . '"> <button type="button" class="btn btn-default" >Aanpassen</button></a>
                    </td>';
                }
                echo '
            </tr>';
        };
        echo '
        </table>';
    }

