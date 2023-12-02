<?php
  $db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
  $rows = $db->query("SELECT * FROM `posts` ORDER BY `postId` DESC")->fetchAll(PDO::FETCH_ASSOC);
  $num = count($rows);
?>

{
  "results": [
    <?php
    $i = 0;
    foreach ($rows as $post) {
    $id = $post['postId'];
    // 执行 SQL 查询
    $sql = "SELECT COUNT(*) as count FROM comments WHERE `postId` = $id";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    
    // 解析查询结果
    $count = $stmt->fetchColumn();

    $userid = $post['usersId'];
    $userimg = $db->query("SELECT * FROM `users` WHERE `usersId`=$userid")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($userimg as $a) {
    ?>
      {
        "link": "<?php echo "http://localhost/db_project/postpage.php?varname=$id#" ?>",
        "title": "<?php echo $post['usersName'] ?>",
        "description": "<?php echo $post['post_content'] ?>",
        "time": "<?php echo $post['post_date'] ?>",
        "like": "<?php echo $post['likes'] ?>",
        "count_comment" : "<?php echo $count ?>",
        "postid":"<?php echo $id ?>",
        "photo":"<?php echo base64_encode($a['usersIMG'])?>"
      }
    <?php
      $i = $i + 1;
      if ($i < $num) {
        echo ",";
      }
    }}
    ?>
  ]
}