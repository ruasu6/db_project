<?php
    session_start();
    require_once './search/dbh.inc.php';

    $userid = $_SESSION['userid'];
    $sql = "SELECT comment FROM video WHERE usersId = '$userid'";

    $result = $conn->query($sql);

    $videoComment = array();
    $i = 0;
    $j = 0;

    // 檢查是否有查詢到結果
    while ($row = $result->fetch_assoc()) {
        // 顯示使用者名稱
        $videoComment[$i] = $row["comment"];
        $i++;
        
    } 
    $new = json_encode($videoComment, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
    // echo $new;

?>

<?php
    require_once './search/dbh.inc.php';
   

    $useruid = $_SESSION['useruid'];
    $userid = $_SESSION['userid'];

    if(isset($_POST['submit'])){
        $comment = $_POST['comment'];
        $videoId = $_POST['videoId'];
        $sql = "UPDATE `video` SET `comment`=? WHERE `vId`=? AND `usersId`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $comment, $videoId, $userid);
        $stmt->execute();
        // 關閉 prepared statement
        $stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']. "?edit=success");
        exit(); // 確保程式不會繼續執行下去
                
        }
    if(isset($_POST['delete'])){
        $videoId = $_POST['videoId'];
        $sql_delete = "DELETE FROM `video` WHERE `vId`='$videoId' AND `usersId` = '$userid'";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->execute();
        $stmt_delete->close();
        header("Location: ".$_SERVER['PHP_SELF']. "?delete=success");
        exit(); // 確保程式不會繼續執行下去
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

    <title>SERENDIPITY</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/search.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        
            <?php include_once 'sidebar.php'; ?>
        
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
                        <div class="movies">
                            <!-- <input type="text" id = "searchBox"> -->
                            <input type="text" id = "searchBox" placeholder="search the video you watch..">
                            <div id="suggestions"></div>
                        </div>
                        <div class="videolist">
                            <!-- <h2>VideoList</h2> -->
                            <?php  
                                    $useruid = $_SESSION['useruid'];
                                    $userid = $_SESSION['userid'];

                                    // 建立 SQL 查詢字串，查詢使用者名稱
                                    $sql = "SELECT vId, vType FROM video WHERE usersId = '$userid'";

                                    $result = mysqli_query($conn, $sql);

                                    // 將查詢結果轉換成陣列
                                    $rows = array();
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $rows[] = $row;
                                    } 
                            ?>
                        </div>
                    <!-- Page Heading -->

                </div>
                <?php
                    if (isset($_GET["error"])){
                                if($_GET["error"] == "repeat"){
                                    echo "<script>alert('Please fill all fields!')</script>";
                                }

                    }
                    if(isset($_GET['edit'])){
                        if($_GET['edit'] == "success"){
                            echo "<script>alert('edit success!')</script>";
                        }
                    }
                    if(isset($_GET['delete'])){
                        if($_GET['delete'] == "success"){
                            echo "<script>alert('delete success!')</script>";
                        }
                    }
                ?>
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
    <script src="js/search.js"></script>

</body>

</html>

<script>
       var video = <?=json_encode($rows)?>;
        // console.log(video);
        var apiKeyT = "ccfc6902624f5980de6bc284bd7b85e3";
        var videoList = document.querySelector(".videolist");
        var videoComment = <?php echo json_encode($videoComment); ?>;
        console.log(videoComment);

        var i = 0;
        // console.log(videoList);


        // 創建 Promise 函數來處理單個請求
        function getDetail(id, type) {
            return new Promise(function(resolve, reject) {
                var urlT = "https://api.themoviedb.org/3/" + type + "/" + id + "?api_key=" + apiKeyT + "&language=zh-TW";
                var xhr = new XMLHttpRequest();
                xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(JSON.parse(xhr.responseText));
                } else {
                    reject(xhr.statusText);
                }
                };
                xhr.onerror = function() {
                reject(xhr.statusText);
                };
                xhr.open("GET", urlT, true);
                xhr.send();
            });
        }

        // 使用 Promise.all() 等待所有請求完成後再處理回應
        Promise.all(video.map(function(item) {
        return getDetail(item.vId, item.vType);
        })).then(function(details) {
            details.forEach(item => {
                var videoArea = document.createElement("div");
                videoArea.classList.add("videoArea");
                var main = document.createElement("div");
                var title = item.name || item.title;
                var poster = item.poster_path;
                var id = item.id;
                
                var edit = document.createElement("form");
                edit.classList.add("edit");

                var comment = document.createElement("textarea");
                comment.name = "comment";
                comment.setAttribute("placeholder", "write your comment..");
                comment.classList.add("comment");

                var deleteButton = document.createElement("button");
                deleteButton.type = "submit";
                deleteButton.name = "delete";
                deleteButton.innerHTML = "X"
                deleteButton.classList.add("delete");
                // deleteButton.classList.add("btn-danger");



                main.classList.add("videoDetail");
    
                comment.innerHTML = videoComment[i];
                i++;

                var hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.value = id;
                hidden.name = "videoId";
                
                var submit = document.createElement("button");
                submit.type = "submit";
                submit.innerHTML = "submit";
                submit.name = "submit";
                submit.classList.add("editSubmit");
                edit.setAttribute("method", "post");

                
                var movieTitle = document.createElement("div");
                var img =  document.createElement("img");
                movieTitle.innerHTML = title;   
                img.src = "https://image.tmdb.org/t/p/w92/" + poster;

                // main.appendChild(movieTitle);
                main.appendChild(img);

                edit.appendChild(movieTitle);
                edit.appendChild(comment);
                edit.appendChild(hidden);
                edit.appendChild(deleteButton);
                edit.appendChild(submit);

                videoArea.appendChild(main);
                videoArea.appendChild(edit);

                videoList.appendChild(videoArea);
                console.log(videoList);

            });
        }).catch(function(error) {
        console.log(error);
        });

        

    </script>