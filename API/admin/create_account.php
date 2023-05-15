<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $sql_check_account = 'SELECT id FROM account WHERE username = "' . $_POST['username'] . '"';
        $res_check_account = mysqli_query($connect, $sql_check_account);
        if ($res_check_account) {
            if (mysqli_num_rows($res_check_account) == 0) {
                $sql_create_account = 'INSERT INTO account (pre_fix, FirstName, LastName, username, password, role) VALUES ("' . $_POST['pre_fix'] . '", "' . $_POST['FirstName'] . '", "' . $_POST['LastName'] . '", "' . $_POST['username'] . '", "' . hash('sha256', $_POST['password']) . '", "' . $_POST['role'] . '")';
                $res_create_account = mysqli_query($connect, $sql_create_account);
                if ($res_create_account) {
                    echo json_encode(array("success" => true));
                } else {
                    http_response_code(500);
                    echo json_encode(array("success" => false));
                }
            } else {
                http_response_code(409);
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