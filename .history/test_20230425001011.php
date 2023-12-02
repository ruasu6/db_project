<?php
// 資料庫連線設定
$servername = "localhost";
$username = "username";
$password = "";
$dbname = "users";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

if (isset($_POST["submit"])) {
// 確認是否有選擇檔案
    $uname = $_POST["textarea1"];
    $udesc = $_POST["textarea2"];

    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {

        // 取得檔案資訊
        $img = $_FILES['image']['name'];
        $tmpName  = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];

        // 讀取檔案內容
        $fp = fopen($tmpName, 'r');
        $content = fread($fp, filesize($tmpName));
        $content = addslashes($content);
        fclose($fp);

        // 建立 SQL 指令
        $sql = "UPDATE `users` SET  `usersIMG`= images (name, size, type, content) VALUES ('$', '$fileSize', '$fileType', '$content')";

        // 執行 SQL 指令
        if ($conn->query($sql) === TRUE) {
            echo "檔案上傳成功";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
// 關閉資料庫連線
$conn->close();
?>
