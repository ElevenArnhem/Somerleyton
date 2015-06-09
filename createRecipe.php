<?php
if(canRead() && canCreate()) {
    if (isset($_GET['headspecies']) && isset($_GET['subspecies'])) {
        $staffID = $_SESSION['STAFFID'];

        $receptID = null;
        if (isset($_POST['receptID'])) {
            $receptID = $_POST['receptID'];
        }

        if (isset($_POST['btnAddItem'])) {
            if ($receptID == null) {
                $headSpecies = $_GET['headspecies'];
                $subSpecies = $_GET['subspecies'];
                $createFeedingRecipeStatement = $dbh->prepare("EXEC proc_AddFeedingRecipe ?,?,?");
                $createFeedingRecipeStatement->bindParam(1, $staffID);
                $createFeedingRecipeStatement->bindParam(2, $headSpecies);
                $createFeedingRecipeStatement->bindParam(3, $subSpecies);
                $createFeedingRecipeStatement->execute();

                $createFeedingRecipeStatement->nextRowset();
                $feedingRecipe = $createFeedingRecipeStatement->fetch();
                $receptID = $feedingRecipe[0];
            }

            $amount = $_POST['aantal'];
            $itemID = $_POST['itemID'];

            $addItemToRecipeStatement = $dbh->prepare("EXEC proc_addItemInFeedingRecipe ?, ?, ?, ?");
            $addItemToRecipeStatement->bindParam(1, $staffID);
            $addItemToRecipeStatement->bindParam(2, $receptID);
            $addItemToRecipeStatement->bindParam(3, $itemID);
            $addItemToRecipeStatement->bindParam(4, $amount);
            $addItemToRecipeStatement->execute();

            spErrorCaching($addItemToRecipeStatement);
        }

        if (isset($_POST['btnDeleteItemFromRecipe'])) {
            $itemID = $_POST['itemID'];

            $removeItemFromRecipeStatement = $dbh->prepare("EXEC proc_DeleteItemInFeedingRecipe ?, ?, ?");
            $removeItemFromRecipeStatement->bindParam(1, $staffID);
            $removeItemFromRecipeStatement->bindParam(2, $receptID);
            $removeItemFromRecipeStatement->bindParam(3, $itemID);
            $removeItemFromRecipeStatement->execute();

            spErrorCaching($removeItemFromRecipeStatement);
        }

        if ($receptID != null) {
            $receptDetailsStatement = $dbh->prepare("EXEC proc_GetVoerRecept ?");
            $receptDetailsStatement->bindParam(1, $receptID);
            $receptDetailsStatement->execute();
            $itemsInRecept = $receptDetailsStatement->fetchAll();
        }

        if (isset($_POST['zoeken'])) {
            $criteria = $_POST['SearchCriteria'];

            $itemsStatement = $dbh->prepare("EXEC proc_searchItems ?, ?");
            $itemsStatement->bindParam(1, $staffID);
            $itemsStatement->bindParam(2, $criteria);
            $itemsStatement->execute();
            $items = $itemsStatement->fetchAll();
        } else {
            $itemsStatement = $dbh->prepare("EXEC proc_GetItems ?");
            $itemsStatement->bindParam(1, $staffID);
            $itemsStatement->execute();
            $items = $itemsStatement->fetchAll();
        }
        ?>
        <div class="row">
            <div class="col-lg-6">
                <h3>Hoofdsoort: <?php echo $_GET['headspecies'] ?> </h3>

                <h3>Subsoort: <?php echo $_GET['subspecies'] ?></h3>
                <a role="button" class="btn btn-primary"
                   href="index.php?page=feedingscheme&headspecies=<?php echo $_GET['headspecies'] ?>&subspecies=<?php echo $_GET['subspecies'] ?>">Terug
                    naar voedingsschema</a>

                <h1>Recept nummer: <?php echo $receptID ?></h1>
                <table class="table table-hover">
                    <tr>
                        <th>
                            Item
                        </th>
                        <th>
                            Hoeveelheid
                        </th>
                        <th></th>
                    </tr>
                    <?php
                    if ($receptID != null)
                        foreach ($itemsInRecept as $itemInRecept) { ?>
                            <tr>
                                <td>
                                    <?php echo $itemInRecept["ItemName"] ?>
                                </td>
                                <td>
                                    <?php echo $itemInRecept["Amount"] . ' ' . $itemInRecept["Unit"] ?>
                                </td>
                                <td>
                                    <form action="index.php?page=createRecipe&headspecies=<?php echo $_GET['headspecies'] ?> &subspecies=<?php echo $_GET['subspecies'] ?>" method="post">
                                        <input type="hidden" name="receptID" value="<?php echo $receptID ?>">
                                        <input type="hidden" name="itemID" value="<?php echo $itemInRecept["ItemID"] ?>">
                                        <button type="submit" name="btnDeleteItemFromRecipe" class="btn btn-link"
                                                aria-label="Left Align">
                                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                    ?>
                </table>
            </div>

            <div class="col-lg-6">
                <h1>Items</h1>
                <form
                    action="index.php?page=createRecipe&headspecies=<?php echo $_GET['headspecies'] ?>&subspecies=<?php echo $_GET['subspecies'] ?>"
                    method="post">
                    <div class="input-group">
                        <input type="hidden" name="receptID" value="<?php echo $receptID ?>">
                        <input name="SearchCriteria" type="text" class="form-control" placeholder="Zoeken">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" name="zoeken">Zoeken</button>
                    </span>
                    </div>
                </form>
                <table class="table table-hover">
                    <tr>
                        <th>
                            Nummer
                        </th>
                        <th>
                            Naam
                        </th>
                        <th>
                            Aantal
                        </th>
                        <th>
                            Eenheid
                        </th>
                        <th></th>
                    </tr>
                    <?php
                    foreach ($items as $item) { ?>
                        <form
                            action="index.php?page=createRecipe&headspecies=<?php echo $_GET['headspecies'] ?>&subspecies=<?php echo $_GET['subspecies'] ?>"
                            method="post">
                            <tr>
                                <td>
                                    <?php echo $item["ItemID"] ?>
                                </td>
                                <td>
                                    <?php echo $item["ItemName"] ?>
                                </td>
                                <td>
                                    <input type="number" min="1" max="2147483646" class="form-control" name="aantal"
                                           value="1">
                                </td>
                                <td>
                                    <?php echo $item["Unit"] ?>
                                </td>
                                <td>
                                    <input type="hidden" name="receptID" value="<?php echo $receptID ?>">
                                    <input type="hidden" name="itemID" value="<?php echo $item["ItemID"] ?>">
                                    <button type="submit" name="btnAddItem" class="btn btn-link"
                                            aria-label="Left Align">
                                        <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                                    </button>
                                </td>
                            </tr>
                        </form>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    <?php
    }
}
?>