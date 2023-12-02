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

// 檢查是否提交表單
if (isset($_POST["submit"])) {
    // 確認是否有選擇檔案
        $uname = $_POST["textarea1"];
        $udesc = $_POST["textarea2"];
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        // 從表單獲取名稱和描述
        

        // 取得檔案資訊
        $img = $_FILES['image']['name'];
        $tmpName  = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];

        // 檢查文件類型和大小
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if (!in_array($fileType, $allowedTypes) || $fileSize > $maxFileSize) {
            echo "文件格式不支持或文件大小超過限制";
            exit;
        }

        // 讀取檔案內容
        $content = file_get_contents($tmpName);
        $content = mysqli_real_escape_string($conn, $content);

        // 檢查是否已定義 $userid 變量
        $userid = 1; // 這裡假設 userid = 1

        // 建立 SQL 指令
        $sql = "UPDATE `users` SET `usersName`='$uname', `usersDesc`='$udesc', `usersIMG`='$img'ontent`='$content' WHERE `usersUid`= $userid";  
    }else{
        $sql = "UPDATE `users` SET `usersName`='$uname', `usersDesc`='$udesc';";  
    }
    
    // 執行 SQL 指令
        if ($conn->query($sql) === TRUE) {
            echo "檔案上傳成功";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
}

// 關閉資料庫連線
if (isset($conn)) {
    $conn->close();
}
?>
