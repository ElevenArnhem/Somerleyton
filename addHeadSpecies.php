<h1>Hoofdsoort toevoegen</h1>

<form action="addHeadSpeciesPost.php" method="post">
    <input type='hidden' name='STAFFID' value='<?php echo $_SESSION['STAFFID']; ?>'>
    <div class="form-group">
        <label>Naam</label>
        <input name="LATINNAME" type="text" class="form-control" placeholder="Naam hoofdsoort">
    </div>
    <input class="btn btn-default" type="submit" name="submit" value="Toevoegen">
</form>