<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $filename = '';
        if (isset($_FILES['img'])) {
            $timestamp = time();
            if (move_uploaded_file($_FILES['img']['tmp_name'], '../../asset/img/candidate/' . $timestamp . '-' . $_FILES['img']['name'])) {
                $filename = $timestamp . '-' . $_FILES['img']['name'];
            }
        }
        $sql_add_candidate = 'INSERT INTO candidate (election_id, cdd_id, u_id, pre_fix, FirstName, LastName, slogan, img) VALUES ("' . $_POST['election_id'] . '", "' . $_POST['cdd_id'] . '", "' . $_POST['u_id'] . '", "' . $_POST['pre_fix'] . '", "' . $_POST['FirstName'] . '", "' . $_POST['LastName'] . '", "' . $_POST['slogan'] . '", "' . $filename . '")';
        $res_add_candidate = mysqli_query($connect, $sql_add_candidate);
        if ($res_add_candidate) {
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
