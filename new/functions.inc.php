<?php
session_start();
function createVideoList($conn, $vId, $vType, $usersId, $vName) {
    checkRepeat($conn, $vId, $usersId);
    $videoTitle = $vName;
    $sql = "INSERT INTO video (vId, vType, usersId) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../search.php?error=stmtfailed");
        exit();
    }

    if(checkRepeat($conn, $vId, $usersId)){
        mysqli_stmt_bind_param($stmt, "sss", $vId, $vType, $usersId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        createVideoPost($conn, $videoTitle, $usersId);
        // header("location: ../blank.php");
        // exit();
    }
    else{    
        header("location:../blank.php?error=repeat");
    }
    

    
}


function createVideoPost($conn, $vName, $usersId) {
    $name_sql = "SELECT usersName FROM users WHERE usersId = '$usersId'";
    $result = $conn->query($name_sql);
    if ($row = $result->fetch_assoc()) {
        $name = $row['usersName'];
    }

    $post_content = $name."看了「".$vName." 」，快來跟他一起討論吧！";

    $sql = "INSERT INTO posts (usersId, usersName, post_content) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../search.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $usersId, $name, $post_content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../blank.php");
    exit();
    
}

function checkRepeat($conn, $vId, $usersId){
    $resultCheck;
    $sql = "SELECT * FROM video WHERE vId = '$vId' AND usersId = '$usersId'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $resultCheck = false;
    }
    else{
        return $resultCheck = true;
    }
    return $resultCheck;

}