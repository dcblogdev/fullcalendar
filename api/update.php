<?php
include("../config.php");

if (isset($_POST['id'])) {

    //collect data
    $error      = null;
    $id         = $_POST['id'];
    $start      = $_POST['start'];
    $end        = $_POST['end'];

    //optional fields
    $title      = isset($_POST['title']) ? $_POST['title']: '';
    $color      = isset($_POST['color']) ? $_POST['color']: '';
    $text_color = isset($_POST['text_color']) ? $_POST['text_color']: '';

    //validation
    if ($start == '') {
        $error['start'] = 'Start date is required';
    }

    if ($end == '') {
        $error['end'] = 'End date is required';
    }

    //if there are no errors, carry on
    if (! isset($error)) {

        //reformat date
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));
        
        $data['success'] = true;
        $data['message'] = 'Success!';

        //set core update array
        $update = [
            'start_event' => date('Y-m-d H:i:s', strtotime($_POST['start'])),
            'end_event' => date('Y-m-d H:i:s', strtotime($_POST['end']))
        ];

        //check for additional fields, and add to $update array if they exist
        if ($title !='') {
            $update['title'] = $title;
        }

        if ($color !='') {
            $update['color'] = $color;
        }

        if ($text_color !='') {
            $update['text_color'] = $text_color;
        }

        //set the where condition ie where id = 2
        $where = ['id' => $_POST['id']];

        //update database
        $db->update('events', $update, $where);
      
    } else {

        $data['success'] = false;
        $data['errors'] = $error;
    }

    echo json_encode($data);
}