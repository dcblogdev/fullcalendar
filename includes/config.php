<?php
$hostname = "localhost";
$database = "calendar";
$username = "root";
$password = "";

$connect = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
$dir='/';