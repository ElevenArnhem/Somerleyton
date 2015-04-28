<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 28-4-2015
 * Time: 14:56
 */

//sec_session_start();
session_start();
	include 'conn.inc.php';
//	$type = $_POST["TYPE"];

//	if($type == "login") {

        $staffName = $_POST["STAFFNAME"];
        $password = $_POST["PASSWORD"];


        // the hash stored for the user
        $stmtGetUser = $dbh->prepare("SELECT * FROM Staff where StaffName = ?");
        if($stmtGetUser->execute(array($staffName))){
            while($userRow = $stmtGetUser->fetch()) {


                // $given_hash = $userRow['PASSWORD'];

                // echo $userRow['PASSWORD']. " ---db password". "\n". phpversion();

                // // extract the hashing method, number of rounds, and salt from the stored hash
                // // and hash the password string accordingly
                // $parts = explode('$', $given_hash);

                // $test_hash = crypt($password, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));


                // // compare
                // echo $given_hash . "\n" . $test_hash . "\n" . var_export($given_hash === $test_hash, true);

                // if($given_hash === $test_hash) {
                // 	 $_SESSION['EMAIL'] = $email;
                // 	 $_SESSION['USERTYPE'] = 'admin';
                // 	 echo $_SESSION['USERTYPE'];

                // }
                $given_hash = $userRow['Password'];
                if($given_hash === $password) {
                    $_SESSION['STAFFNAME'] = $staffName;
                    $_SESSION['FUNCTION'] = 'admin';
                    echo $_SESSION['FUNCTION'];

                }
            }

        }
//        header('Location:/');
//    }