<?php
    session_start();
    //连接数据库
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

    // 取得使用者輸入的 userid
    $userid = $_SESSION['useruid'];

    //获取当前用户的观看记录和所追踪的用户列表
    $sql_1 = "SELECT * FROM `follows` WHERE `currentUserId`='$userid'";
    // 執行查詢
    $result_1 = $conn_new->query($sql_1);

    // 檢查是否有查詢到結果
    if ($result_1->num_rows > 0) {
        // 取得查詢結果中的第一筆資料
        $row_1 = $result_1->fetch_assoc();
        // 顯示使用者名稱
        $following_users = $row_1["followedUserId"];
       
    } else {
        echo "查無此使用者";
    }
    $sql_2 = "SELECT * FROM `video` WHERE `usersId`='$userid'";
    // 執行查詢
    $result_2 = $conn_new->query($sql_2);

    // 檢查是否有查詢到結果
    if ($result_2->num_rows > 0) {
        // 取得查詢結果中的第一筆資料
        $row_2 = $result_2->fetch_assoc();
        // 顯示使用者名稱
        $watch_history = $row_2["vid"];
        $watch_history_type = $row_2["vType"];
       
    } else {
        echo "查無此使用者";
    }

    //计算用户之间的相似度
    function similarity($user1, $user2, $conn) {
        //根据用户的观看记录和所追踪的用户列表计算相似度，这里使用余弦相似度算法
        $sql = "SELECT COUNT(DISTINCT video) FROM watch_history WHERE user_id IN ($user1, $user2)";
        $result = mysqli_query($conn, $sql);
        $common_videos = mysqli_fetch_array($result)[0];
        $sql = "SELECT COUNT(DISTINCT video) FROM watch_history WHERE user_id = $user1";
        $result = mysqli_query($conn, $sql);
        $videos1 = mysqli_fetch_array($result)[0];
        $sql = "SELECT COUNT(DISTINCT video) FROM watch_history WHERE user_id = $user2";
        $result = mysqli_query($conn, $sql);
        $videos2 = mysqli_fetch_array($result)[0];
        $similarity = $common_videos / sqrt($videos1 * $videos2);
        return $similarity;
    }

    //获取用户关注的用户列表
    function get_following_users($user_id, $conn) {
        $following_users = array();
        $sql = "SELECT following_id FROM following WHERE user_id = $user_id";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            $following_users[] = $row[0];
        }
        return $following_users;
    }

    //获取用户观看的影片列表
    function get_watch_history($user_id, $conn) {
        $watch_history = array();
        $sql = "SELECT video FROM watch_history WHERE user_id = $user_id";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            $watch_history[] = $row[0];
        }
        return $watch_history;
    }

    //找出与当前用户最相似的几个用户
    $similar_users = array();
    foreach ($following_users as $user) {
        $similarity = similarity($user_id, $user, $conn);
        $similar_users[$user] = $similarity;
    }
    arsort($similar_users); //按照相似度值从大到小排序

    //对于这几个最相似的用户，找出他们所追踪的用户以及观看的影片，并计算他们与当前用户之间的相似度
    $recommended_users = array();
    foreach ($similar_users as $user => $similarity) {
        $following = get_following_users($user, $conn);
        $watch_history = get_watch_history($user, $conn);
        foreach ($following as $following_user) {
            if ($following_user == $user_id) { //排除当前用户和已经被推荐过的用户
                continue;
            }
            if (in_array($following_user, $following_users)) { //已经被当前用户所追踪的用户不做推荐
                continue;
            }
            $similarity = similarity($user_id, $following_user, $conn);
            if ($similarity > 0.5) { //相似度超过阈值的用户才做推荐
                $recommended_users[$following_user] = $similarity;
            }
            foreach ($watch_history as $video) { //对于这个用户所看过的每一个影片，如果当前用户没有看过，则推荐这个影片
                if (!in_array($video, $watch_history)) {
                    $recommended_videos[$video][] = $following_user;
                }
            }
        }
    }
            
    //按照相似度值从大到小排序
    arsort($recommended_users);
    
    //输出推荐的用户和影片
    echo "推荐可能认识的用户：<br>";
    foreach ($recommended_users as $user => $similarity) {
        echo "用户 " . $user . " 相似度 " . $similarity . "<br>";
    }
    echo "<br>";
    echo "推荐的影片：<br>";
    foreach ($recommended_videos as $video => $users) {
        echo "影片 " . $video . " 推荐给：";
    foreach ($users as $user) {
        echo $user . " ";
    }
    echo "<br>";
    }
    //关闭数据库连接
    mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
            include_once 'sidebar.php'
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                    include_once 'header.php'
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                        <!-- Your code! -->
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Blank Page</h1>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php
                include_once 'footer.php'
            ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>