<?php

class SignupContr extends Signup{
    private $name;
    private $uid;
    private $email;
    private $pwd;
    private $pwdRepeat;
    

    public function __construct($name, $uid, $email, $pwd, $pwdRepeat){
        $this->name = $name;
        $this->uid = $uid;
        $this->email = $email;
        $this->pwd = $pwd;
        $this->pwdRepeat = $pwdRepeat;
    }

    public function signupUser(){
        if($this->emptyImput() == false){
            // echo "Empty input!";
            header("location: ../index.php?error=emptyinput");
            exit();
        }
        if($this-> invalidUid()== false){
            // echo "Invalid username!";
            header("location: ../index.php?error=username");
            exit();
        }
        if ($this->invalidEmail() == false) {
            header("location: ../register.php?error=email");
            exit();
        }
        if ($this->pwdMatch() == false) {
            header("location: ../register.php?error=passwordmatch");
            exit();
        }
        if ($this->uidTakenCheck() == false) {
            header("location: ../register.php?error=usernametaken");
            exit();
        }
        $this->setUser($this->name, $this->uid, $this->email, $this->pwd);
    }

    private function emptyImput(){
        $result;
        if(empty($this->name) || empty($this->uid) || empty($this->email) || empty($this->pwd)|| empty($this->pwdRepeat)){
            $result = false;
        } 
        else{
            $result = true;
        }
        return $result;
        
    }
    
    // private function invalidUsername() {
    //     $result;
    //     if (!preg_match("/^[a-zA-Z0-9]*$/", $this->userame)){
    //         $result = false;
    //     } 
    //     else{
    //         $result = true;
    //     }
    //     return $result;
    // }

    private function invalidUid() {
        $result;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)){
            $result = false;
        } 
        else{
            $result = true;
        }
        return $result;
    }

    private function invalidEmail() {
        $result;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $result = false;
        } 
        else{
            $result = true;
        }
        return $result;
    }

    private function pwdMatch() {
        $result;
        if ($this->pwd !== $this->pwdRepeat){
            $result = false;
        } 
        else{
            $result = true;
        }
        return $result;
    }

    private function uidTakenCheck() {
        $result;
        if (!$this->checkUser($this->uid, $this->email)){
            $result = false;
        } 
        else{
            $result = true;
        }
        return $result;
    }
}
?>