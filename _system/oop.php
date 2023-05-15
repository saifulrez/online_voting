<?php
function gotoPage($page)
{
?>
    <script>
        window.location.href = '?page=<?= $page ?>';
    </script>
<?php
}

function isAdmin($u_id)
{
    global $connect;
    $sql_account = 'SELECT role FROM account WHERE id = "' . $u_id . '"';
    $res_account = mysqli_query($connect, $sql_account);
    if ($res_account) {
        if (mysqli_num_rows($res_account) == 1) {
            $fetch_account = mysqli_fetch_assoc($res_account);
            if ($fetch_account['role'] == 'admin') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
}

function electionState($election)
{
    $electionState = 1;
    $dateNow = date("Y-m-d H:i:s");
    if (date("Y-m-d H:i:s", strtotime($election["announcement_time"])) <= $dateNow) {
        $electionState = 4;
    } else if (date("Y-m-d H:i:s", strtotime($election["end_time"])) <= $dateNow) {
        $electionState = 3;
    } else if (date("Y-m-d H:i:s", strtotime($election["start_time"])) <= $dateNow) {
        $electionState = 2;
    }
    return $electionState;
}

function candidateElection($election_id)
{
    global $connect;
    $sql_candidate = 'SELECT account.id, account.username, candidate.cdd_id, candidate.pre_fix, candidate.FirstName, candidate.LastName, candidate.slogan, candidate.img FROM candidate INNER JOIN account ON candidate.u_id = account.id WHERE election_id = "' . $election_id . '" ORDER BY candidate.cdd_id ASC';
    $res_candidate = mysqli_query($connect, $sql_candidate);
    $candidateArray = array();
    while ($fetch_candidate = mysqli_fetch_assoc($res_candidate)) {
        array_push($candidateArray, $fetch_candidate);
    }
    return $candidateArray;
}
