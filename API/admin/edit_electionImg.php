<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        if (isset($_FILES['img'])) {
            $timestamp = time();
            if (move_uploaded_file($_FILES['img']['tmp_name'], '../../asset/img/election/' . $timestamp . '-' . $_FILES['img']['name'])) {
                $filename = $timestamp . '-' . $_FILES['img']['name'];
                $sql_update_election = 'UPDATE election SET img = "' . $filename . '" WHERE election_id = "' . $_POST['election_id'] . '"';
                $res_update_election = mysqli_query($connect, $sql_update_election);
                if ($res_update_election) {
                    echo json_encode(array("success" => true));
                } else {
                    http_response_code(500);
                    echo json_encode(array("success" => false));
                }
            }
        }
    }
}