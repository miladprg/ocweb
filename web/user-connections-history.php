<?php
require_once "./assets/pages/header.php";
if (!isUserAuthenticated()) route("login.php");

if (!isset($_GET["user"]) || empty($_GET["user"])) route("users-list.php");

$connections = file("/etc/ocserv/logs.log");

ServersDatabase::connect(DATABASE);
foreach (ServersDatabase::getAllServers() as $server) {
    $tmp = run_command_to_server($server->ip, "root", $server->password, $server->port, "cat /etc/ocserv/logs.log");
    $connections = array_merge($connections, explode("\n", $tmp));
}
$user_connections = array_filter($connections, function ($value) {
    return strpos($value, $_GET["user"]) !== false;
});

if (empty($user_connections)) route("users-list.php");

$user_connections_times = array_map(function ($value){
    $tmp = explode(" ", trim($value));
    if (strlen($tmp[1])==strlen($_GET["user"])) return (int) $tmp[0];
}, $user_connections);

rsort($user_connections_times);

ServersDatabase::closeConnection();

?>

    <div class="contents">

        <div class="demo7 mb-25 t-thead-bg">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-main">
                            <h4 class="breadcrumb-title">
                                تاریخچه اتصالات کاربر
                                '<?php echo $_GET["user"];?>'
                            </h4>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-default card-md mb-4">
                            <div class="card-body">
                                <div class="list-box">
                                    <ul class="row mx-auto">
                                        <?php foreach ($user_connections_times as $connections_time) { ?>
                                        <li class="p-1 col-12 col-md-4 col-xl-3 text-center"><p class="border text-success border-success fs-13 p-3 rounded"><?php echo get_datetime_by_timestamp($connections_time);?></p></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <!-- ends: .list-box -->
                            </div>
                        </div>
                        <!-- ends: .card -->
                        <a href="users-list.php" class="btn btn-primary btn-sm">بازگشت</a>
                    </div>
                </div>
                <!-- ends: .row -->
            </div>
        </div>

<?php
require_once "./assets/pages/footer.php";
?>