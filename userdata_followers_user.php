<?php
  session_start();
  $db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
  $uid = $_SESSION['userid'];
  $var = $_GET['varname'];
  $row = $db->query(" SELECT *
                      FROM users
                      JOIN follows ON users.usersId = follows.currentUserId
                      WHERE follows.followedUserId = $var");
  $num = $row->rowCount()
?>
{
  "results": [
<?php
    $i = 0;
  foreach ($row as $row){
    $id = $row['usersId'];
?>
    {
      "link": "<?php echo "http://localhost/db_project/userpage.php?varname=$id#" ?>",
      "img":"<?php echo base64_encode($row['usersIMG']) ?>",
      "name":"<?php echo($row['usersName'])?>",
      "uid":"<?php echo($row['usersUid']) ?>"
    }
    <?php $i = $i+1?>
<?php
// echo ($num);
// echo ($i);
  
    if($i < ($num)){
        
?>
,
<?php
    }

  }
?>
  ]
}