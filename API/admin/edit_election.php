<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $sql_update_election = 'UPDATE election SET title = "' . $_POST['title'] . '", description = "' . $_POST['description'] . '", start_time = "' . $_POST['start_time'] . '", end_time = "' . $_POST['end_time'] . '", announcement_time = "' . $_POST['announcement_time'] . '", hidden_time = "' . $_POST['hidden_time'] . '" WHERE election_id = "' . $_POST['election_id'] . '"';
        $res_update_election = mysqli_query($connect, $sql_update_election);
        if ($res_update_election) {
            echo json_encode(array("success" => true));
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