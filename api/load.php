<?php
include("../config.php");
$data = [];

$result = $db->rows("SELECT * FROM events ORDER BY id");
foreach($result as $row) {
    $data[] = [
        'id'              => $row->id,
        'title'           => $row->title,
        'start'           => $row->start_event,
        'end'             => $row->end_event,
        'backgroundColor' => $row->color,
        'textColor'       => $row->text_color
    ];
}

echo json_encode($data);
