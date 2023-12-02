<?php
    session_start();
    
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

    // 建立 SQL 查詢字串，查詢使用者名稱
    $sql = "SELECT * FROM users WHERE usersUid = '$userid'";

    // 執行查詢
    $result = $conn->query($sql);

    // 檢查是否有查詢到結果
    if ($result->num_rows > 0) {
        // 取得查詢結果中的第一筆資料
        $row = $result->fetch_assoc();
        // 顯示使用者名稱
        $mid = $row["usersId"];
        $uname = $row["usersName"];
        $udesc = $row["usersDesc"];
        $img = $row["usersIMG"];
        $posts = $row["posts"];
        $followers = $row["followers"];
        $followings = $row["following"];
    } else {
        echo "查無此使用者";
    }

    // 關閉資料庫連線
    $conn->close();
    
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
                                        $img_src = "data:image/png;base64," . base64_encode($img);
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
                                                echo $uname;
                                            ?></div>
                                        <div class="rela-block user-desc" id="user_description">
                                            <?php
                                                echo $udesc;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="rela-block profile-card-stats">
                                        <div class="floated profile-stat works" id="num_works"><?php echo $posts?><br></div>
                                        
                                        <div class="floated profile-stat followers" id="num_followers"><?php echo $followers?><br></div>
                                        
                                        <div class="floated profile-stat following" id="num_following"><?php echo $followings?><br></div>
                                    
                                    </div>
                                    <div id='>test</div>
                                </div>
                                <div class="rela-block content">
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
                                    <div class="rela-inline image"></div>
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