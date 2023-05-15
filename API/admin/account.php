<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $sql_account_list = 'SELECT id, pre_fix, FirstName, LastName, username, role FROM account';
        if (isset($_GET['id'])) {
            $sql_account_list .= ' WHERE id = "' . $_GET['id'] . '"';
        } else if (isset($_GET['username'])) {
            $sql_account_list .= ' WHERE username = "' . $_GET['username'] . '"';
        }
        $res_account_list = mysqli_query($connect, $sql_account_list);
        if ($res_account_list) {
            // print_r(mysqli_fetch_all($res_account_list));
            if (isset($_GET['id']) || isset($_GET['username'])) {
                echo json_encode(array("success" => true, "account" => mysqli_fetch_assoc($res_account_list)));
            } else {
                echo json_encode(array("success" => true, "accountList" => mysqli_fetch_all($res_account_list, MYSQLI_ASSOC)));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("success" => false));
        }
    }
} else {
    http_response_code(401);
    echo json_encode(array("success" => false));
}
