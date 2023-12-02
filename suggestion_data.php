<?php
 session_start();
$userid = $_SESSION['userid'];
?>
<?php
  $db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
  $row = $db->query("SELECT * FROM `users` WHERE `usersId`!=$userid AND `usersId` NOT IN (SELECT `followedUserId` FROM `follows` WHERE `currentUserId` = $userid);");

//   $row = $db->query("SELECT * FROM `users`WHERE ");
  $num = $row->rowCount()
?>

{
  "results": [
<?php
    $i = 0;
    
    
    foreach ($row as $row) {
        $list1 = array();
        $list2 = array();
        $id = $row['usersId'];
    
        $sql1 = "SELECT DISTINCT `vId` FROM `video` WHERE `usersId` = $userid";
        $result1 = $db->query($sql1);
    
        // 檢查查詢結果是否為空
        $rows1 = $result1->fetchAll();
        if (count($rows1) > 0) {
            // 將資料加入陣列
            foreach ($rows1 as $row11) {
                $list1[] = $row11["vId"];
            }
        }
    
        $sql2 = "SELECT DISTINCT`vId` FROM `video` WHERE `usersId` = $id";
        $result2 = $db->query($sql2);
    
        // 檢查查詢結果是否為空
        $rows2 = $result2->fetchAll();
        if (count($rows2) > 0) {
            // 將資料加入陣列
            foreach ($rows2 as $row22) {
                $list2[] = $row22["vId"];
            }
        }
    
        $similarity = jaccard_similarity($list1, $list2);

        if (1) {?>
        {
        "link": "<?php echo "http://localhost/db_project/userpage.php?varname=$id#" ?>",
        "img":"<?php echo base64_encode($row['usersIMG']) ?>",
        "name":"<?php echo($row['usersName'])?>",
        "uid":"<?php echo($row['usersUid']) ?>",
        "similarity":"<?php echo $similarity?>"
        }
        <?php } $i = $i+1?>
    <?php
        if($i < ($num)){   
    ?>
    ,
    <?php
        }

  }
?>
  ]
}

<?php
function jaccard_similarity($list1, $list2) {
    $intersection = array_intersect($list1, $list2);
    $union = array_unique(array_merge($list1, $list2));
    $similarity = count($intersection) / count($union);
    return $similarity;
}
?>