<?php
    session_start();

    if(isset($_POST["submit"])){
        $vId = $_POST["search-result"];
        $vType = $_POST["search-result-type".$vId];
        $vName = $_POST["search-result-name".$vId];
        $vGenre = $_POST["search-result-genre".$vId];
        $genres = explode(',', $vGenre);
        $usersId = $_SESSION["userid"];


        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';

        createVideoList($conn, $vId, $vType, $usersId, $vName, $genres);

    }
    else{
        header("location: ../blank.php");
        exit(); 
     }