<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
	$var = $_GET['varname'];
?>
<?php
    $db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
    $row = $db->query("SELECT * FROM `posts` WHERE `postId`=$var");
    foreach ($row as $post){
    } 
    $id = $post['usersId'];
    $name = $db->query("SELECT * FROM `users` WHERE `usersId`=$id");
    foreach ($name as $writername){
    }

    //刪除文章
    if(isset($_POST["delete"])){
        // 刪除 likes 表中的記錄
        $del_likes = "DELETE FROM `likes` WHERE postId = '$var'";
        $db->exec($del_likes); 

        $del_comments = "DELETE FROM `comments` WHERE postId = '$var'";
        $db->exec($del_comments); 

        // 刪除 posts 表中的記錄
        $del_posts = "DELETE FROM `posts` WHERE postId = '$var'";
        $db->exec($del_posts);
        header("Location:post.php");       
    }

    //刪除留言
    if(isset($_POST["deletecomment"])){
        $comment_id = $_POST['comment_id'];
        $del_comment = "DELETE FROM `comments` WHERE comment_id = '$comment_id'";
        $db->exec($del_comment);
        header("Location:");       
    }

    //新增留言
    if (isset($_POST['commentbtn'])) {						
        if(isset($_SESSION['userid'])){
            //echo "Welcome back" ;
            //獲取使用者ID
            $usersId = $_SESSION['userid'];
            $com = $_POST['createcomment'];
        }
        $sqlcom = "INSERT INTO `comments` (`postId`, `usersId`, `comment_content`) VALUES ('$var', '$usersId', '$com')";
        $result = $db->exec($sqlcom);
        if ($result === false) {
            echo "Error: " . $db->errorInfo()[2];
        } else {
            //echo "Post created successfully.";
        }
    }
?>
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
                <div class="container-fluid"style = "width : calc(100% - 160px)">
                <section class="card mt-4">
					<div class="border p-2">
						<!-- post header -->
						<div class="row m-0">
							<div class="">
                            <a class="text-decoration-none" href="http://localhost/db_project/userpage.php?varname=<?php echo $writername['usersId'] ?>#">
                                    <!-- <img class='profile-pic'  src=\"data:image;base64," + val.photo + "\"/ width='40' height='40' object-fit: 'cover' alt='...'> -->
                                    <img class='profile-pic'  src= "data:image/png;base64,<?php echo base64_encode($writername['usersIMG']);?>" width='40' height='40' object-fit: cover alt='...'>
                                </a><?php ?>
							</div>
							<div class="flex-grow-1 pl-2">
								<a class="text-decoration-none" href="http://localhost/db_project/userpage.php?varname=<?php echo $writername['usersId'] ?>#">
									<h2 class="text-capitalize h5 mb-0"><?php 
                                    echo $writername['usersName'];
                                    
                            ?>
                                    
                                </h2>
								</a> 
								<p class="small text-secondary m-0 mt-1"><?php echo $post['post_date']?></p>
							</div>
                            
							<?php $usersId = $_SESSION['userid'];
                            if ($writername['usersId']==$usersId){ ?>
                                <div class="dropdown">
                                    <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-chevron-down"></i>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item text-primary" href="edit.php?varname=<?php echo $var?>">Edit</a>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <button class="dropdown-item text-primary" name = "delete">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
						</div>
						<!-- post body -->
						<div class="">
							<p class="my-2">
								<?php 
                                    echo $post['post_content'];
                                ?>
							</p>
						</div>
						<hr class="my-1">
						<!-- post footer begins -->
						<footer class="">
							<!-- post actions -->
							<div class="">
								<ul class="list-group list-group-horizontal">
									<li class="list-group-item flex-fill text-center p-0 px-lg-2 border border-0">
                                        <form method="POST" action="like.php">
                                            <a class="small text-decoration-none"type="submit" name="like" href="#">
                                                <input type="hidden" name="post_id" value=<?php echo $post['postId']?>>
                                                <?php
                                                    $usersId = $_SESSION['userid'];

                                                    function liked($postId, $usersId) {
                                                        $db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
                                                        $row2 = $db->query("SELECT * FROM `likes` WHERE `usersId`=$usersId AND `postId`=$postId");
                                                        return $row2->rowCount() > 0;
                                                    }

                                                    $hasLiked = liked($post['postId'], $usersId);
                                                    ?>

                                                    <button type="submit" name="like" style="background-color: transparent; border: none; color: <?php echo $hasLiked ? '#1c145d' : '#4e73df'; ?>; cursor: pointer; <?php echo $hasLiked ? 'font-weight:bold' : ''; ?>">
                                                        <i class="far fa-thumbs-up"></i><?php echo " ".$post['likes']." " ?> Like
                                                    </button>

                                            </a>
                                        </form>
									</li>
									<li class="list-group-item flex-fill text-center p-0 px-lg-2 border border-right-0 border-top-0 border-bottom-0">
                                    
										<a class="small text-decoration-none" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
											<i class="fas fa-comment-alt"></i> 
                                            <?php
                                            // 执行 SQL 查询
                                            $sql = "SELECT COUNT(*) as count FROM comments WHERE `postId` = $var";
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
							

							<!-- collapsed comments begins -->
							<div class="collapse" id="collapseExample">
								<div class="card border border-right-0 border-left-0 border-bottom-0 mt-1">
									<!-- new comment form -->
									<section class="mt-3">
                                        <form action="" method="post" enctype="multipart/form-data">
											<div class="input-group input-group">
												<input type="text" class="form-control" name="createcomment" placeholder="Write Comment" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button class="text-decoration-none text-white btn btn-primary" type="submit" value="create" name="commentbtn" class="btn btn-primary">Comment</button>
                                                    </div>
											</div>
										</form>
									</section>
									<!-- comment card bgins -->
									<section>
                                        <?php
                                        $comment = $db->query("SELECT * FROM `comments` WHERE `postId`=$var");
                                        foreach ($comment as $post_comment){
                                            $co = $post_comment["usersId"];
                                            $commentname = $db->query("SELECT * FROM `users` WHERE `usersId`= $co");
                                            foreach($commentname as $cname){
                                        ?>
										<div class="card p-2 mt-3">
											<!-- comment header -->
											<div class="d-flex">
												<div class="">
													<a class="text-decoration-none" href="http://localhost/db_project/userpage.php?varname=<?php echo $cname['usersId'] ?>#">
                                                        <img class='profile-pic'  src= "data:image/png;base64,<?php echo base64_encode($cname['usersIMG']);?>" width='40' height='40' object-fit: cover alt='...'>
													</a>
												</div>
												<div class="flex-grow-1 pl-2">
													<a class="text-decoration-none text-capitalize h6 m-0" href="http://localhost/db_project/userpage.php?varname=<?php echo $cname['usersId'] ?>#"><?php echo $cname["usersName"]?></a>
													<p class="small m-0 text-muted"><?php echo $post_comment["comment_date"]?></p>
												</div>
												<div >
                                                    <?php if ($post_comment['usersId']==$usersId) { ?>
													<div class="dropdown">
														<a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="fas fa-chevron-down"></i>
														</a>
                                                        

														<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
															<form action="" method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="comment_id" value=<?php echo $post_comment['comment_id']?>>
                                                                <button class="dropdown-item text-primary" name = "deletecomment">Delete</button>
                                                            </form>
														</div>
													</div><?php }?>
												</div>
											</div>
											<!-- comment header -->
											<!-- comment body -->
                                            
											<div class="card-body p-0">
												<p class="card-text h7 mb-1"><?php echo $post_comment["comment_content"]?></p>
											</div>
										</div><?php }}?>
									</section>
									<!-- comment card ends -->

								</div>
							</div>
							<!-- collapsed comments ends -->
						</footer>
						<!-- post footer ends -->
					</div>
				</section>
                    <!-- Page Heading -->
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
    <script src="loadmore_comment.js"></script>

</body>

</html>