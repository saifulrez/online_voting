<?php
if (!isset($_SESSION["username"])) {
    gotoPage('home');
} else {
    if (isset($_GET["election_id"])) { ?>
        <script type="text/javascript">
            function voteCandidate(candidateId = 0) {
                Swal.fire({
                    title: 'ยืนยันการลงคะแนน',
                    text: candidateId == 0 ? "คลิกปุ่ม ยืนยัน เพื่อยืนยันไม่ประสงค์การลงคะแนน" : "คลิกปุ่ม ยืนยัน เพื่อยืนยันการลงคะแนนให้หมายเลข " + candidateId,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post({
                            url: '/API/vote_candidate.php',
                            data: {
                                candidate_id: candidateId,
                                election_id: getUrlParams('election_id')
                            }
                        }).done((response) => {
                            var voteCandidate = null;
                            try {
                                voteCandidate = JSON.parse(response);
                            } catch (e) {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด',
                                    icon: 'error',
                                    showCloseButton: true,
                                    focusConfirm: false,
                                    confirmButtonText: 'ตกลง'
                                })
                                return;
                            }
                            Swal.fire({
                                title: 'ลงคะแนนสำเร็จ',
                                icon: 'success',
                                html: '',
                                showCloseButton: true,
                                focusConfirm: false,
                                confirmButtonText: 'ตกลง',
                            }).then(() => {
                                window.location.href = '?page=detail&election_id=' + getUrlParams('election_id');
                            })
                        }).fail((response, status, error) => {
                            var voteCandidate = null;
                            try {
                                voteCandidate = JSON.parse(response.responseText);
                            } catch (e) {
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด',
                                    icon: 'error',
                                    showCloseButton: true,
                                    focusConfirm: false,
                                    confirmButtonText: 'ตกลง'
                                })
                                return;
                            }
                            Swal.fire({
                                title: 'ลงคะแนนไม่สำเร็จ',
                                icon: 'error',
                                text: voteCandidate.msg,
                                showCloseButton: true,
                                focusConfirm: false,
                                confirmButtonText: 'ตกลง',
                            })
                        })
                    }
                })
            }

            function clickToVote(e) {
                const candidateToVote = $('input[type="radio"][class="candidate-input"]:checked').val();
                if (candidateToVote) {
                    voteCandidate(candidateToVote);
                } else {
                    Swal.fire({
                        title: 'กรุณาเลือกคนที่ต้องการลงคะแนน',
                        icon: 'warning',
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText: 'ตกลง',
                    })
                }
            }

            $(document).ready(() => {
                $.get({
                    url: '/API/election.php',
                    data: {
                        election_id: getUrlParams('election_id')
                    }
                }).done((response) => {
                    var electionDetail = null;
                    try {
                        electionDetail = JSON.parse(response);
                    } catch (e) {
                        console.error(e);
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            icon: 'error',
                            showCloseButton: true,
                            focusConfirm: false,
                            confirmButtonText: 'ตกลง',
                        })
                        return;
                    }
                    electionDetail.candidate.forEach((candidate) => {
                        $('#candidate_list').append('<div class="d-flex border rounded mb-2"><div class="p-2 flex-shrink-1 d-inline-flex align-items-center border-right"><input type="radio" name="candidate-vote" class="candidate-input" value="' + candidate.cdd_id + '" /></div><div class="p-2 flex-shrink-2 border-right"><img src="/asset/img/candidate/' + candidate.img + '" class="candidate-img"></div><div class="p-2 w-100"><h5 class="">' + candidate.pre_fix + ' ' + candidate.FirstName + ' ' + candidate.LastName + '</h5><div>' + candidate.slogan + '</div></div></div>');
                    })
                })
            })
        </script>
        <h3>รายชื่อผู้สมัคร</h3>
        <hr>
        <div id="candidate_list">

        </div>
        <div class="text-right">
            <button class="btn btn-danger waves-effect waves-light" onclick="voteCandidate(0)"> ไม่ประสงค์ลงคะแนน </button>
            <button type="submit" class="btn btn-primary waves-effect waves-light" onclick="clickToVote()"> บันทึก </button>
        </div>
<?php } else {
        gotoPage('home');
    }
} ?>