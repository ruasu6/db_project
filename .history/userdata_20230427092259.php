<?php
  $db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
  $row = $db->query("SELECT * FROM `users`");
  $num = $row->rowCount()
?>
{
  "results": [
<?php
    $i = 0;
  foreach ($row as $row){
?>
    {
      "link": "<?php echo "http://localhost/startbootstrap-sb-admin-2-gh-pages/userpage.php?varname=$row#" ?>",
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