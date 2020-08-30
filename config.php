<?php
require('vendor/autoload.php');

use Dcblogdev\PdoWrapper\Database;

$host = "localhost";
$database = "calendar";
$username = "root";
$password = "";

$db = Database::get($username, $password, $database, $host);
$dir = "./";
