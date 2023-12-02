<?php
session_start();
$db = new PDO("mysql:dbname=video_db;host=localhost", "root", "");
$userid = $_SESSION['userid'];
$currentUserId = $userid;

function getMatchedUsers($userid, $currentUserId, $db) {
    // 查询目前使用者追踪的人的ID
    $followedQuery = "SELECT `followedUserId` FROM `follows` WHERE `currentUserId` = $currentUserId";
    $followedResult = $db->query($followedQuery);

    // 构建被追踪次数的关联数组
    $followedCounts = array();

    // 检查是否有追踪的人
    if ($followedResult->rowCount() > 0) {
        $followedIds = array(); // 用于记录目前使用者已追踪的人的ID
        while ($row_1 = $followedResult->fetch(PDO::FETCH_ASSOC)) {
            $followedId = $row_1['followedUserId'];
            $followedIds[] = $followedId; // 将已追踪的人的ID添加到数组中
        }

        // 查询追踪此人的其他人
        $suggestedQuery = "SELECT `followedUserId` FROM `follows` WHERE `currentUserId` IN (" . implode(',', $followedIds) . ")";
        $suggestedResult = $db->query($suggestedQuery);

        // 检查是否有建议的使用者
        if ($suggestedResult->rowCount() > 0) {
            while ($suggestedRow = $suggestedResult->fetch(PDO::FETCH_ASSOC)) {
                $suggestedUserId = $suggestedRow['followedUserId'];

                // 排除已追踪的人、自己和目前使用者已追踪的人
                if ($suggestedUserId != $userid && $suggestedUserId != $followedId && !in_array($suggestedUserId, $followedIds)) {
                    // 检查该被追踪的人是否已存在于关联数组中，若存在则增加计数，否则初始化为1
                    if (isset($followedCounts[$suggestedUserId])) {
                        $followedCounts[$suggestedUserId]++;
                    } else {
                        $followedCounts[$suggestedUserId] = 1;
                    }
                }
            }
        }
    }

    // 找出同时被两个以上人追踪的人的ID及被追踪次数
    $matchedUsers = array();
    foreach ($followedCounts as $userId => $count) {
        if ($count >= 2) {
            $matchedUsers[$userId] = $count;
        }
    }
    // 返回结果
    return $matchedUsers;
}



$matchedUsers = getMatchedUsers($userid, $currentUserId, $db);
$num = count($matchedUsers); // 獲取matchedUsers數量

$i = 0;
?>
{
  "results": [
    <?php
foreach ($matchedUsers as $userId => $count) {
    $row = $db->query("SELECT * FROM `users` WHERE `usersId` = $userId");
    foreach ($row as $row) {
        $id = $row['usersId'];
        $userQuery = $db->query("SELECT u.usersUid
                                    FROM follows AS f
                                    JOIN users AS u ON f.currentUserId = u.usersId
                                    WHERE f.followedUserId = $userId
                                    LIMIT 1");
        $user = $userQuery->fetch(PDO::FETCH_ASSOC);
        if ($user !== false) { // 修改為你想要的條件檢查
            ?>
            {
                "link": "<?php echo "http://localhost/db_project/userpage.php?varname=$id#" ?>",
                "img": "<?php echo base64_encode($row['usersIMG']) ?>",
                "name": "<?php echo $row['usersName'] ?>",
                "uid": "<?php echo $row['usersUid'] ?>",
                "user": "<?php echo (string)$user['usersUid']?>",
                "num": "<?php echo $count-1 ?>"
            }
            <?php
        }
        $i = $i + 1;
        if ($i < $num && $user !== false) {
            ?>
            ,
            <?php
        }
    }
}
?>
  ]
}
