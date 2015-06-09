<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 19-5-2015
 * Time: 14:04
 */
if(canRead()) {
    $searchString = '';
    if (isset($_POST['SEARCHSTRING'])) {
        $searchString = $_POST['SEARCHSTRING'];
    }
    $getRecipesstmt = $dbh->prepare("EXEC proc_SearchRecipe ?");
    $getRecipesstmt->bindParam(1, $searchString);
    $getRecipesstmt->execute();
    $recipes = $getRecipesstmt->fetchAll();
    echo ' 
    <br>
    <div class="row">
      <div class="col-lg-8">
        <form action="index.php?page=recipes" method="post">
          <div class="col-lg-6">
            <div class="input-group">
              <input name="SEARCHSTRING" type="text" class="form-control" placeholder="Zoek recepten op ingredient naam">
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit" >Zoek</button>
              </span>
        </div><!-- /input-group -->
      </div><!-- /.col-lg-6 -->
    <br><br>
    </form>
    <table class="table table-hover"><tr>
                <th>ReceptID</th>
                <th>Ingredienten</th>
                <th>Hoeveelheid</th>
    </tr>';
    $recipeID = 0;
    foreach ($recipes as $recipe) {
        $items = null;
        if ($recipeID != $recipe['FeedingRecipeID']) {
            $recipeID = $recipe['FeedingRecipeID'];
            echo '<tr>
                  <td>' . $recipe['FeedingRecipeID'] . '</td>
                  <td>
            ';
            if ($recipeID == $recipe['FeedingRecipeID']) {
                foreach ($recipes as $recipe1) {
                    if ($recipeID == $recipe1['FeedingRecipeID']) {
                        $popupRow = $recipe1['ItemName'];
                        echo $popupRow . '<br>';

                    }
                }
            }
            echo '
            </td><td>';
            if ($recipeID == $recipe['FeedingRecipeID']) {
                foreach ($recipes as $recipe1) {
                    if ($recipeID == $recipe1['FeedingRecipeID']) {
                        $popupRow = $recipe1['Amount'] . ' ' . $recipe1['Unit'];
                        echo $popupRow . '<br>';
                    }
                }
            }
            echo '</td>';
        }
        echo '</tr>';
    };
    echo '
    </table>
    </div>
</div>
 ';
}