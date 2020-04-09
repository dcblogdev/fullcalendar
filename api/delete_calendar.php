<?php
include("../includes/config.php");

if (isset($_POST["id"])) {
    $query = "DELETE FROM events WHERE id=:id";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':id' => $_POST['id']
    ]);
}