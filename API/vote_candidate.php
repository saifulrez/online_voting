<?php
require_once("../_system/config.php");
require_once("../_system/database.php");
if (isset($_SESSION["u_id"])) {
    if (isset($_POST["candidate_id"]) && isset($_POST["election_id"])) {
        $candidate_id = mysqli_real_escape_string($connect, $_POST["candidate_id"]);
        $election_id = mysqli_real_escape_string($connect, $_POST["election_id"]);
        $sql_checkvote = 'SELECT id FROM votelog WHERE u_id = "' . $_SESSION['u_id'] . '" AND election_id = "' . $election_id . '"';
        $res_checkvote = mysqli_query($connect, $sql_checkvote);
        if ($res_checkvote) {
            $num_checkvote = mysqli_num_rows($res_checkvote);
            if ($num_checkvote == 0) {
                if ($_POST['candidate_id'] == 0) {
                    $sql_votecandidate = 'INSERT INTO votelog (u_id, election_id) VALUES ("' . $_SESSION['u_id'] . '", "' . $election_id . '")';
                    $res_votecandidate = mysqli_query($connect, $sql_votecandidate);
                    if ($res_votecandidate) {
                        echo json_encode(array('success' => true, 'msg' => 'บันทึกการลงคะแนนเรียบร้อยแล้ว'));
                    } else {
                        http_response_code(500);
                        echo json_encode(array('success' => false, 'msg' => 'เกิดข้อผิดพลาด', 'error_code' => 'QUERY_01'));
                    }
                } else {
                    $sql_checkcandidate = 'SELECT cdd_id FROM candidate WHERE cdd_id = "' . $candidate_id . '" AND election_id = "' . $election_id . '"';
                    $res_checkcandidate = mysqli_query($connect, $sql_checkcandidate);
                    if ($res_checkcandidate) {
                        $num_checkcandidate = mysqli_num_rows($res_checkcandidate);
                        if ($num_checkcandidate == 1) {
                            $sql_votecandidate = 'INSERT INTO votelog (u_id, election_id) VALUES ("' . $_SESSION['u_id'] . '", "' . $election_id . '")';
                            $res_votecandidate = mysqli_query($connect, $sql_votecandidate);
                            if ($res_votecandidate) {
                                $sql_addscore = 'UPDATE candidate SET score=score+"1" WHERE election_id = "' . $election_id . '" AND cdd_id = "' . $candidate_id . '"';
                                $res_addscore = mysqli_query($connect, $sql_addscore);
                                if ($res_addscore) {
                                    echo json_encode(array('success' => true, 'msg' => 'บันทึกการลงคะแนนเรียบร้อยแล้ว'));
                                } else {
                                    http_response_code(500);
                                    echo json_encode(array('success' => false, 'msg' => 'เกิดข้อผิดพลาด', 'error_code' => 'QUERY_02'));
                                }
                            } else {
                                http_response_code(500);
                                echo json_encode(array('success' => false, 'msg' => 'เกิดข้อผิดพลาด', 'error_code' => 'QUERY_03'));
                            }
                        } else {
                            http_response_code(507);
                            echo json_encode(array('success' => false, 'msg' => 'เกิดข้อผิดพลาด', 'error_code' => 'Candidate_Member_Not_Found'));
                        }
                    } else {
                        http_response_code(500);
                        echo json_encode(array('success' => false, 'msg' => 'เกิดข้อผิดพลาด', 'error_code' => 'QUERY_04'));
                    }
                }
            } else {
                http_response_code(429);
                echo json_encode(array('success' => false, 'msg' => 'ไม่สามารถลงคะแนนซ้ำได้', 'error_code' => 'Already_Voted'));
            }
        } else {
            http_response_code(500);
            echo json_encode(array('success' => false, 'msg' => 'เกิดข้อผิดพลาด', 'error_code' => 'QUERY_05'));
        }
    } else {
        http_response_code(422);
        echo json_encode(array('success' => false, 'msg' => 'เกิดข้อผิดพลาด', 'error_code' => 'Missing_Parameter'));
    }
} else {
    http_response_code(401);
    echo json_encode(array('success' => false, 'msg' => 'กรุณาเข้าสู่ระบบก่อนทำการลงคะแนน'));
}