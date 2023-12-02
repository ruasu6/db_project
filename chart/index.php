<?php
    session_start();
    $userid = $_SESSION['userid'];
    
    $con = new mysqli('localhost', 'root', '', 'video_db');
    $query1 = $con->query("SELECT COALESCE(COUNT(DISTINCT v.vId), 0) AS count, u.usersId, t.vType
                            FROM (SELECT DISTINCT vType FROM video) t
                            LEFT JOIN video v ON t.vType = v.vType AND v.usersId = '$userid'
                            CROSS JOIN (SELECT DISTINCT usersId FROM video WHERE usersId = '$userid') u
                            GROUP BY u.usersId, t.vType;
                            ");
    // echo $query1;

	foreach($query1 as $data){
		$type[] = $data['vType'];
		$count[] = $data['count'];
	}

    
    $query3 = $con->query("SELECT COUNT(DISTINCT vId) AS count, users.usersUid, video.usersId 
                            FROM `video`
                            JOIN users ON video.usersId = users.usersId
                            GROUP BY video.usersId 
                            HAVING video.usersId = '$userid' OR count = (
                                SELECT max(count) FROM (
                                    SELECT COUNT(DISTINCT vId) AS count FROM `video` GROUP BY video.usersId
                                ) AS counts
                            ) 
                            ORDER BY count ASC;
                            ");
    foreach ($query3 as $data) {
        $countTotal[] = $data['count'];
        $countId[] = $data['usersUid'];
    }

    $query5 = $con->query("SELECT genreName, COUNT(*) as countGenre
                            FROM (
                            SELECT tg.Tname AS genreName
                            FROM video
                            JOIN tvgenres tg ON video.vGenre = tg.tId AND vType = 'tv'
                            WHERE usersId = '$userid'
                            
                            UNION ALL
                            
                            SELECT mg.mName AS genreName
                            FROM video
                            JOIN moviegenres mg ON video.vGenre = mg.mId AND vType = 'movie'
                            WHERE usersId = '$userid'
                            ) as subquery
                            GROUP BY genreName;
    ");

        foreach($query5 as $data){
            $genreName[] = $data['genreName'];
            $countGenre[] = $data['countGenre'];
        }
    
    $queryFan = $con->query("SELECT 
                                COUNT(CASE WHEN f2.currentUserId IS NOT NULL THEN 1 END) AS mutual_count,
                                COUNT(CASE WHEN f2.currentUserId IS NULL THEN 1 END) AS not_mutual_count
                            FROM follows f1
                            LEFT JOIN follows f2 ON f1.followedUserId = f2.currentUserId AND f2.followedUserId = f1.currentUserId
                            WHERE f1.currentUserId = '$userid';");
    foreach($queryFan as $data){
        $mutual_count[] = $data['mutual_count'];
        $not_mutual_count[] = $data['not_mutual_count'];
    }
    

    $query4 = $con->query("SELECT 
                                t.month,
                                SUM(t.num_movies) as num_movies,
                                SUM(t.num_tv) as num_tv
                            FROM 
                                (
                                SELECT 
                                    months.month,
                                    COUNT(DISTINCT CASE WHEN video.vType = 'movie' THEN video.vId END) as num_movies,
                                    COUNT(DISTINCT CASE WHEN video.vType = 'tv' THEN video.vId END) as num_tv
                                FROM 
                                    months 
                                    LEFT JOIN video ON months.month = MONTH(video.vTime) AND video.usersId = '$userid'
                                GROUP BY 
                                    months.month, 
                                    video.vType
                                ) t
                            GROUP BY 
                                t.month;");
    foreach($query4 as $data){
		$num_movies[] = $data['num_movies'];
		$num_tv[] = $data['num_tv'];
        // $vType[] = $data['vType'];
        $month[] = $data['month'];
	}

    $queryvideo = $con->query("SELECT vId, vType,COUNT(*) AS count
                            FROM (
                            SELECT DISTINCT vId, usersId, vType
                            FROM video
                            ) AS tmp
                            WHERE vId IN (
                            SELECT vId
                            FROM (
                                SELECT vId, COUNT(DISTINCT usersId) AS num_users
                                FROM video
                                GROUP BY vId
                                ORDER BY num_users DESC
                                LIMIT 3
                            ) AS top_videos
                            )
                            GROUP BY vId
                            ORDER BY count DESC;");


    
        $rows = array();
        while ($row = mysqli_fetch_assoc($queryvideo)) {
            $rows[] = $row;
        } 
    // $data = $querypost->fetchAll(PDO::FETCH_ASSOC);
    // $month = array_column($data, 'month');
    // $num_post = array_column($data, 'num_post');

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

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="css/index.css">
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
                <!-- Topbar -->
                
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="video">
                        <!-- <div class="watchTitle">大家都在看..</div> -->
                        <!-- <div class="videolist"></div> -->
                    </div>
                    <!-- <canvas id="myChart1"></canvas>
                    <canvas id="myChart2"></canvas> -->
                    <div class="chart">
                        <div id="piechart" style="width: 350px;"></div>
                        <div id="fanschart" style="width: 370px;"></div>
                        <div id="moviechart" style="width: 350px; "></div>
                    </div>
                    <div class="chart">
                        <div class="countBlock">
                                <div id="countchart" style="width: 350px; height: 330px;"></div>
                                <!-- <div id = "award"></div> -->
                        </div>
                        <!-- <div id="postchart" style="width: 700px; height: 280px;"></div> -->
                        <div id="totalchart"></div>
                    </div>
                    
                    
                    
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

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script>
         <?php if(count($rows) === 0){ ?>

<?php } ?>
var video = <?=json_encode($rows)?>;
// console.log(video);
var apiKeyT = "ccfc6902624f5980de6bc284bd7b85e3";
var videoBlock = document.querySelector(".video");
var i = 1;
var videoList = document.createElement("div");
videoList.classList.add("videolist");

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
        

        var watchTitle = document.createElement("div");
        watchTitle.innerHTML = "大家都在看.."
        watchTitle.classList.add("watchTitle");

        var videoArea = document.createElement("div");
        videoArea.classList.add("videoArea");

        // var main = document.createElement("div");
        var title = item.name || item.title;
        var poster = item.poster_path;
        var type = item.media_type;
        var id = item.id;

        if(i === 1){
        }
        

        
        var movieTitle = document.createElement("div");
        var img =  document.createElement("img");
        var order = document.createElement("span");
        order.classList.add("order");
        order.innerHTML = i;
        console.log(i);
        i++;
        
        movieTitle.innerHTML = title;
        movieTitle.classList.add("title");  
        img.src = "https://image.tmdb.org/t/p/w154/" + poster;

        videoArea.appendChild(movieTitle);
        videoArea.appendChild(order);
        videoArea.appendChild(img);

        // main.appendChild(movieTitle);

        // videoArea.appendChild(main);
        // videoArea.appendChild(edit);

        videoList.appendChild(videoArea);
        console.log(videoList);

        if(i === 3){
            videoBlock.appendChild(watchTitle);
            videoBlock.appendChild(videoList);
        }

    });
}).catch(function(error) {
console.log(error);
});
    </script>
    <script>
       
    // tv and movie doughnut chart
    function getRandomColor(numColors) {
        const colors = [];
        const baseColor = [71, 117, 174];
        for (let i = 0; i < numColors; i++) {
            const r = baseColor[0] + i * 10;
            const g = baseColor[1] + i * 10;
            const b = baseColor[2] ;
            colors.push(`RGB(${r}, ${g}, ${b})`);
        }
        return colors;
    }
      // 載入 Google Charts
    google.charts.load('current', {'packages':['corechart']});

    google.charts.setOnLoadCallback(function() {
        drawChart1();
        drawChart2();
        drawChart3();
        drawChart4();
        drawChartFan();
        drawChartPost();
    });
    
  // 定義 drawChart 函數
  function drawChart1() {
    // 將 PHP 變數 $type 和 $count 傳遞給 JavaScript
    var type = <?php echo json_encode($type); ?>;
    var count = <?php echo json_encode($count); ?>;

    // 將數據轉換為 Google Charts 所需的數據格式
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Type');
    data.addColumn('number', 'Count');
    for (var i = 0; i < type.length; i++) {
      data.addRow([type[i], parseInt(count[i])]);
    }


    // 設置圓餅圖的選項
    var options = {
      title: '',
      is3D: false,
      width: 350,
      height: 230,
      backgroundColor: 'transparent',
      legend: {marker: {shape: 'circle', size: 8},
               position: 'right',
               alignment: 'center'
            },
      colors: ['#4775ae', '#859cb7'],
      pieSliceText: 'none'
    };

    
    // 在指定的 DIV 元素中繪製圓餅圖
    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);

    
  }

  function drawChartFan() {
    var mutual_count = <?= json_encode($mutual_count); ?>;
    var not_mutual_count = <?= json_encode($not_mutual_count); ?>;

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Followers');
    data.addColumn('number', 'Count');
    data.addRows([
    ['follow you', parseInt(mutual_count)],
    ['unfollow', parseInt(not_mutual_count)]
    ]);



    // 設置圓餅圖的選項
    var options = {
      title: '',
      is3D: false,
      width: 350,
      height: 230,
      backgroundColor: 'transparent',
      legend: {marker: {shape: 'circle', size: 8},
               position: 'right',
               alignment: 'center'
            },
      colors: ['#6f9dae', '#8dbbae'],
      pieSliceText: 'none'
    };

    
    // 在指定的 DIV 元素中繪製圓餅圖
    var chart = new google.visualization.PieChart(document.getElementById('fanschart'));
    chart.draw(data, options);

    
  }

  function drawChart2() {
 
    var countTotal = <?php echo json_encode($countTotal); ?>;
    var countId = <?php echo json_encode($countId); ?>;

    if (countId.length === 1){
        var countBlock = document.querySelector('.countBlock');
        var award = document.createElement("div");
        award.setAttribute("id", "award");
        award.innerHTML = "You watch the most videos in SERENDIPITY!";
        countBlock.appendChild(award);
    }

    var countData = google.visualization.arrayToDataTable([
          ['UserId', 'View'],
          <?php
            for ($i = 0; $i < count($countId); $i++) {
              echo "['" . $countId[$i] . "', " . $countTotal[$i] . "],";
            }
          ?>
    ]);
    var countOptions = {
        title: '',
        legend: { position: 'top' },
        vAxis: { title: 'Views' },
        // hAxis: { title: 'id', format: 'MMM' },
        isStacked: true,
        width: 350,
        height: 330,
        backgroundColor: 'transparent',
        series: {
                    0: { color: '#b48ead' }, // Num Movies
                    1: { color: '#5783a6' }  // Num TV
                },
        bar: {width: 30},
        hAxis: { title: 'UserID', format: 'MMM' },
    };
    var chart2 = new google.visualization.ColumnChart(document.getElementById('countchart'));
    chart2.draw(countData, countOptions);
  }

  function drawChart3() {

        var genreName = <?php echo json_encode($genreName); ?>;
        var countGenre = <?php echo json_encode($countGenre); ?>;

    var MovieData = new google.visualization.DataTable();
    MovieData.addColumn('string', 'genreName');
    MovieData.addColumn('number', 'countGenre');
    for (var i = 0; i < genreName.length; i++) {
      MovieData.addRow([genreName[i], parseInt(countGenre[i])]);
    }
    var optionsMovie = {
      title: '',
      is3D: false,
      width: 320,
      height: 230,
      backgroundColor: 'transparent',
      legend: {marker: {shape: 'circle', size: 8},
               position: 'right',
               alignment: 'center'
            },
      colors: getRandomColor(<?=count($genreName)?>),
      pieSliceText: 'none'
    };
    var chart3 = new google.visualization.PieChart(document.getElementById('moviechart'));
    chart3.draw(MovieData, optionsMovie);
  }

  function drawChart4() {
        var totalData = google.visualization.arrayToDataTable([
            ['Month', 'Movies', 'TV'],
            <?php
                for ($i = 0; $i < count($month); $i++) {
                echo "['" . $month[$i] . "', " . $num_movies[$i] . ", " . $num_tv[$i] . "],";
                }
            ?>
        ]);
        var totalOptions = {
            title: '',
            legend: { position: 'top' },
            vAxis: { title: 'Views' },
            hAxis: { title: 'Month', format: 'MMM' },
            isStacked: true,
            width: 700,
            height: 330,
            backgroundColor: 'transparent',
            series: {
                        0: { color: '#b48ead' }, // Num Movies
                        1: { color: '#cfbfcc' }  // Num TV
                    },
        };
        var chart4 = new google.visualization.ColumnChart(document.getElementById('totalchart'));
        chart4.draw(totalData, totalOptions);
        
    }

    
	
</script> 


</body>

</html>

