<?php session_start()?>
<?php
//連接資料庫
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "video_db";

// 建立與資料庫的連線
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 確認連線是否成功
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());}

function hasLiked($conn, $post_id, $user_id) {
    $sql = "SELECT * FROM `likes` WHERE postId=$post_id AND usersId=$user_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return true;
    }
    return false;
}
?>


<?php
// 更新貼文的讚數並記錄按讚紀錄
if (isset($_POST['like'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['userid']; // 假設已經登入
    if (!hasLiked($conn, $post_id, $user_id)) {
        $sql = "UPDATE `posts` SET `likes`=`likes`+1 WHERE `postId`=$post_id";
        mysqli_query($conn, $sql);
        $sql = "INSERT INTO `likes` (postId, usersId) VALUES ($post_id, $user_id)";
        mysqli_query($conn, $sql);
    }
    else{
        $sql = "UPDATE `posts` SET `likes`=`likes`-1 WHERE `postId`=$post_id";
        mysqli_query($conn, $sql);
        $sql = "DELETE FROM likes WHERE `likes`.`usersId` = $user_id AND `likes`.`postId` = $post_id";
        mysqli_query($conn, $sql);
    }
    header("Location: post.php");
}


//重定向到貼文列表頁面


if (isset($_POST['profilelike'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['userid']; // 假設已經登入
    if (!hasLiked($conn, $post_id, $user_id)) {
        $sql = "UPDATE `posts` SET `likes`=`likes`+1 WHERE `postId`=$post_id";
        mysqli_query($conn, $sql);
        $sql = "INSERT INTO `likes` (postId, usersId) VALUES ($post_id, $user_id)";
        mysqli_query($conn, $sql);
    }
    else{
        $sql = "UPDATE `posts` SET `likes`=`likes`-1 WHERE `postId`=$post_id";
        mysqli_query($conn, $sql);
        $sql = "DELETE FROM likes WHERE `likes`.`usersId` = $user_id AND `likes`.`postId` = $post_id";
        mysqli_query($conn, $sql);
    }
    header("Location: profile.php");
}

if (isset($_POST['userpagelike'])) {
    $post_id = $_POST['post_id'];
    $post_user = $_POST['post_userid'];
    $user_id = $_SESSION['userid']; // 假設已經登入
    if (!hasLiked($conn, $post_id, $user_id)) {
        $sql = "UPDATE `posts` SET `likes`=`likes`+1 WHERE `postId`=$post_id";
        mysqli_query($conn, $sql);
        $sql = "INSERT INTO `likes` (postId, usersId) VALUES ($post_id, $user_id)";
        mysqli_query($conn, $sql);
    }
    else{
        $sql = "UPDATE `posts` SET `likes`=`likes`-1 WHERE `postId`=$post_id";
        mysqli_query($conn, $sql);
        $sql = "DELETE FROM likes WHERE `likes`.`usersId` = $user_id AND `likes`.`postId` = $post_id";
        mysqli_query($conn, $sql);
    }
    header("Location:userpage.php?varname=".urlencode($post_user));
}
//關閉資料庫連接
mysqli_close($conn);
?>
