<?php
			$con = new mysqli('localhost', 'root', '', 'video_db');

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

?>

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
<body>
<div class="chart">
                        <div class="countBlock">
                                <div id="countchart" style="width: 350px; height: 280px;"></div>
                                <div id = "award"></div>
                        </div>
                        <div id="postchart" style="width: 700px; height: 280px;"></div>
                    </div>
					<div class="video">
                        <!-- <div class="watchTitle">大家都在看..</div> -->
                        <!-- <div class="videolist"></div> -->
                    </div>
</body>

<script>
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
	google.charts.load('current', {'packages':['corechart']});

	google.charts.setOnLoadCallback(function() {
		// drawChart1();
		// drawChart2();
		// drawChart3();
		// drawChart4();
		// drawChartFan();
		// drawChartPost();
	});

    // function drawChartPost() {
    //     var month = < json_encode($month) ?>;
    //     var post = < json_encode($num_post) ?>;


    //     var data = new google.visualization.DataTable();
    //     data.addColumn('string', 'Month');
    //     data.addColumn('number', 'Post');

    //     for (var i = 0; i < month.length; i++) {
    //         data.addRow([month[i], parseInt(post[i])]);
    //     }

    //     var options = {
    //         title: '',
    //         curveType: 'function',
    //         // legend: { position: 'bottom' }
    //     };

    //     var chart = new google.visualization.LineChart(document.getElementById('postchart'));
    //     chart.draw(data, options);
    // }
</script>
