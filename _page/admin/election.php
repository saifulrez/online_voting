<div class="d-flex justify-content-end">
    <button class="btn btn-primary btn-md col-md-3 mb-3" onclick="createElectionModal()">สร้างการเลือกตั้ง</button>
</div>
<div class="border">
    <table class="table" id="table-election">
        <thead>
            <tr class="text-center">
                <td>#</td>
                <td>ชื่อการเลือกตั้ง</td>
                <td>สถานะ</td>
                <td>จัดการ</td>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<script>
    var electionState = {};
    var currentCandidateEdit = 0;

    function addElectionToTable(election) {
        var tr = $('<tr class="text-center"></tr>');
        tr.append($('<td></td>').text(election.election_id));
        tr.append($('<td></td>').text(election.title));
        tr.append($('<td></td>').text(election.status));
        tr.append($('<td></td>').append($('<i class="fas fa-edit"></i>').attr('onclick', 'editElectionModal(' + election.election_id + ')')).append($('<i class="fas fa-link"></i>').attr('onclick', 'gotoElection(' + election.election_id + ')')).append($('<i class="fas fa-times"></i>').attr('onclick', 'deleteElection(' + election.election_id + ')')).append($('<i class="fas fa-image"></i>').attr('onclick', 'editElectionImageModal(' + election.election_id + ')')));
        $('#table-election tbody').append(tr);
    }

    function createElectionModal() {
        $('#electionModal-title').text('สร้างการเลือกตั้ง');
        $('#electionModal').modal('show');
        $('#electionModal-btn').text('ถัดไป').attr('onclick', 'nextElectionStepModal()');
        electionState = {};
        electionState.candidate = [];
        setElectionModal();
    }

    function gotoElection(election_id) {
        window.location.href = '?page=detail&election_id=' + election_id;
    }

    function nextElectionStepModal() {
        electionState.title = $('#election-title').val();
        electionState.description = $('#election-description').val();
        electionState.start_time = $('#election-start-time').val();
        electionState.end_time = $('#election-end-time').val();
        electionState.announcement_time = $('#election-announcement-time').val();
        electionState.hidden_time = $('#election-hidden-time').val();
        $('#electionModal').modal('hide');
        $('#electionCandidateModal').modal('show');
    }

    function submitCreateElection() {
        $.post({
            url: "/API/admin/create_election.php",
            data: {
                title: electionState.title,
                description: electionState.description,
                start_time: electionState.start_time,
                end_time: electionState.end_time,
                announcement_time: electionState.announcement_time,
                hidden_time: electionState.hidden_time,
            }
        }).done((response) => {
            try {
                var res = JSON.parse(response);
                if (res.success) {
                    electionState.election_id = res.election_id;
                    updateCandidateList();
                    $('#electionCandidateModal').modal('hide');
                    loadElection();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: res.msg
                    })
                }
            } catch (e) {
                console.error(e);
                return;
            }
        })
    }

    function loadElection() {
        $.get({
            "url": "/API/admin/election.php"
        }).done((response) => {
            var election = null;
            try {
                election = JSON.parse(response);
            } catch (e) {
                console.error(e);
                return;
            }
            $('#table-election tbody').empty();
            election.forEach((election) => {
                switch (election.election_state) {
                    case 1:
                        election.status = 'รอเริ่มเลือกตั้ง';
                        break;
                    case 2:
                        election.status = 'กำลังเลือกตั้ง';
                        break;
                    case 3:
                        election.status = 'รอประกาศผล';
                        break;
                    case 4:
                        election.status = 'ประกาศผลคะแนน';
                        break;
                }
                addElectionToTable(election);
            });
            if ($.fn.dataTable.isDataTable('#table-election')) {
                $('#table-election').DataTable();
            } else {
                $('#table-election').DataTable({
                    "autoWidth": false,
                    "order": []
                });
            }
        })
    }

    function candidateComponent(candidate) {
        $('#candidate_list').append('<div class="col-12 col-md-6"><div class="card"><div class="card-header"><div class="d-flex justify-content-between"><div>' + candidate.username + '</div><div></div><div><i class="fas fa-edit" onclick="editCandidateModal(' + candidate.cdd_id + ')"></i>&nbsp;<i class="fas fa-times" onclick="removeCandidate(' + candidate.cdd_id + ')"></i></div></div></div><div class="card-body text-center" id="candidate-card-body-' + candidate.cdd_id + '" onclick="uploadCandidateImg(' + candidate.cdd_id + ')"><img class="card-img-top" style="max-height: 300px;" id="candidate-img-' + candidate.cdd_id + '" src=""><i class="fas fa-file-upload"></i></div><div class="card-footer"><h5 class="card-title">' + candidate.pre_fix + ' ' + candidate.FirstName + ' ' + candidate.LastName + '</h5><div class="card-text">หมายเลข: <span class="text-primary">' + candidate.cdd_id + '</span><br />สโลแกน: <span>' + candidate.slogan + '</span></div></div></div></div>');
    }

    function refreshCandidateList() {
        $('#candidate_list').empty();
        electionState.candidate.forEach((candidate, i) => {
            candidate.cdd_id = i + 1;
            candidateComponent(candidate);
            if (candidate.img) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#candidate-card-body-' + candidate.cdd_id).addClass('p-0');
                    $('#candidate-img-' + candidate.cdd_id).attr('src', e.target.result);
                }
                reader.readAsDataURL(candidate.img);
            }
        });
    }

    function addCandidate() {
        var username = $('#username-select').val();
        var isInCandidateList = false;
        electionState.candidate.forEach((candidate) => {
            if (candidate.username == username) {
                isInCandidateList = true;
            }
        })
        if (!isInCandidateList) {
            $.get({
                url: "/API/admin/account.php",
                data: {
                    username: username
                }
            }).then((response) => {
                try {
                    var candidate = JSON.parse(response).account;
                    electionState.candidate.push({
                        u_id: candidate.id,
                        username: candidate.username,
                        pre_fix: candidate.pre_fix,
                        FirstName: candidate.FirstName,
                        LastName: candidate.LastName,
                        slogan: $('#candidate-slogan').val(),
                        img: null
                    })
                    $('#username-select').val('');
                    $('#candidate-slogan').val('');
                    refreshCandidateList();
                } catch (e) {
                    console.error(e);
                    return;
                }
            })
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ชื่อผู้ใช้นี้เป็นผู้สมัครเลือกตั้งแล้ว'
            })
        }
    }

    function readFile(e) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#candidate-card-body-' + currentCandidateEdit).addClass('p-0');
            $('#candidate-img-' + currentCandidateEdit).attr('src', e.target.result);
        }
        reader.readAsDataURL(e.files[0]);
        electionState.candidate[currentCandidateEdit - 1].img = e.files[0];
    }

    function uploadCandidateImg(cdd_id) {
        currentCandidateEdit = cdd_id;
        $('#candidate-img').click();
    }

    function removeCandidate(cdd_id) {
        electionState.candidate.splice(cdd_id - 1, 1);
        refreshCandidateList();
    }

    function updateCandidateList() {
        electionState.candidate.forEach((candidate) => {
            var form_data = new FormData();
            form_data.append('election_id', electionState.election_id);
            form_data.append('cdd_id', candidate.cdd_id);
            form_data.append('u_id', candidate.u_id);
            form_data.append('username', candidate.username);
            form_data.append('pre_fix', candidate.pre_fix);
            form_data.append('FirstName', candidate.FirstName);
            form_data.append('LastName', candidate.LastName);
            form_data.append('slogan', candidate.slogan);
            form_data.append('img', candidate.img);
            $.ajax({
                url: '/API/admin/add_candidate.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: (response) => {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            $('#candidate-card-header').append($('<i class="fas fa-check"></i>'));
                        } else {
                            $('#candidate-card-header').append($('<i class="fas fa-times"></i>'));
                        }
                    } catch (e) {
                        console.error(e);
                        $('#candidate-card-header').append($('<i class="fas fa-times"></i>'));
                    }
                }
            })
        })
    }

    function editElectionModal(election_id) {
        $.get({
            url: '/API/admin/election.php',
            data: {
                election_id: election_id
            }
        }).done((response) => {
            try {
                electionState = JSON.parse(response);
            } catch (e) {
                console.error(e);
                return;
            }

            function createFileObject(url) {
                return new Promise((resolve, reject) => {
                    fetch(url).then(async response => {
                        const contentType = response.headers.get('content-type')
                        const blob = await response.blob()
                        const fileName = url.split('/').pop();
                        const file = new File([blob], fileName.substring(fileName.indexOf('-') + 1), {
                            contentType
                        })
                        resolve(file);
                    })
                })
            }
            electionState.candidate.forEach((candidate) => {
                if (candidate.img) {
                    createFileObject('/asset/img/candidate/' + candidate.img).then((file) => {
                        candidate.img = file;
                    })
                }
            })
            setTimeout(() => {
                setElectionModal();
                refreshCandidateList();
                $('#electionModal').modal('show');
                $('#electionModal-title').text('แก้ไขการเลือกตั้ง');
                $('#electionModal-btn').text('บันทึก').attr('onclick', 'updateElection()');
            }, electionState.candidate.length * 1000);
        })
    }

    function setElectionModal() {
        $('#election-title').val(electionState.title ? electionState.title : '');
        $('#election-description').val(electionState.title ? electionState.description : '');
        $('#election-start-time').val(electionState.title ? moment(new Date(electionState.start_time)).format('YYYY-MM-DDTHH:mm') : '');
        $('#election-end-time').val(electionState.title ? moment(new Date(electionState.end_time)).format('YYYY-MM-DDTHH:mm') : '');
        $('#election-announcement-time').val(electionState.title ? moment(new Date(electionState.announcement_time)).format('YYYY-MM-DDTHH:mm') : '');
        $('#election-hidden-time').val(electionState.title ? moment(new Date(electionState.hidden_time)).format('YYYY-MM-DDTHH:mm') : '');
    }

    function updateElection() {
        $.post({
            url: '/API/admin/edit_election.php',
            data: {
                election_id: electionState.election_id,
                title: $('#election-title').val(),
                description: $('#election-description').val(),
                start_time: $('#election-start-time').val(),
                end_time: $('#election-end-time').val(),
                announcement_time: $('#election-announcement-time').val(),
                hidden_time: $('#election-hidden-time').val()
            }
        }).done((response) => {
            try {
                var res = JSON.parse(response);
            } catch (e) {
                console.error(e);
                return;
            }
            if (res.success) {
                $('#electionModal').modal('hide');
                electionState = {};
                loadElection();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: res.msg
                })
            }
        })
    }

    function editElectionImageModal(election_id) {
        $('#election-img-pre').attr('src', '');
        $.get({
            url: '/API/admin/election.php',
            data: {
                election_id: election_id
            }
        }).done((response) => {
            try {
                electionState = JSON.parse(response);
            } catch (e) {
                console.error(e);
                return;
            }

            function createFileObject(url) {
                return new Promise((resolve, reject) => {
                    fetch(url).then(async response => {
                        const contentType = response.headers.get('content-type')
                        const blob = await response.blob()
                        const fileName = url.split('/').pop();
                        const file = new File([blob], fileName.substring(fileName.indexOf('-') + 1), {
                            contentType
                        })
                        resolve(file);
                    })
                })
            }
            createFileObject('/asset/img/election/' + electionState.img).then((file) => {
                electionState.img = file;
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#election-img-pre').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
                $('#electionImageModal').modal('show');
            })
        })
    }

    function previewElectionImage(e) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#election-img-pre').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.files[0]);
        electionState.img = e.files[0];
    }

    function submitEditElectionImage() {
        var form_data = new FormData();
        form_data.append('election_id', electionState.election_id);
        form_data.append('img', electionState.img);
        $.ajax({
            url: '/API/admin/edit_electionImg.php',
            data: form_data,
            type: 'post',
            processData: false,
            contentType: false,
            success: (response) => {
                try {
                    var res = JSON.parse(response);
                } catch (e) {
                    console.error(e);
                    return;
                }
                if (res.success) {
                    $('#electionImageModal').modal('hide');
                    electionState = {};
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: res.msg
                    })
                }
            }
        })
    }

    function deleteElection(election_id) {
        $.get({
            url: '/API/admin/election.php',
            data: {
                election_id: election_id
            }
        }).done((response) => {
            try {
                electionState = JSON.parse(response);
            } catch (e) {
                console.error(e);
                return;
            }
            Swal.fire({
                title: 'ลบการเลือกตั้ง ' + electionState.title,
                text: "คุณแน่ใจว่าต้องการลบการเลือกตั้งนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post({
                        url: '/API/admin/remove_election.php',
                        data: {
                            election_id: election_id
                        }
                    }).done((response) => {
                        try {
                            var res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire(
                                    'ลบการเลือกตั้งสำเร็จ',
                                    'การเลือกตั้งถูกลบแล้ว',
                                    'success'
                                )
                                loadElection();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: res.msg
                                })
                            }
                        } catch (e) {
                            console.error(e);
                            return;
                        }
                    })
                }
            })
        })
    }
    $(document).ready(function() {
        loadElection();
        $.get({
            url: "/API/admin/account.php"
        }).then((response) => {
            try {
                var account = JSON.parse(response).accountList;
            } catch (e) {
                console.error(e);
                return;
            }
            account.forEach((account) => {
                $('#username-select').append($('<option></option>', {
                    value: account.id,
                    text: account.username
                }))
            })
            $('#username-select').editableSelect();
        })
    });
</script>
<div class="modal fade" id="electionModal" tabindex="-1" role="dialog" aria-labelledby="electionModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="electionModal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ชื่อการเลือกตั้ง</label>
                    <input type="text" class="form-control" id="election-title">
                </div>
                <div class="form-group">
                    <label>คำอธิบายการเลือกตั้ง</label>
                    <textarea class="form-control" id="election-description"></textarea>
                </div>
                <div class="form-group">
                    <label>วัน-เวลา เริ่มการเลือกตั้ง</label>
                    <input type="datetime-local" class="form-control" id="election-start-time">
                </div>
                <div class="form-group">
                    <label>วัน-เวลา สิ้นสุดการเลือกตั้ง</label>
                    <input type="datetime-local" class="form-control" id="election-end-time">
                </div>
                <div class="form-group">
                    <label>วัน-เวลา ประกาศผลการเลือกตั้ง</label>
                    <input type="datetime-local" class="form-control" id="election-announcement-time">
                </div>
                <div class="form-group">
                    <label>วัน-เวลา ซ่อนการเลือกตั้งจากรายการ</label>
                    <input type="datetime-local" class="form-control" id="election-hidden-time">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="electionModal-btn"></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade overflow-auto" id="electionCandidateModal" tabindex="-1" role="dialog" aria-labelledby="electionCandidateModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ผู้สมัครเลือกตั้ง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ชื่อผู้ใช้ผู้สมัครเลือกตั้ง</label>
                    <select class="form-control" id="username-select" searchable="ค้นหาผู้ใช้"></select>
                </div>
                <div class="form-group">
                    <label>สโลแกนผู้สมัครเลือกตั้ง</label>
                    <input type="text" class="form-control" id="candidate-slogan">
                </div>
                <div class="btn btn-success col" style="margin: 0;" onclick="addCandidate()">เพิ่มผู้สมัครเลือกตั้ง</div>
                <br />
                <input type="file" class="d-none" onchange="readFile(this)" id="candidate-img">
                <div class="row mt-2" id="candidate_list"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" onclick="submitCreateElection()">บันทึก</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="electionImageModal" tabindex="-1" role="dialog" aria-labelledby="electionImageModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">แก้ไขภาพเลือกตั้ง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="file" class="d-none" onchange="previewElectionImage(this)" id="election-img">
            <div class="modal-body" onclick="$('#election-img').click()">
                <div class="card">
                    <div class="card-body text-center">
                        <img class="img-fluid" src="" id="election-img-pre" style="max-height: 300px;" />
                        <i class="fas fa-file-upload"></i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" onclick="submitEditElectionImage()">บันทึก</button>
            </div>
        </div>
    </div>
</div>