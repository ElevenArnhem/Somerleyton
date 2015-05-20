<?php
    session_start();
    include 'conn.inc.php';

    $staffID = $_POST["STAFFID"];
    $password = $_POST["PASSWORD"];

    $stmtGetUser = $dbh -> prepare("proc_getLoginInfoFromStaffID ?");
    $stmtGetUser -> bindParam(1, $staffID);
    $stmtGetUser-> execute();

    while($loginInfoRow = $stmtGetUser -> fetch()) {
        $given_hash = $loginInfoRow['Password'];
        $parts = explode('$', $given_hash);
        $test_hash = crypt($password, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));

        if($given_hash === $test_hash) {
            $_SESSION['STAFFID'] = $loginInfoRow['StaffID'];
            $_SESSION['STAFFNAME'] = $loginInfoRow['StaffName'];
            $_SESSION['FUNCTION'] = $loginInfoRow['Function'];
        }
    }
    if( $_SESSION['FUNCTION'] == 'KantoorPersoneel') {
        header('Location:index.php?page=medewerkers');
    } else {
        header('Location:index.php');
    }