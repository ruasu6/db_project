<?php

class LoginContr extends Login{
    private $uid;
    private $pwd;

    public function __construct($uid, $pwd){
        $this->uid = $uid;
        $this->pwd = $pwd;
    }

    public function loginUser(){
        if($this->emptyImput() == false){
            // echo "Empty input!";
            header("location: ../post.php?error=emptyinput");
            exit();
        }
        $this->getUser($this->uid, $this->pwd);
    }

    private function emptyImput(){
        $result;
        if(empty($this->uid) || empty($this->pwd)){
            $result = false;
        } 
        else{
            $result = true;
        }
        return $result;
        }
    }
    
    
?>