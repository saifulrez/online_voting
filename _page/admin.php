<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    if (isAdmin($_SESSION['u_id'])) {
        if (!isset($_GET['admin'])) {
            gotoPage('admin&admin=account');
        }
?>
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group" id="sidenav-admin">
                    <li class="list-group-item" id="sidenav-menu-1"><a class="text-dark" href="?page=admin&admin=account">บัญชีในระบบ</a></li>
                    <li class="list-group-item" id="sidenav-menu-2"><a class="text-dark" href="?page=admin&admin=election">รายการเลือกตั้ง</a></li>
                </ul>
            </div>
            <div class="col-md-9">
                <?php
                if ($_GET['admin'] == 'account') {
                    include_once __DIR__ . '/admin/account.php';
                } elseif ($_GET['admin'] == 'election') {
                    include_once __DIR__ . '/admin/election.php';
                } else {
                    gotoPage('admin');
                }
                ?>
            </div>
        </div>
        <script>
            $(document).ready(() => {
                switch (getUrlParams('admin')) {
                    case 'account':
                        $('#sidenav-admin > #sidenav-menu-1').addClass('active');
                        $('#sidenav-admin > #sidenav-menu-1 > a').removeClass('text-dark').addClass('text-white');
                        break;
                    case 'election':
                        $('#sidenav-admin > #sidenav-menu-2').addClass('active');
                        $('#sidenav-admin > #sidenav-menu-2 > a').removeClass('text-dark').addClass('text-white');
                        break;
                }
            })
        </script>
<?php
    } else {
        gotoPage('home');
    }
}
