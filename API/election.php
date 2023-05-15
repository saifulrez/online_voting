<?php
require_once("../_system/config.php");
require_once("../_system/database.php");
require_once("../_system/oop.php");
$sql_votelist = 'SELECT * FROM election WHERE hidden_time >= NOW()';
$specificElectionSearch = false;
if (isset($_GET["keyword"])) {
    $keyword = mysqli_real_escape_string($connect, $_GET["keyword"]);
    if ($_GET["keyword"] != "" && $_GET["keyword"] != "NULL" && $_GET["keyword"] != NULL) {
        $sql_votelist = 'SELECT * FROM election WHERE hidden_time >= NOW() AND (election_id LIKE "%' . $keyword . '%" OR title LIKE "%' . $keyword . '%" OR description = "%' . $keyword . '%")';
        $specificElectionSearch = true;
    }
} else if (isset($_GET["election_id"])) {
    $election_id = mysqli_real_escape_string($connect, $_GET["election_id"]);
    if ($_GET["election_id"] != "" && $_GET["election_id"] != "NULL" && $_GET["election_id"] != NULL) {
        $sql_votelist = 'SELECT * FROM election WHERE election_id = "' . $election_id . '"';
        $specificElectionSearch = true;
    }
}
$resElectionList = mysqli_query($connect, $sql_votelist);
if (mysqli_num_rows($resElectionList) == 0) {
    http_response_code(204);
    echo json_encode(array());
} else {
    if ($specificElectionSearch) {
        $fetchElection = mysqli_fetch_assoc($resElectionList);
        $fetchElection["election_state"] = electionState($fetchElection);
        $fetchElection["candidate"] = candidateElection($fetchElection["election_id"]);
        echo json_encode($fetchElection);
    } else {
        $electionArray = array();
        while ($fetchElection = mysqli_fetch_assoc($resElectionList)) {
            $fetchElection["election_state"] = electionState($fetchElection);
            $fetchElection["candidate"] = candidateElection($fetchElection["election_id"]);
            array_push($electionArray, $fetchElection);
        }
        echo json_encode($electionArray);
    }
}
