<?php

class Dbh{

    private function connect(){
        try{
            $username = "root";
            $password = "";
            $dbh = new PDO('mysql:host=localhost;dbname=users', $username, $password);
            return $dbh;
        }
        catch(PDOException $e){
            print "Error!: ". $e->getMessage() . "<br/>";
            die();
        }
    }
}
