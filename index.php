<?php
require_once("_system/config.php");
require_once("_system/database.php");
require_once('_system/oop.php');
$datenow = date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" type="text/css" rel="stylesheet">
    <link href="./asset/css/main.css" rel="stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.14.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://kit.fontawesome.com/b94fe2fbf1.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>
    <link href="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="./asset/js/main.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark primary-color">
        <a class="navbar-brand" href="./">Voting</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav" aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="basicExampleNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./">หน้าหลัก</a>
                </li>
                <?php if (isset($_SESSION["username"])) { ?>
                    <li class="nav-item">
                        <div class="nav-link"><?php echo $_SESSION["username"]; ?></div>
                    </li>
                    <?php
                    if (isAdmin($_SESSION['u_id'])) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=admin">จัดการระบบ</a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=logout">ออกจากระบบ</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=login">เข้าสู่ระบบ</a>
                    </li>
                <?php } ?>
            </ul>
            <div class="form-inline md-form my-0">
                <input class="mr-sm-2 placeholder-white" type="text" placeholder="Search" name="keyword" aria-label="Search">
            </div>
        </div>
    </nav>
    <div class="container-md" style="margin-bottom: 30px; margin-top: 30px;">
        <?php if (!$_GET) {
            $_GET["page"] = 'home';
        }
        if (!isset($_GET['page'])) {
            $_GET['page'] = 'home';
        }
        if (!$_GET["page"]) {
            $_GET["page"] = "home";
        }
        if ($_GET["page"] == "home") {
            include_once __DIR__ . '/_page/home.php';
        } elseif ($_GET['page'] == "detail") {
            include_once __DIR__ . '/_page/detail.php';
        } elseif ($_GET['page'] == "election") {
            include_once __DIR__ . '/_page/election.php';
        } elseif ($_GET['page'] == "result") {
            include_once __DIR__ . '/_page/result.php';
        } elseif ($_GET['page'] == "login") {
            include_once __DIR__ . '/_page/login.php';
        } elseif ($_GET['page'] == "logout") {
            include_once __DIR__ . '/_page/logout.php';
        } elseif ($_GET['page'] == "admin") {
            include_once __DIR__ . '/_page/admin.php';
        } else {
            echo '<div class="container"><div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> ไม่พบหน้าที่ท่านร้องขอ กำลังพาท่านกลับไปหน้าหลัก...</div></div>';
            echo '<meta http-equiv="refresh" content="3;URL=?page=home"/>';
        } ?>
    </div>
    <br>
    <footer class="font-small">
        <div class="text-center py-3 blue">© <?php echo date("Y"); ?> Copyright:
            <a href="https://mdbootstrap.com/" class="text-white"> MDBootstrap.com || Modifiled & Coding by ForestCrazy</a>
        </div>
    </footer>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>
</body>

</html>