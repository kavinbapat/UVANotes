<?php
session_start();

require("connect-db.php");
require("database-requests.php");

if(isset($_POST["username"]) && !empty($_POST["username"]) &&
isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["computingId"]) && !empty($_POST["computingId"])) {

    $name = $_POST["username"];
    $computingId = $_POST['computingId'];
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!empty(getUser($name, $computingId, $hashed_password))) {
        $_SESSION['errorMessage'] = "The provided Computing ID already exists. Please choose a different one.";
        header("Location: login.php");
        exit();
    }
    else{    
        $success = insertUser($name, $computingId, $hashed_password);
        if($success) {
            header("Location: login.php");
            exit();
        } else {
            header("Location: login.php");
            $_SESSION['errorMessage'] = "An error occured when trying to register your account. Please try again later.";
            exit();
        }

    }
}