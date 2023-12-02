

<?php
// 建立與資料庫的連線
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "video_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 取得來自前端的查詢參數
$query = $_GET['query'];

// 使用查詢參數構建 SQL 查詢
$sql = "SELECT usersUid, usersName FROM users WHERE usersUid LIKE '%$query%' OR usersName LIKE '%$query%'";

// 执行 SQL 查询
$result = $conn->query($sql);

// 存储所有匹配的用户名和姓名
$userData = array();
while ($row = $result->fetch_assoc()) {
    $userData[] = array(
        'label' => $row['usersUid'],
        'name' => $row['usersName']
    );
}

// 将用户名和姓名返回给前端
$response = array();
foreach ($userData as $data) {
    $username = $data['label'];
    $sql2 = "SELECT usersId, usersIMG FROM users WHERE usersUid = '$username'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        $row = $result2->fetch_assoc();
        $Id = $row['usersId'];
        $name = $data['name'];
        $img = $row['usersIMG'];
        $response[] = array(
            'label' => $username,
            'name' => $name,
            'img' => base64_encode($img),
            'url' => 'http://localhost/db_project/userpage.php?varname=' . urlencode($Id)
        );
    }
}



// 返回 JSON 格式的搜尋結果
header('Content-Type: application/json');
echo json_encode($response);

// 關閉資料庫連線
$conn->close();
?>
