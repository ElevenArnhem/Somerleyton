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


        $staffID = $_POST["STAFFID"];
        $password = $_POST["PASSWORD"];

        $stmtGetUser = $dbh->prepare("SELECT * FROM Staff where StaffID = ?");
        if($stmtGetUser->execute(array($staffID))){
            while($userRow = $stmtGetUser->fetch()) {
                 $given_hash = $userRow['Password'];
                 $parts = explode('$', $given_hash);
                 $test_hash = crypt($password, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));

                if($given_hash === $test_hash) {
                    $value = $staffID;
                    
                    $functionstmt = $dbh->prepare("EXEC proc_getFunctionFromStaffID ?");
                    $functionstmt->bindParam(1, $value);
                    $functionstmt->execute();

                    while($row = $functionstmt->fetch()) {

                        $_SESSION['FUNCTION'] = $row[0];
                    }
                    $_SESSION['STAFFID'] = $userRow['StaffID'];
                    $_SESSION['STAFFNAME'] = $userRow['StaffName'];
                }
            }

        }
        header('Location:index.php');
