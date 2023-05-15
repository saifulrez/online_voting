<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $sql_edit_account = 'UPDATE account SET pre_fix = "' . $_POST['pre_fix'] . '", FirstName = "' . $_POST['FirstName'] . '", LastName = "' . $_POST['LastName'] . '", role = "' . $_POST['role'] . '" WHERE id = "' . $_POST['id'] . '"';
        $res_edit_account = mysqli_query($connect, $sql_edit_account);
        if ($res_edit_account) {
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
