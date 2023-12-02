<?php
    //讀取
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
        $uemail = $row["usersEmail"];
        $img = $row["usersIMG"];
    } else {
        echo "查無此使用者";
    }
?>


<?php
// 上傳
// 資料庫連線設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "video_db";

// 建立資料庫連線
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("連線失敗: " . $e->getMessage());
}

$max = "SET global max_allowed_packet=33554432";
$conn->exec($max);

if (isset($_POST["submit"])) {
    // 取得使用者輸入的內容
    
    $img= $_FILES["file"]['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $uname = $_POST["textarea1"];
    $udesc = $_POST["textarea2"];

    // 建立 SQL 插入語句，插入內容到資料庫
    
    // 準備 SQL 語句
    if ($img != null) {
        $fp = fopen($file_temp, 'r');
        $data = fread($fp, filesize($file_temp));
        $data = addslashes($data);
        $sql = "UPDATE `users` SET `usersName`=?, `usersDesc`=?, `usersIMG`=? WHERE `usersUid`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $uname);
        $stmt->bindParam(2, $udesc);
        $stmt->bindParam(3, $data, PDO::PARAM_LOB);
        $stmt->bindParam(4, $userid);
    } else {
        $sql = "UPDATE `users` SET `usersName`=?, `usersDesc`=? WHERE `usersUid`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $uname);
        $stmt->bindParam(2, $udesc);
        $stmt->bindParam(3, $userid);
    }
    $conn->exec($sql);
    fclose($fp);

    // 執行 prepared statement
    if ($stmt->execute()) {
    ?>
        <script>
            alert("個人資料已成功更新");
        </script>
    <?php
    } else {
    ?>
        <script>
            alert("發生錯誤");
        </script>
    <?php
    }

    // 關閉 prepared statement
    $stmt->closeCursor();
}


// 關閉資料庫連線
$conn = null;
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
    <link href="css/user_info.css" rel="stylesheet">

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
                                        $img_src = "data:image/png;base64," .base64_encode($img);
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
                                    <form class="form-horizontal" method = "post" enctype="multipart/form-data">
                                    <fieldset>


                                    <!-- File Button --> 
                                    <div class="form-group">
                                    <label class="col-md-4 control-label" for="filebutton">New profile pic</label>
                                    <div class="col-md-4">
                                        <input id="filebutton" name="file" class="input-file" type="file" accept="image/*">
                                    </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="id">Member ID</label>
                                        <div class="id_text2">
                                            <?php
                                                echo $mid;
                                            ?>
                                        </div>
                                    
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="uid">User ID</label>  
                                    <!-- <div class="col-md-4"> -->
                                        <div class="id_text2">
                                        <?php
                                            if (isset($_SESSION['userid'])) {
                                                echo $_SESSION['useruid'];
                                            }
                                            ?>
                                        </div>
                                    <!-- <input id="uid" name="uid" type="text" placeholder="placeholder" class="form-control input-md"> -->
                                        
                                    <!-- </div> -->
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Username</label>  
                                    <!-- <div class="col-md-4"> -->
                                    <textarea class="form-control" id="id_text" name="textarea1"><?php echo $uname; ?></textarea>
                                    <!-- </div> -->
                                    <!-- <div id="id_text"> -->
                                            
                                                
                                            
                                        <!-- </div> -->
                                    </div>

                                    <!-- Password input-->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="id">Email</label>
                                        <div class="id_text1">
                                            <?php
                                                echo $uemail;
                                            ?>
                                        </div>
                                    
                                    </div>

                                    <!-- Textarea -->
                                    <div class="form-group">
                                    <label class="col-md-4 control-label" for="textarea">User Description</label>
                                    <div class="col-md-4">                     
                                        <textarea class="form-control" id="textarea" name="textarea2"><?php echo $udesc; ?></textarea>
                                    </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="submit"></label>
                                        <div class="col-md-4">
                                            <button id="submit" type ="submit" name="submit" class="btn btn-primary">Submit</button>
                                            
                                        </div>
                                    </div>

                                    </fieldset>
                                    </form>
                                    </div>
                                    
                                </div>
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
