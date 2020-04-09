<?php
include("../includes/config.php");

if (isset($_POST["id"])) {
 
    $query = "UPDATE events SET start_event=:start, end_event=:end WHERE id=:id";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':start' => date('Y-m-d H:i:s', strtotime($_POST['start'])),
        ':end'   => date('Y-m-d H:i:s', strtotime($_POST['end'])),
        ':id'    => $_POST['id']
    ]);
}