<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $sql_update_password = 'UPDATE account SET password = "' . hash('sha256', $_POST['password']) . '" WHERE id = "' . mysqli_real_escape_string($connect, $_POST['id']) . '"';
        $res_update_password = mysqli_query($connect, $sql_update_password);
        if ($res_update_password) {
            echo json_encode(array("success" => true));
        } else {
            http_response_code(500);
            echo json_encode(array("success" => false, "msg" => "เกิดข้อผิดพลาดไม่ทราบสาเหตุ"));
        }
    } else {
        http_response_code(403);
        echo json_encode(array("success" => false));
    }
} else {
    http_response_code(401);
    echo json_encode(array("success" => false));
}