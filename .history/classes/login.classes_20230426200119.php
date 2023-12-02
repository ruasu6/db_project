<?php

class Login extends Dbh {
    
    protected function getUser($uid, $pwd){
        $stmt = $this->connect()->prepare('SELECT usersPwd FROM users WHERE usersUid = ? OR usersEmail = ?;');

        if(!$stmt->execute(array($uid, $pwd))){
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }
        if($stmt->rowCount() == 0 ){
            $stmt = null;
            header("location: ../index.php?error=usernotfound");
            exit();
        }
        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["usersPwd"]);

        if($checkPwd == false){
            $stmt = null;
            header("location: ../index.php?error=wrongpassword");
            exit();
        }
        elseif($checkPwd == true){
            $stmt = $this->connect()->prepare('SELECT usersPwd FROM users WHERE usersUid = ? OR usersEmail = ? AND usersPwd = ?;');
            
            if(!$stmt->execute(array($uid, $uid, $pwd))){
                $stmt = null;
                header("location: ../register.php?error=stmtfailed");
                exit();
            }
            if($stmt->rowCount() == 0 ){
                $stmt = null;
                header("location: ../index.php?error=usernotfound");
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($users["usersUrl"]= Null){
                $userid = $_SESSION['useruid'];
                $url = "userpage.php?id= $userid";
            
                // 建立 SQL 查詢字串，查詢使用者名稱
                $sql = "UPDATE `users` SET `usersUrl`=? WHERE `usersUid`=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $url, $userid);
                $stmt->close();
            }


            session_start();
            
            $_SESSION["userid"] = $user[0]["usersId"];
            $_SESSION["useruid"] = $user[0]["usersUid"];
            $stmt = null;
            
        }
    }
}