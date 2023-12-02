<?php

if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $username = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php'; 

    if (emptyInputSignup( $name, $username, $email, $pwd, $pwdRepeat) !== false) {
        header("location: ../register.php?error=emptyinput");
        exit();
    }
    if (invalidUid($username) !== false) {
        header("location: ../register.php?error=invaliduid");
        exit();
    }
    if (invalidEmail($email) !== false) {
        header("location: ../register.php?error=invalidemail");
        exit();
    }
    if (pwdMatch($pwd, $pwdRepeat) !== false) {
        header("location: ../register.php?error=passwordsdontmatch");
        exit();
    }
    if (uidExists($conn, $username, $email) !== false) {
        header("location: ../register.php?error=usernametaken");
        exit();
    }

    createUser($conn, $name, $username, $email, $pwd);
}
else {
    header("location: ../register.php");
    exit();
}

