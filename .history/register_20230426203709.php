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

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form action="includes/signup.inc.php" method="post" class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <!-- Username -->
                                    <input type="text" name="name" class="form-control form-control-user" id="exampleUserName"
                                        placeholder="Username">
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- UserUid -->
                                        <input type="text" name="uid" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="User Id">
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Email Address">
                                </div>
                                <!-- Password / Repeat Password -->
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="pwd" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="pwdrepeat" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <!-- Submit -->
                                <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                                <!-- <a href="login.php" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </a> -->
                                <hr>
                                <a href="index.php" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.php" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.php">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
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

    <?php
        // if (isset($_GET["error"])){
        //     if($_GET["error"] == "emptyinput"){
        //         echo "<script>alert('Please fill all fields!')</script>";
        //     }
        //     else if ($_GET["error"] == "invaliduid"){
        //         echo "<script>alert('Username only contains alphabets and numbers!')</script>";
        //     }
        //     else if ($_GET["error"] == "invalidemail"){
        //         echo "<script>alert('Please input correct email!')</script>";
        //     }
        //     else if ($_GET["error"] == "passwordsdontmatch"){
        //         echo "<script>alert('Password doesnt match')</script>";
        //     }
        //     else if ($_GET["error"] == "usernametaken"){
        //         echo "<script>alert('Username exists, please change.')</script>";
        //     }
        //     else if ($_GET["error"] == "stmtfailed"){
        //         echo "<script>alert('Something wrong, try again!')</script>";
        //     }
            
        // }
    ?>
    <?php
// 建立與資料庫的連線
$db = new mysqli('localhost', 'root', '', 'video_db');

// 檢查連線是否成功
if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// 讀取 users 資料表中的所有使用者資料
$sql_read = "SELECT * FROM users";
$result = $db->query($sql_read);

// 逐筆處理使用者資料
while ($row = $result->fetch_assoc()) {
    // 設定 URL
    $uid = $users['userId'];
    $url = "edit.php?id=" . $uid;
    
    // 更新 usersUrl 欄位
    $update_sql = "UPDATE users SET usersUrl='$url' WHERE userId=" = '$ui';
    $db->query($update_sql);
}

// 關閉資料庫連線
$db->close();
?>

</body>

</html>

