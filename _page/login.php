<?php
if (isset($_SESSION["username"])) { ?>
    <script langquage='javascript'>
        window.location = "?page=home";
    </script>
<?php } else { ?>
    <div class="d-flex justify-content-center">
        <div class="col col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">เข้าสู่ระบบ</h5>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="username">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="กรอกชื่อผู้ใช้">
                        </div>
                        <div class="form-group">
                            <label for="password">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่าน">
                        </div>
                        <input type="hidden" name="submit_login">
                        <button type="submit" class="btn btn-success">เข้าสู่ระบบ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST["submit_login"])) {
        $username = mysqli_real_escape_string($connect, $_POST["username"]);
        $sql_checkuser = 'SELECT id, username,password FROM account WHERE username = "' . $username . '"';
        $res_checkuser = mysqli_query($connect, $sql_checkuser);
        $num_checkuser = mysqli_num_rows($res_checkuser);
        if ($num_checkuser > 0) {
            $shapassword = hash('sha256', $_POST['password']);
            $fetch_checkuser = mysqli_fetch_assoc($res_checkuser);
            if ($shapassword == $fetch_checkuser["password"]) {
                $_SESSION["username"] = $fetch_checkuser["username"];
                $_SESSION['u_id'] = $fetch_checkuser['id'];
                $msg_alert = 'สำเร็จ!';
                $alert = 'success';
                $msg = 'ล็อกอินสำเร็จ';
            } else {
                $msg = 'รหัสผ่านไม่ถูกต้อง!';
                $alert = 'error';
                $msg_alert = 'เกิดข้อผิดพลาด!';
            }
        } else {
            $msg = 'ไม่พบชื่อผู้ใช้นี้ในระบบ';
            $alert = 'error';
            $msg_alert = 'เกิดข้อผิดพลาด!';
        } ?>
        <script>
            Swal.fire(
                '<?php echo $msg_alert ?>',
                '<?php echo $msg ?>',
                '<?php echo $alert ?>'
            ).then((value) => {
                window.location.href = window.location.href;
            });
        </script>
<?php }
} ?>