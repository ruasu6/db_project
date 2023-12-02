<?php

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];
    
    include "../classes/dbh.classes.php";
    include "../classes/signup.classes.php";
    include "../classes/signup-contr.classes.php";

    $signup = new SignupContr($name, $uid, $email, $pwd, $pwdRepeat);

    $signup->signupUser();

    header("location: ../index.php?error=none");

    // 資料庫連線設定
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "video_db";

    // 建立資料庫連線
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 檢查連線是否成功
    if ($conn->connect_error) {
        die("連線失敗: " . $conn->connect_error);
    }

    if ($result->num_rows > 0) {
        // 取得使用者 ID
        $row = $result->fetch_assoc();
        $userId = $row["usersId"];
        
        // 建立使用者 URL
        $url = "edit.php?id=" . $userId;

        // 將使用者 URL 寫入資料庫
        $sql = "UPDATE users SET usersUrl = ? WHERE usersUid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $url, $uid);
        $stmt->execute();
        $stmt->close();
    }

    header("location: ../index.php?error=none");
    exit();

}