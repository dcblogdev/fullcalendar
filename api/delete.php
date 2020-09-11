<?php
include("../config.php");

if (isset($_POST["id"])) {
    $db->deleteById('events',$_POST['id']);
}
