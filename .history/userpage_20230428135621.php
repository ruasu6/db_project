<?php
session_start();

// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "video_db";

// 建立資料庫連線
$conn_new = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn_new->connect_error) {
    die("連線失敗: " . $conn_new->connect_error);
}

// 取得使用者輸入的 userid
$userid = $_SESSION['userid'];
// $sql_o = "SELECT * FROM `users` WHERE `usersId`='$userid'";
// $result_o = $conn_new->query($sql_o);

// if ($result_o->num_rows > 0) {
//     $row_o = $result_o->fetch_assoc();
//     $uid = $row_o["usersId"];
// }
$var = $_GET['varname'];
// 查詢字串，查詢使用者名稱
$sql_new = "SELECT * FROM `users` WHERE `usersId`='$var'";

// 執行查詢
$result_new = $conn_new->query($sql_new);

// 檢查是否有查詢到結果
if ($result_new->num_rows > 0) {
    // 取得查詢結果中的第一筆資料
    $row_new = $result_new->fetch_assoc();
    // 顯示使用者名稱
    $mid_new = $row_new["usersId"];
    $uname_new = $row_new["usersName"];
    $udesc_new = $row_new["usersDesc"];
    $img_new = $row_new["usersIMG"];
} else {
    echo "查無此使用者";
}



$current_user_id = $userid;
$user_id_to_follow = $var;



if (isset($_POST["follow"])) {
    echo "<script>alert('followed');</script>";
    $sql_s = "INSERT INTO follows (currentUserId, followedUserId) VALUES ($current_user_id, $user_id_to_follow)";
    $sql_s1 = "UPDATE users SET `followers`=`followers`+1 WHERE `usersId`='$user_id_to_follow'";
    $sql_s2 = "UPDATE users SET `following`=`following`+1 WHERE `usersId`='$current_user_id'";
    $result_s = $conn_new->query($sql_s);
    $result_s1 = $conn_new->query($sql_s1);
    $result_s2 = $conn_new->query($sql_s2);
}

if (isset($_POST["unfollow"])) {
    $sql_u = "DELETE FROM follows WHERE currentUserId='$current_user_id' AND followedUserId='$user_id_to_follow'";
    $sql_u1 = "UPDATE users SET `followers`=`followers`-1 WHERE `usersId`='$user_id_to_follow'";
    $sql_u2 = "UPDATE users SET `following`=`following`-1 WHERE `usersId`='$current_user_id'";
    $result_u = $conn_new->query($sql_u);
    $result_u1 = $conn_new->query($sql_u1);
    $result_u2 = $conn_new->query($sql_u2);
}

function check_if_user_is_following($current_user_id, $user_id_to_follow, $conn_new) {
    $sql_f = "SELECT * FROM follows WHERE currentUserId='$current_user_id' AND followedUserId='$user_id_to_follow'";
    $result_f = $conn_new->query($sql_f);

    if ($result_f->num_rows > 0) {
        return true;
    } else {
        return false;
}

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto:300,400|Yellowtail" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">

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

                        <!-- PAGE STUFF -->
                    
                            <div class="rela-block container">
                                <div class="rela-block profile-card">
                                <?php    
                                        // 將二進制數據轉換為 base64 字串
                                        $img_src = "data:image/png;base64," . base64_encode($img_new);
                                    ?>
                                <style>
                                .background-img {
                                    background: url(<?php echo $img_src; ?>) center/cover no-repeat;
                                    height: 180px;
                                    width: 180px;
                                    border-radius: 50%;
                                }
                                </style>
                                    <div class="profile-pic background-img" id="profile_pic">

                                    </div>
                                    <div class="rela-block profile-name-container">
                                        <div class="rela-block user-name" id="user_name">
                                            <?php
                                                echo $uname_new;
                                            ?></div>
                                        <div class="rela-block user-desc" id="user_description">
                                            <?php
                                                echo $udesc_new;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="rela-block profile-card-stats">
                                        <div class="floated profile-stat works" id="num_works">28<br></div>
                                        
                                        <div class="floated profile-stat followers" id="num_followers">112<br></div>
                                        
                                        <div class="floated profile-stat following" id="num_following">245<br></div>
                                    
                                    </div>
                                    <div style="flex-wrap:wrap;">
                                    <form action="" method="post">
                                    <?php
                                        // 假設已經獲取了當前用戶ID和用戶ID
                                        $current_user_id = $userid;
                                        $user_id_to_follow = $var;

                                        // 在此處檢查當前用戶是否已經追蹤了該用戶
                                        if($current_user_id != $user_id_to_follow){
                                            if (check_if_user_is_following($current_user_id, $user_id_to_follow, $conn_new)) {
                                                echo '<button name="unfollow" id="submit-btn" style="margin: 0 10px;" class="btn btn-primary btn-primary">Unfollow</button>'
                                            } else {
                                                echo '<button name="follow" id="submit-btn" style="margin: 0 10px;" class="btn btn-primary btn-info">Follow</button>'
                                            }
                                        }
                                    ?>
                                    </form>
                                    </div>
                                </div>
                                <div class="rela-block content">
                                    
                                </div>
                                <div class="rela-inline button more-images" onclick="add_images(); inf_scroll = true;">More Images</div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <?php
                        include_once 'footer.php'
                    ?>
                    <!-- End of Footer -->
                </div>
            </div>
            <!-- End of Main Content -->

            

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

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src=”js/profile.js”></script>
</body>

</html>
<?php
// 關閉資料庫連線
$conn_new->close();
    
?>