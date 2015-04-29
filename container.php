<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 24-4-2015
 * Time: 12:19
 */

include 'navbar.php';
echo '
    <br><br><br>
    <div class="container">';
//    include 'findAnimal.php';


echo '<div class="row">';
if($_SESSION['FUNCTION'] == 'HeadKeeper') {
    include 'headKeeperHome.php';
    include 'keeperHome.php';
}else if($_SESSION['FUNCTION'] == 'Keeper') {
    include 'keeperHome.php';
};
//echo '
//        <div class="col-lg-4">
//          <h2>Heading</h2>
//          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
//          <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
//       </div>
//        <div class="col-lg-4">
//          <h2>Heading</h2>
//          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa.</p>
//          <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
//        </div>
//      </div>';

  echo'  </div> <!-- /container -->';

