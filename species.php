<?php
    include 'conn.inc.php';

    $allHeadSpeciesStatement = $dbh->prepare("EXEC proc_GetHeadSpecies");
    $allHeadSpeciesStatement->execute();
    $allHeadSpecies = $allHeadSpeciesStatement->fetchAll();

    $selectedHeadSpecies = '';

    if(isset( $_POST['selectedHeadSpecies'])) {
        $selectedHeadSpecies = $_POST['selectedHeadSpecies'];

        $allSubSpeciesForHeadSpeciesStatement = $dbh -> prepare("proc_getAllSubSpeciesForHeadSpecies ?");
        $allSubSpeciesForHeadSpeciesStatement -> bindParam(1, $selectedHeadSpecies);
        $allSubSpeciesForHeadSpeciesStatement -> execute();
        $subSpeciesForHeadSpecies = $allSubSpeciesForHeadSpeciesStatement -> fetchAll();
    }
?>

<div class="row">
    <div class="col-lg-4">
        <table class="table table-hover">
            <tr>
                <th colspan="2">Hoofdsoorten</th>
            </tr>
            <?php
            foreach($allHeadSpecies as $headSpecies)
            { ?>
                <tr <?php if($selectedHeadSpecies == $headSpecies['LatinName']) { echo 'class="active" ';} ?> >
                    <td>
                        <form action="index.php?page=species" method="POST">
                            <button class="btn btn-link" type="submit" name="selectedHeadSpecies" value="<?php echo $headSpecies["LatinName"] ?>"><?php echo $headSpecies["LatinName"] ?></button>
                        </form>
                    </td>
                    <td>
                        <form action="?page=changeHeadSpecies" method="POST">
                            <button type="submit" class="btn btn-default" name="headSpecies" value="<?php echo $headSpecies["LatinName"] ?>">Aanpassen</button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>

        <div class="btn-group" role="group">
            <a href="?page=addHeadSpecies"> <button type="button" class="btn btn-default" >Hoofdsoort toevoegen</button></a>
        </div>
    </div>
    <div class='col-lg-4'>
    <?php
        if(isset( $_POST['selectedHeadSpecies'])) {
            ?>
            <table class="table table-hover">
                <tr>
                    <th colspan="2">Subsoorten</th>
                </tr>
    
                <?php
                    foreach($subSpeciesForHeadSpecies as $subSpecies)
                    {
                        ?>
                            <tr>
                                <td>
                                    <?php echo $subSpecies["SubSpeciesName"] ?>
                                </td>
    
                                <td>
                                    <form action="index.php?page=changeSubSpecies" method="POST">
                                        <input type="hidden" name="headSpecies" value="<?php echo $selectedHeadSpecies ?>"/>
                                        <button type="submit" class="btn btn-default" name="subSpecies" value="<?php echo $subSpecies["SubSpeciesName"] ?>">Aanpassen</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                    }
                ?>
            </table>

            <div class="btn-group" role="group">
                <form action="?page=addSubSpecies" method="POST">
                    <input type="hidden" name="LATINNAME" value="<?php echo $selectedHeadSpecies ?>"/>
                    <button type="submit" class="btn btn-default">Subsoort toevoegen</button>
                </form>
            </div>
        <?php
        }
    ?>
</div>