<?php
include("../includes/config.php");
$data = [];

$query = "SELECT * FROM events ORDER BY id";
$statement = $connect->prepare($query);
$statement->execute();

$result = $statement->fetchAll();
foreach($result as $row) {
    $data[] = [
        'id'    => $row["id"],
        'title' => $row["title"],
        'start' => $row["start_event"],
        'end'   => $row["end_event"],
        'backgroundColor' => $row["color"],
        'textColor' => $row["text_color"]
    ];
}

echo json_encode($data);
