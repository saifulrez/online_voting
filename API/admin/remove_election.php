<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $sql_delete_candidate = 'DELETE FROM candidate WHERE election_id = "' . $_POST['election_id'] . '"';
        $res_delete_candidate = mysqli_query($connect, $sql_delete_candidate);
        if ($res_delete_candidate) {
            $sql_delete_election = 'DELETE FROM election WHERE election_id = "' . $_POST['election_id'] . '"';
            $res_delete_election = mysqli_query($connect, $sql_delete_election);
            if ($res_delete_election) {
                echo json_encode(array("success" => true));
            } else {
                http_response_code(500);
                echo json_encode(array("success" => false));
            }
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