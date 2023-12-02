<?php
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
        
        $uname = $row["usersName"];
        $img = $row["usersIMG"];
    } else {
        echo "查無此使用者";
    }
?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>

<!-- Topbar Search -->
<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class="input-group">
        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
            aria-label="Search" aria-describedby="basic-addon2" onkeyup="searchUsers(this.value)">
        <div class="input-group-append">
            <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
            </button>
        </div>
    </div>
</form>

<div id="searchResults" class=" dropdown-menu" style="left:30px;min-width: 23rem;" aria-labelledby="dropdownMenuButton"></div>

<script>
    function searchUsers(query) {
        // 創建XMLHttpRequest對象
        var xhttp = new XMLHttpRequest();

        // 設定回應接收完成的處理函式
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                // 解析回應的JSON數據
                var results = JSON.parse(this.responseText);

                // 獲取搜尋結果顯示區域的元素
                var searchResults = document.getElementById("searchResults");

                // 清空之前的內容
                searchResults.innerHTML = "";

                // 如果有搜尋結果，則建立下拉式選單的選項
                if (results.length > 0) {
                    for (var i = 0; i < results.length; i++) {
                    var option = document.createElement("a");
                    option.classList.add("dropdown-item");

                    // 创建容器元素来包裹图像和文本
                    var container = document.createElement("div");
                    container.style.display = "flex";
                    container.style.alignItems = "center";

                    // 创建图像元素并设置样式
                    var image = document.createElement("img");
                    image.src = "data:image/jpeg;base64," + results[i].img;
                    image.style.width = "33px";
                    image.style.height = "33px";
                    image.style.borderRadius = "50%";
                    image.style.backgroundColor = "lightgray";
                    image.style.marginRight = "5px";
                    image.style.marginTop = "5px";
                    image.style.marginBottom = "5px";

                    // 创建容器元素来包裹文本
                    var textContainer = document.createElement("div");
                    textContainer.style.display = "flex";
                    textContainer.style.flexDirection = "column";

                    // 创建文本元素并设置样式
                    var label = document.createElement("span");
                    label.textContent = results[i].label;
                    label.style.marginLeft = "10px";
                    label.style.color = "black";
                    label.style.fontWeight = "bold";

                    var name = document.createElement("span");
                    name.textContent = results[i].name;
                    name.style.marginLeft = "10px";
                    name.style.color = "grey";
                    name.style.fontSize = "8px";


                    // 将图像和文本元素添加到容器中
                    container.appendChild(image);
                    textContainer.appendChild(label);
                    textContainer.appendChild(name);
                    container.appendChild(textContainer);

                    // 创建链接并设置链接属性
                    var link = document.createElement("a");
                    link.href = results[i].url;

                    // 将容器添加到链接中
                    link.appendChild(container);

                    // 鼠标悬停时设置链接样式
                    link.addEventListener("mouseover", function() {
                        this.style.backgroundColor = "lightblue";
                        this.style.color = "white";
                        this.style.textDecoration = "none";
                    });

                    // 鼠标离开时恢复默认样式
                    link.addEventListener("mouseout", function() {
                        this.style.backgroundColor = "";
                        this.style.color = "";
                        this.style.textDecoration = "";
                    });

                    // 将链接添加到选项中
                    option.appendChild(link);

                    // 将选项添加到搜索结果显示区域
                    searchResults.appendChild(option);
                }


                searchResults.style.display = "block";
            } else {
                searchResults.style.display = "none";
            }

            }
        };

        // 發送請求到後端處理程式（例如search_user.php）
        xhttp.open("GET", "search_user.php?query=" + query, true);
        xhttp.send();
    }
    // 在文檔加載完成後綁定點擊事件監聽器
    document.addEventListener("click", function(event) {
        var searchResults = document.getElementById("searchResults");
        var targetElement = event.target;

        // 檢查點擊的目標元素是否位於搜尋結果區域以外
        if (!searchResults.contains(targetElement)) {
            // 隱藏搜尋結果
            searchResults.style.display = "none";
        }
    });

</script>

<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
            aria-labelledby="searchDropdown">
            <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small"
                        placeholder="Search for..." aria-label="Search"
                        aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </li>

    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter - Alerts -->
            <span class="badge badge-counter" style="right: -10px; color: red;">NEW</span>
        </a>
        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">
                Alerts Center
            </h6>
            <div id = "mes"></div>
            <!--  -->
            <a class="dropdown-item text-center small text-gray-500" id="loadMoreTrigger1" >Read More Messages</a>
        </div>
        <!-- href="message.php" -->
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            
            <?php
                if (isset($_SESSION['userid'])) {
            ?>
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php echo $uname;?>
                </span>
            <?php
                }
            ?>
            <?php    
                // 將二進制數據轉換為 base64 字串
                $img_src = "data:image/png;base64," . base64_encode($img);
            ?>
        <style>
        .bg_img {
            background: url(<?php echo $img_src; ?>) center/cover no-repeat;
            height: 2rem;
            width: 2rem;
            border-radius: 50%;
        }
        </style>
            <div class="img-profile rounded-circle bg_img"></div>
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <a class="dropdown-item" href="profile.php">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profile
            </a>
            <a class="dropdown-item" href="user_info.php">
                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                Settings
            </a>
            <a class="dropdown-item" href="index.php">
                <i class="fas fa fa-chart-area fa-sm fa-fw mr-2 text-gray-400"></i>
                Chart
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
            <?php
                // if (!empty($_SESSION['mylogin'])) {
                 // print $_SESSION["userid"];
                    // echo "<a href='./includes/logout.inc.php'></i></a>";
                // }
                // else{
                 // print $_SESSION["userid"];
                //  echo "<a href='login.php'></i></a>";
                // }
                // print $_SESSION["userid"];
            ?>
        </div>
    </li>
</ul>
</nav>
<!-- <script src="js/mes_data.js"></script> -->
<!-- Topbar -->