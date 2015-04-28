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

        $staffID = $_POST["STAFFID"];
        $password = $_POST["PASSWORD"];


        // the hash stored for the user
        $stmtGetUser = $dbh->prepare("SELECT * FROM Staff where StaffID = ?");
        if($stmtGetUser->execute(array($staffID))){
            while($userRow = $stmtGetUser->fetch()) {


                 $given_hash = $userRow['Password'];

                 echo $userRow['StaffName']. " ---db password". "\n". phpversion();

                 // extract the hashing method, number of rounds, and salt from the stored hash
                 // and hash the password string accordingly
                 $parts = explode('$', $given_hash);
//                    echo $parts[1];
                 $test_hash = crypt($password, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));


                 // compare
//                 echo $given_hash . "\n" . $test_hash . "\n" . var_export($given_hash === $test_hash, true);

                // if($given_hash === $test_hash) {
                //
                // 	 $_SESSION['USERTYPE'] = 'admin';
                // 	 echo $_SESSION['USERTYPE'];

                // }

//                $hashedPass = crypt($password, sprintf('$6$rounds=%d$%s$', $rounds, $salt));




//                echo $hashedPass;
//                $given_hash =$userRow['StaffName'];
                if($given_hash === $test_hash) {
                    $_SESSION['STAFFID'] = $userRow['StaffID'];
                    $_SESSION['STAFFNAME'] = $userRow['StaffName'];
                    $_SESSION['FUNCTION'] = 'admin';
//                    echo $_SESSION['FUNCTION'];

                }
            }

        }
        header('Location:/');
//    }