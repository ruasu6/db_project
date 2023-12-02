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
 
      if($row['id']==$var && $_SESSION['mylogin'] == true ){ ?>
          <a href="edit.php?id=<?= $row['id'] ?>"class="editbtn_1">edit</a>
  <?php
      }   
?>
    {
      "link":"<?php echo $row['usersUrl'] ?>",
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