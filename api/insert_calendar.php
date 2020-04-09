<?php
include("../includes/config.php");

$error = null;
$title = $_POST['title'];
$date = $_POST['date'];
$color = $_POST['color'];
$text_color = $_POST['text_color'];

if ($title == '') {
    $error['title'] = 'Title is required';
}

if ($date == '') {
    $error['date'] = 'Date is required';
}

if (! isset($error)) {

    $start = date('Y-m-d H:i:s', strtotime($date));
    $end = date('Y-m-d H:i:s', strtotime($date.' +2 hours'));
    
    $data['success'] = true;
    $data['message'] = 'Success!';

    //store
    $query = "INSERT INTO events (title, start_event, end_event, color, text_color) VALUES (:title, :start, :end, :color, :text_color)";
    $statement = $connect->prepare($query);
    $statement->execute([
        ':title' => $title,
        ':start' => $start,
        ':end'   => $end,
        ':color' => $color,
        ':text_color' => $text_color
    ]);
    
} else {

    $data['success'] = false;
    $data['errors'] = $error;
}

echo json_encode($data);