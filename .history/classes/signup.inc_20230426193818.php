<?php

if (isset($_POST["submit"])) {
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];
    $url = "userpage.php?id=<?= $user['id'] ?>"

    include "../classes/dbh.classes.php";
    include "../classes/signup.classes.php";
    include "../classes/signup-contr.classes.php";
    $signup = new SignupContr($name, $uid, $email, $pwd, $pwdRepeat);

    $signup->signupUser();

    header("location: ../index.php?error=none");
}