<form action="addSubSpeciesPost.php" method="post">
    <input type='hidden' name='STAFFID' value='<?php echo $_SESSION['STAFFID']; ?>'>
    <div class="form-group">
        <label>Naam</label>
        <input name="SUBSPECIESNAME" type="text" class="form-control" placeholder="Naam soort">
    </div>
    <div class="form-group">
        <label>Latijnse naam</label>
        <input name="LATINNAME" type="text" class="form-control" placeholder="Latijnse naam">
    </div>
    <div class="form-group">
        <label>Beschrijving</label>
        <textarea name="DESCRIPTION" class="form-control" rows="3"></textarea>
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Toevoegen">
</form>