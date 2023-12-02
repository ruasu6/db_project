<?php
    session_start();
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
	<!-- <link rel="stylesheet" href="loadmore.css"> -->

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
                <!-- <div class="container-fluid" > -->
                        <!-- Your code! -->
                    <!-- Page Heading -->
                    <div class="container-fluid my-5"style = "width : calc(100% - 160px)">
					<?php $usersId = $_SESSION['userid'];?>
						<!-- <div class="row"> -->
							<div class="">
								<?php
								$db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
								if(isset($_GET['post_id'])){
									$id=$_GET['post_id'];
									$max="SET global max_allowed_packet=33554432";
									$db->exec($max);
								}
								if (isset($_POST['create'])) {						
									if(isset($_SESSION['userid'])){
										//echo "Welcome back" ;
										//獲取使用者ID
										$usersId = $_SESSION['userid'];
										$content = $_POST['content'];
									}
									$row = $db->query("SELECT * FROM `users` WHERE `usersId`=$usersId");
									foreach($row as $name){
									}
									$na = $name['usersName'];
									$sql = "INSERT INTO `posts` (`post_content`, `usersId`, `usersName`) VALUES ('$content', '$usersId', '$na')";
									$result = $db->exec($sql);
									if ($result === false) {
										echo "Error: " . $db->errorInfo()[2];
									} else {
										//echo "Post created successfully.";
										unset($_POST['create']);
										unset($_POST['content']);
									}
								}
								?>
				
								<!--- Post Form Begins -->
								
								<section class="card" >
									<div class="card-header">
										<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" id="posts-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="true">Make
													a Post</a>
											</li>
										</ul>
									</div>

									<div class="card-body">
										<form action="" method="post" enctype="multipart/form-data">
											<div class="tab-content" id="myTabContent">
												<div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
													<div class="form-group">
														<label class="sr-only" for="message">post</label>
														<textarea class="form-control" id="message" name="content" rows="3" placeholder="What are you thinking..."></textarea>
													</div>
												</div>
											</div>
											
											<div class="text-right">
												<button type="submit" value="create" name="create" class="btn btn-primary">share</button>
											</div>
										</form>
									</div>
								</section>
								
								<!-- Post Begins -->

								<section >
									<?php
									$myFollow = $db->query("SELECT postId, usersId, usersName, post_content, post_date, likes
									FROM (
										SELECT postId, usersId, usersName, post_content, post_date, likes
										FROM `posts`
										WHERE `usersId` = $usersId
										UNION
										SELECT p.postId, p.usersId, u.usersName, p.post_content, p.post_date, p.likes
										FROM `follows` AS f
										INNER JOIN `posts` AS p ON p.usersId = f.followedUserId
										INNER JOIN `users` AS u ON u.usersId = p.usersId
										WHERE f.currentUserId = $usersId
									) AS allposts
									-- WHERE usersId <> $usersId
									ORDER BY post_date DESC");
			
									foreach ($myFollow as $all) {
										// 印出貼文的相关信息
										//echo $all['post_content'];
								
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
													<img class='profile-pic'  src= "data:image/png;base64,<?php echo base64_encode($a['usersIMG']);?>" width='40' height='40' object-fit: cover alt='...'>
												</a><?php }?>
											</div>
										<div class="flex-grow-1 pl-2">
											<a class="text-decoration-none text-capitalize h6 m-0" href="http://localhost/db_project/userpage.php?varname=<?php echo $a['usersId'] ?>#"><?php echo $all["usersName"]?></a>
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
															<a class="small text-decoration-none"type="submit" name="like" href="#">
																<input type="hidden" name="post_id" value=<?php echo $all['postId']?>>
																<?php
																	$usersId = $_SESSION['userid'];

																	
																		$db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
																		$p = $all['postId'];
																		$row2 = $db->query("SELECT * FROM `likes` WHERE `usersId`=$usersId AND `postId`= $p");
																		$hasLiked = $row2->rowCount()
																	
																	?>

																	<button onclick="saveScrollPosition()" type="submit" name="like" style="background-color: transparent; border: none; color: <?php echo $hasLiked ? '#1c145d' : '#4e73df'; ?>; cursor: pointer; <?php echo $hasLiked ? 'font-weight:bold' : ''; ?>">
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
								</section>


								<!-- <div class="form-box">
									<button id="loadMoreTrigger" style="border-radius: 30px; background-color: #4e73df; color: white; padding: 10px 20px; border: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);">Load More</button>
								</div> -->
								
								<!-- Post Ends -->
							</div>
						<!-- </div> -->
					</div>

                <!-- </div> -->
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
	<script src="loadmore.js"></script>
	<script src="scroll_like.js"></script>
</body>

</html>