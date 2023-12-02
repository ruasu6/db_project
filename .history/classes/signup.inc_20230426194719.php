<?php

if (isset($_POST["submit"])) {
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];
    
    include "../classes/dbh.classes.php";
    include "../classes/signup.classes.php";
    include "../classes/signup-contr.classes.php";

    $url = "userpage.php?id= $uid";

    // 建立 SQL 查詢字串，查詢使用者名稱
    $sql = "UPDATE `users` SET `usersUrl`=? WHERE `usersUid`=?";

    // 執行查詢
    $result = $conn->query($sql);

    $signup = new SignupContr($name, $uid, $email, $pwd, $pwdRepeat);

    $signup->signupUser();

    header("location: ../index.php?error=none");
}