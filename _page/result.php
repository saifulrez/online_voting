<?php
if (!isset($_GET["election_id"])) {
    gotoPage('home');
} else {
    $election_id = mysqli_real_escape_string($connect, $_GET["election_id"]); 
    $sql_votelog = 'SELECT * FROM votelog WHERE election_id = "'. $election_id .'"';
    $res_votelog = mysqli_query($connect, $sql_votelog);
    $num_votelog = mysqli_num_rows($res_votelog);
    $sql_candidate = 'SELECT * FROM candidate WHERE election_id = "' . $election_id . '" ORDER BY score DESC';
    $res_candidate = mysqli_query($connect, $sql_candidate);
    $allscore = 0;
    while ($fetchscore = mysqli_fetch_assoc($res_candidate)) {
        $allscore = $allscore + $fetchscore["score"];
    }
    $failedvote = $num_votelog - $allscore;
    ?>
    <div class="d-flex justify-content-center">
        <div class="row">
            <div class="col col-sm-12">
                <hr>
                <h3>ผลการโหวต</h3>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="d-flex justify-content-center">
                            <div class="card" style="border-left: 4px solid #28a745; width: 100%">
                                <div class="card-body">
                                    จำนวนผู้ร่วมลงคะแนน <b><?php echo $num_votelog; ?></b> คน
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="d-flex justify-content-center">
                            <div class="card" style="border-left: 4px solid #da3b3b; width: 100%">
                                <div class="card-body">
                                    ไม่ประสงค์ลงคะแนน <?php echo $failedvote; ?> คน
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="grey lighten-2">
                            <th width="5%">หมายเลข.</th>
                            <th width="12%">IMG</th>
                            <th width="20%">ชื่อ</th>
                            <th width="50%">สโลแกน</th>
                            <th width="5%">
                                <center>คะแนน</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_candidate = 'SELECT * FROM candidate WHERE election_id = "' . $election_id . '" ORDER BY score DESC';
                        $res_candidate = mysqli_query($connect, $sql_candidate);
                        while ($fetchcandidate = mysqli_fetch_assoc($res_candidate)) {
                        ?>
                            <tr>
                                <td><?php echo $fetchcandidate["cdd_id"]; ?></td>
                                <td><img src="/asset/img/candidate/<?php echo $fetchcandidate["img"]; ?>" width="100%"></td>
                                <td><?php echo $fetchcandidate["pre_fix"] . ' ' . $fetchcandidate["FirstName"] . ' ' . $fetchcandidate["LastName"]; ?></td>
                                <td><?php echo $fetchcandidate["slogan"]; ?></td>
                                <td align="center"><?php echo $fetchcandidate["score"]; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>