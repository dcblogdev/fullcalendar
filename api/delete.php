<?php
include("../includes/config.php");

if (isset($_POST["id"])) {
    $db->delete('events', ['id' => $_POST['id']]);
}