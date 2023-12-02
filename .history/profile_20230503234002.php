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
<?php
$db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
if(isset($_GET['post_id'])){
    $id=$_GET['post_id'];
    $max="SET global max_allowed_packet=33554432";
    $db->exec($max);
}
if(isset($_POST["delete"])){
    $post_id = $_POST['post_id'];
    // 刪除 likes 表中的記錄
    $del_likes = "DELETE FROM `likes` WHERE postId = '$post_id'";
    $db->exec($del_likes); 

    $del_comments = "DELETE FROM `comments` WHERE postId = '$post_id'";
    $db->exec($del_comments); 

    // 刪除 posts 表中的記錄
    $del_posts = "DELETE FROM `posts` WHERE postId = '$post_id'";
    $db->exec($del_posts);
    header("Location:profile.php");       
}
?>
<?php
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
                                    <?php 
                                        $nowuserid = $_SESSION['userid'];
                                        $sql = "SELECT COUNT(*) as count FROM posts WHERE `usersId` = $nowuserid";
                                            $stmt = $db->prepare($sql);
                                            $stmt->execute();
                                            $countpost = $stmt->fetchColumn();
                                        ?>
                                        <div class="floated profile-stat works" id="num_works"><?php echo $countpost?><br></div>
                                        
                                        <div class="floated profile-stat followers" id="num_followers"><?php echo $followers?><br></div>
                                        
                                        <div class="floated profile-stat following" id="num_following"><?php echo $followings?><br></div>
                                        
                                    </div>
                                    <div id="test" class="rela-block">
                                        <div class="rela-block postTitle" onclick="showContent('post')">POST</div>
                                        <div class="rela-block videoTitle" onclick="showContent('video')">VIDEOLIST</div>
                                    </div>
                                </div>
                                <div >
                                    <div class="content"></div>
                                    <div class="postBlock "  style="display:none;">
                                        <!-- POST 內容 -->
                                        <!-- <div class="container-fluid my-5"> -->
                                            <?php 
                                            $usersId = $_SESSION['userid'];?>
                                                <!-- <div class="row"> -->
                                            <div class="">
                                                <?php
                                                
                                                ?>
                                                <!--- Post Form Begins -->
                                                <!-- Post Begins -->

                                            
                                                    <?php
                                                    
                                                    $allpost = $db->query("SELECT * FROM `posts` WHERE `usersId`= $usersId ORDER BY `post_date`  DESC");
                                                    
                                                    foreach ($allpost as $all){
                                                    ?>
                                                    <div class="card p-2 mt-3">
                                                        <!-- comment header -->
                                                        <div class="d-flex">
                                                            <div class="">
                                                                <?php
                                                                    $u = $all['usersId']; 
                                                                    $userimg = $db->query("SELECT * FROM `users` WHERE `usersId`=$u");
                                                                    foreach ($userimg as $a) {?>
                                                                    <a class="text-decoration-none" href="http://localhost/db_project/userpage.php?varname=<?php echo $a['usersId'] ?>#">
                                                                    <!-- <img class='profile-pic'  src=\"data:image;base64," + val.photo + "\"/ width='40' height='40' object-fit: 'cover' alt='...'> -->
                                                                    <img class='profile_pic'  src= "data:image/png;base64,<?php echo base64_encode($a['usersIMG']);?>" width='40' height='40' object-fit: cover alt='...'>
                                                                    </a><?php }?>
                                                            </div>
                                                            <div class="flex-grow-1 pl-2">
                                                                <a class="text-decoration-none text-capitalize h6 m-0" href="http://localhost/db_project/userpage.php?varname=<?php echo $a['usersId'] ?>#"><?php echo $all["usersName"]?></a>

                                                                
                                                                <?php $usersId = $_SESSION['userid'];

                                                                if ($all['usersId']==$usersId){ ?>
                                                                <div class="dropdown" style="position: absolute;top: 7px;right: 7px;">
                                                                    <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-chevron-down" ></i>
                                                                    </a>

                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                        <?php $postid = $all['postId'] ?>
                                                                        <a class="dropdown-item text-primary" href="edit.php?varname=<?php echo $postid?>">Edit</a>
                                                                        <form action="" method="post" enctype="multipart/form-data">
                                                                            <input type="hidden" name="post_id" value=<?php echo $postid?>>
                                                                            <button class="dropdown-item text-primary" name = "delete">Delete</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                                <p class="small m-0 text-muted"><?php echo $all["post_date"]?></p>
                                                            </div>		
                                                        </div>
                                                        <!-- comment header -->
                                                        <!-- comment body -->
                                                        
                                                        <div class="card-body p-0">
                                                            <p class="card-text h7 mb-1"><?php echo $all["post_content"]?></p>
                                                        </div>

                                                        <hr class="my-1">
                                                        <!-- post footer begins -->
                                                        <footer class="">
                                                            <div class="">
                                                                <ul class="list-group list-group-horizontal">
                                                                    <li class="list-group-item flex-fill text-center p-0 px-lg-2 border border-0">
                                                                        <form method="POST" action="postlike.php">
                                                                            <a class="small text-decoration-none"type="submit" name="profilelike" href="#">
                                                                                <input type="hidden" name="post_id" value=<?php echo $all['postId']?>>
                                                                                <?php
                                                                                    $usersId = $_SESSION['userid'];

                                                                                    
                                                                                        $db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
                                                                                        $p = $all['postId'];
                                                                                        $row2 = $db->query("SELECT * FROM `likes` WHERE `usersId`=$usersId AND `postId`= $p");
                                                                                        $hasLiked = $row2->rowCount()
                                                                                    
                                                                                    ?>

                                                                                    <button onclick="saveScrollPosition()" type="submit" name="profilelike" style="background-color: transparent; border: none; color: <?php echo $hasLiked ? '#1c145d' : '#4e73df'; ?>; cursor: pointer; <?php echo $hasLiked ? 'font-weight:bold' : ''; ?>">
                                                                                        <i class="far fa-thumbs-up"></i><?php echo " ".$all['likes']." " ?> Like
                                                                                    </button>
                                                                                    
                                                                            </a>
                                                                        </form>
                                                                    </li>
                                                                    <li class="list-group-item flex-fill text-center p-0 px-lg-2 border border-right-0 border-top-0 border-bottom-0">
                                                                    
                                                                        <a class="small text-decoration-none" role="button" href="http://localhost/db_project/postpage.php?varname=<?php echo $all['postId']; ?>"  >
                                                                            <i class="fas fa-comment-alt"></i> 
                                                                            <?php
                                                                            // 执行 SQL 查询
                                                                            $current = $all['postId'];
                                                                            $sql = "SELECT COUNT(*) as count FROM comments WHERE `postId` = $current";
                                                                            $stmt = $db->prepare($sql);
                                                                            $stmt->execute();

                                                                            // 解析查询结果
                                                                            $count = $stmt->fetchColumn();
                                                                            echo $count;

                                                                            // 关闭数据库连接
                                                                            // $db = null;?> Comment
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        
                                                        </footer>
                                                        
                                                    </div><?php }?>
                                                
                                                <!-- Post Ends -->
                                            </div>
                                                <!-- </div> -->
                                    </div>

                                        <!-- </div> -->

                                    <div class="videoBlock rela-block" style="display:none;">
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
                                        <!-- VIDEO 內容 -->
                                    </div>
                                </div>
                                <!-- <div class="rela-inline button more-images" onclick="add_images(); inf_scroll = true;">More Images</div> -->
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
    <script src=”js/scroll_like.js”></script>

</body>

</html>

<script>
       var video = <?=json_encode($rows)?>;
        // console.log(video);
        var apiKeyT = "ccfc6902624f5980de6bc284bd7b85e3";
        var videoList = document.querySelector(".videoBlock");
        var post = document.querySelector(".postBlock");
        var test = document.getElementById("test");
        var videoComment = <?php echo json_encode($videoComment); ?>;
        console.log(videoComment);

        var i = 0;

        function showContent(contentType) {
            var postBlock = document.querySelector('.postBlock');
            var videoBlock = document.querySelector('.videoBlock');

            if (contentType === 'post') {
                postBlock.style.display = 'block';
                videoBlock.style.display = 'none';
            } else if (contentType === 'video') {
                postBlock.style.display = 'none';
                videoBlock.style.display = 'flex';
            }
        }
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
                
                var edit = document.createElement("div");
                edit.classList.add("edit");

                var comment = document.createElement("div");
                comment.classList.add("comment");

                main.classList.add("videoDetail");


                if(i === 0){
                    var editButton = document.createElement("a");
                    editButton.setAttribute("href", "blank.php");
                    editButton.classList.add("editButton");
                    editButton.innerHTML = "edit";
                    videoList.appendChild(editButton);
                    console.log("flag");
                }
                
    
                comment.innerHTML = videoComment[i];
                i++;

                var movieTitle = document.createElement("div");
                var img =  document.createElement("img");
                movieTitle.innerHTML = title;   
                img.src = "https://image.tmdb.org/t/p/w92/" + poster;

                // main.appendChild(movieTitle);
                main.appendChild(img);
                edit.appendChild(movieTitle);
                edit.appendChild(comment);

                videoArea.appendChild(main);
                videoArea.appendChild(edit);

                videoList.appendChild(videoArea);
                console.log(videoList);

            });
        }).catch(function(error) {
        console.log(error);
        });

        

    </script>