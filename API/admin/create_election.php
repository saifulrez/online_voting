<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $filename = '';
        if (isset($_FILES['img'])) {
            $timestamp = time();
            if (move_uploaded_file($_FILES['img']['tmp_name'], '../../asset/img/election/' . $timestamp . '-' . $_FILES['img']['name'])) {
                $filename = $timestamp . '-' . $_FILES['img']['name'];
            }
        }
        $sql_create_election = 'INSERT INTO election (title, description, start_time, end_time, announcement_time, hidden_time, img) VALUES ("' . $_POST['title'] . '", "' . $_POST['description'] . '", "' . $_POST['start_time'] . '", "' . $_POST['end_time'] . '", "' . $_POST['announcement_time'] . '", "' . $_POST['hidden_time'] . '", "' . $filename . '")';
        $res_create_election = mysqli_query($connect, $sql_create_election);
        if ($res_create_election) {
            echo json_encode(array("success" => true, "election_id" => mysqli_insert_id($connect)));
        } else {
            http_response_code(500);
            echo json_encode(array("success" => false));
        }
    } else {
        http_response_code(403);
        echo json_encode(array("success" => false));
    }
} else {
    http_response_code(401);
    echo json_encode(array("success" => false));
}
