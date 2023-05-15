<?php
require '../../_system/config.php';
require '../../_system/database.php';
require '../../_system/oop.php';
if (isset($_SESSION['u_id'])) {
    if (isAdmin($_SESSION['u_id'])) {
        $sql_election_list = 'SELECT * FROM election';
        $specificElectionSearch = false;
        if (isset($_GET["election_id"])) {
            $election_id = mysqli_real_escape_string($connect, $_GET["election_id"]);
            if ($_GET["election_id"] != "" && $_GET["election_id"] != "NULL" && $_GET["election_id"] != NULL) {
                $sql_election_list = 'SELECT * FROM election WHERE election_id = "' . $election_id . '"';
                $specificElectionSearch = true;
            }
        }
        $sql_election_list .= ' ORDER BY election_id DESC';
        $resElectionList = mysqli_query($connect, $sql_election_list);
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
}
