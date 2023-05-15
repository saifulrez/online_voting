<div class="d-flex justify-content-end">
    <button class="btn btn-primary btn-md col-md-3 mb-3" onclick="createAccountModal()">เพิ่มบัญชี</button>
</div>
<div class="border">
    <table class="table" id="table-account">
        <thead>
            <tr class="text-center">
                <td>ชื่อผู้ใช้</td>
                <td>สถานะ</td>
                <td>จัดการ</td>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <script>
        function addAccountToTable(account) {
            var tr = $('<tr class="text-center"></tr>');
            tr.append($('<td></td>').text(account.username));
            tr.append($('<td></td>').text(account.role == 'admin' ? 'ผู้ดูแลระบบ' : 'ผู้ใช้งานทั่วไป'));
            tr.append($('<td></td>').append($('<i class="far fa-edit"></i>').attr('onclick', 'editAccountModal(' + account.id + ')')).append($('<i class="fa-solid fa-key"></i>').attr('onclick', 'changePasswordModal(' + account.id + ')')));
            $('#table-account tbody').append(tr);
        }

        function editAccountModal(id) {
            $.get({
                "url": "/API/admin/account.php",
                "data": {
                    "id": id
                }
            }).done((response) => {
                try {
                    var account = JSON.parse(response);
                    if (account.success) {
                        account = account.account;
                    } else {
                        console.error('request account failed');
                        return;
                    }
                } catch (e) {
                    console.error(e);
                    return;
                }
                $('#modal-account-edit').modal('show');
                $('#modal-account-edit-id').val(account.id);
                $('#modal-account-edit-username').val(account.username);
                $('#modal-account-edit-pre_fix').val(account.pre_fix);
                $('#modal-account-edit-FirstName').val(account.FirstName);
                $('#modal-account-edit-LastName').val(account.LastName);
                $('#modal-account-edit-role').val(account.role);
                $('#modal-account-edit-currentRole').val(account.role);
            });
        }

        function submitEditAccount() {
            var role = $('#modal-account-edit-role').val();
            var currentRole = $('#modal-account-edit-currentRole').val();
            if (role != currentRole) {
                Swal.fire({
                    title: 'สถานะบัญชีมีการเปลี่ยนแปลง',
                    text: 'คุณต้องการเปลี่ยนสถานะผู้ใช้งานนี้เป็น ' + (role == 'admin' ? 'ผู้ดูแลระบบ' : 'ผู้ใช้งานทั่วไป') + ' ใช่หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        editAccount(true);
                    }
                })
            } else {
                editAccount();
            }
        }

        function editAccount(roleIsChange = false) {
            var id = $('#modal-account-edit-id').val();
            var username = $('#modal-account-edit-username').val();
            var pre_fix = $('#modal-account-edit-pre_fix').val();
            var FirstName = $('#modal-account-edit-FirstName').val();
            var LastName = $('#modal-account-edit-LastName').val();
            var role = $('#modal-account-edit-role').val();
            $.post({
                "url": "/API/admin/edit_account.php",
                "data": {
                    "id": id,
                    "username": username,
                    "pre_fix": pre_fix,
                    "FirstName": FirstName,
                    "LastName": LastName,
                    "role": role
                }
            }).done((response) => {
                try {
                    var account = JSON.parse(response);
                    if (account.success) {
                        account = account.account;
                        $('#modal-account-edit').modal('hide');
                        if (roleIsChange) {
                            loadAccount();
                        }
                    } else {
                        console.error('edit account failed');
                        return;
                    }
                } catch (e) {
                    console.error(e);
                    return;
                }
            });
        }

        function loadAccount() {
            $.get({
                "url": "/API/admin/account.php"
            }).done((response) => {
                try {
                    var account = JSON.parse(response);
                } catch (e) {
                    console.error(e);
                    return;
                }
                $('#table-account tbody').empty();
                account.accountList.forEach((account) => {
                    addAccountToTable(account);
                });
                if ($.fn.dataTable.isDataTable('#table-account')) {
                    $('#table-account').DataTable();
                } else {
                    $('#table-account').DataTable({
                        "autoWidth": false
                    });
                }
            })
        }

        function createAccountModal() {
            $('#modal-account-create').modal('show');
        }

        function submitCreateAccount() {
            var username = $('#modal-account-create-username').val();
            var password = $('#modal-account-create-password').val();
            var pre_fix = $('#modal-account-create-pre_fix').val();
            var FirstName = $('#modal-account-create-FirstName').val();
            var LastName = $('#modal-account-create-LastName').val();
            var role = $('#modal-account-create-role').val();
            $.post({
                url: '/API/admin/create_account.php',
                data: {
                    "username": username,
                    "password": password,
                    "pre_fix": pre_fix,
                    "FirstName": FirstName,
                    "LastName": LastName,
                    "role": role
                }
            }).then((response) => {
                try {
                    var res = JSON.parse(response);
                    if (res.success) {
                        $('#modal-account-create').modal('hide');
                        loadAccount();
                        Swal.fire(
                            'เพิ่มบัญชีสำเร็จ',
                            'ทำการเพิ่มบัญชีสำเร็จ',
                            'success'
                        )
                        $('#modal-account-create-username').val('');
                        $('#modal-account-create-password').val('');
                        $('#modal-account-create-pre_fix').val('');
                        $('#modal-account-create-FirstName').val('');
                        $('#modal-account-create-LastName').val('');
                        $('#modal-account-create-role').val('user');
                    } else {
                        console.error('create account failed');
                        return;
                    }
                } catch (e) {
                    console.error(e);
                    return;
                }
            })
        }

        function changePasswordModal(id) {
            $('#modal-account-change-password').modal('show');
            $("#modal-account-change-password-id").val(id);
            $("#modal-account-change-password-new-password").val('');
            $("#modal-account-change-password-confirm-new-password").val('');
        }

        function submitChangePasswordAccount() {
            var id = $('#modal-account-change-password-id').val();
            var password = $('#modal-account-change-password-new-password').val();
            var confirmPassword = $('#modal-account-change-password-confirm-new-password').val();
            if (password == confirmPassword) {
                $.post({
                    url: '/API/admin/change_password.php',
                    data: {
                        "id": id,
                        "password": password
                    }
                }).then((response) => {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            $('#modal-account-change-password').modal('hide');
                            Swal.fire(
                                'เปลี่ยนรหัสผ่านสำเร็จ',
                                'ทำการเปลี่ยนรหัสผ่านสำเร็จ',
                                'success'
                            )
                        } else {
                            console.error('change password failed');
                            return;
                        }
                    } catch (e) {
                        console.error(e);
                        return;
                    }
                })
            } else {
                Swal.fire({
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณากรอกรหัสผ่านให้ตรงกัน',
                    icon: 'error'
                })
            }
        }
        $(document).ready(function() {
            loadAccount();
        });
    </script>
    <div class="modal fade" id="modal-account-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แก้ไขบัญชี</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>#</label>
                        <input type="number" name="id" class="form-control" id="modal-account-edit-id" readonly>
                    </div>
                    <div class="form-group">
                        <label>ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" id="modal-account-edit-username" readonly>
                    </div>
                    <div class="form-group">
                        <label>คำนำหน้า</label>
                        <input type="text" name="pre_fix" class="form-control" id="modal-account-edit-pre_fix">
                    </div>
                    <div class="form-group">
                        <label>ชื่อ</label>
                        <input type="text" name="FirstName" class="form-control" id="modal-account-edit-FirstName">
                    </div>
                    <div class="form-group">
                        <label>นามสกุล</label>
                        <input type="text" name="LastName" class="form-control" id="modal-account-edit-LastName">
                    </div>
                    <div class="form-group">
                        <label>สถานะ</label>
                        <input type="hidden" name="currentRole" id="modal-account-edit-currentRole">
                        <select class="form-control" id="modal-account-edit-role">
                            <option value="admin">ผู้ดูแลระบบ</option>
                            <option value="user">ผู้ใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditAccount()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-account-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">สร้างบัญชี</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" id="modal-account-create-username">
                    </div>
                    <div class="form-group">
                        <label>รหัสผ่าน</label>
                        <input type="password" name="password" class="form-control" id="modal-account-create-password">
                    </div>
                    <div class="form-group">
                        <label>คำนำหน้า</label>
                        <input type="text" name="pre_fix" class="form-control" id="modal-account-create-pre_fix">
                    </div>
                    <div class="form-group">
                        <label>ชื่อ</label>
                        <input type="text" name="FirstName" class="form-control" id="modal-account-create-FirstName">
                    </div>
                    <div class="form-group">
                        <label>นามสกุล</label>
                        <input type="text" name="LastName" class="form-control" id="modal-account-create-LastName">
                    </div>
                    <div class="form-group">
                        <label>สถานะ</label>
                        <select class="form-control" id="modal-account-create-role">
                            <option value="admin">ผู้ดูแลระบบ</option>
                            <option value="user" selected>ผู้ใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="submitCreateAccount()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-account-change-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนรหัสผ่าน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="modal-account-change-password-id" id="modal-account-change-password-id">
                    <div class="form-group">
                        <label>รหัสผ่านใหม่</label>
                        <input type="password" name="password" class="form-control" id="modal-account-change-password-new-password">
                    </div>
                    <div class="form-group">
                        <label>ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" name="password" class="form-control" id="modal-account-change-password-confirm-new-password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="submitChangePasswordAccount()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
</div>