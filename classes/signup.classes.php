<?php

class Signup extends Dbh {
    
    protected function setUser($name, $uid, $email, $pwd){
        $stmt = $this->connect()->prepare('INSERT INTO users (usersName, usersUid, usersEmail, usersPwd) VALUES (?, ?, ?, ?);');
        
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);


        if(!$stmt->execute(array($name, $uid, $email, $hashedPwd))){
            $stmt = null;
            header("location: ../register.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }

    protected function checkUser($uid, $email){
        $stmt = $this->connect()->prepare('SELECT usersUid FROM users WHERE usersUid = ? OR usersEmail = ?;');
        if(!$stmt->execute(array($uid, $email))){
            $stmt = null;
            header("location: ../register.php?error=stmtfailed");
            exit();
        }

        $resultCheck;
        if($stmt->rowCount() > 0){
            $resultCheck = false;
        }
        else{
            $resultCheck = true;
        }
        return $resultCheck;
    }
}