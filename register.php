<?php

$login = trim($_POST['name']);
$pass = trim($_POST['pass']);
$pass = md5($pass."fgradsare");
$mysql = new mysqli('localhost', 'root', '', 'tourism_php');
$mysql -> query("INSERT INTO `users` (`_login`, `pass`) VALUES('$login','$pass')");
$mysql->close();
header ("Location: /sin.html");
?>
