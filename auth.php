<?php
$login = trim($_POST['name']);
$pass = trim($_POST['pass']);

$pass = md5($pass."fgradsare");

$mysql = new mysqli('localhost', 'root', '', 'tourism_php');

$result = $mysql->query("SELECT * FROM `users` WHERE `_login` = '$login' AND `pass` = '$pass'");
$user = $result->fetch_assoc();
if(count($user) == 0){
    echo"Error";
    exit();
}

setcookie('user', $user['_login'], time() + 180, "/");

$mysql->close();
header ("Location: /");

?>