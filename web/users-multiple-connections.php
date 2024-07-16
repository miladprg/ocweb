<?php
require_once "./assets/pages/header.php";
if (!isUserAuthenticated()) route("index.php");

ServersDatabase::connect(DATABASE);

if (isset($_GET["disconnect"])) {
    $user = $_GET["disconnect"];
    terminal("disconnect_user $user");
    foreach (ServersDatabase::getAllServers() as $server) {
        run_command_to_server($server->ip, "root", $server->password, $server->port, "occtl disconnect user $user");
    }
    ServersDatabase::closeConnection();
    route();
}

$online_users = shell_exec("sudo occtl show users");
$online_users = explode("\n", $online_users);
array_shift($online_users);

foreach (ServersDatabase::getAllServers() as $server) {
    $onlines = run_command_to_server($server->ip, "root", $server->password, $server->port, "occtl show users");
    $onlines = explode("\n", $onlines);
    array_shift($onlines);
    $online_users = array_merge($online_users, $onlines);
}
ServersDatabase::closeConnection();

$multiple_connections = array_map(function ($value){
    if ($value) return preg_split('/\s+/', $value, -1, PREG_SPLIT_NO_EMPTY)[1];
}, $online_users);

$multiple_connections = array_count_values($multiple_connections);

$multiple_connections = array_filter($multiple_connections, function ($value) {
    return $value >= 2;
});

$multiple_connections = array_diff($multiple_connections, ["(none)"]);

UsersDatabase::connect(DATABASE);
$users_detail = UsersDatabase::getUsersByUsername(array_keys($multiple_connections));
UsersDatabase::closeConnection();

$users_detail = array_map(function ($value) {
    if ($value->group=="*") $value->group = 1;
    return $value;
}, $users_detail);

$offender_users = array_map(function ($value) {
    global $multiple_connections;
    if ($multiple_connections[$value->username] > $value->group)
        return (object) [
            "username" => $value->username,
            "max_users" => $value->group,
            "connections" => $multiple_connections[$value->username]
        ];
}, $users_detail);


if (isset($_GET["disconnectAll"])) {
    $offender_users = array_map(function ($value) {
        return "occtl disconnect user $value->username;";
    }, $offender_users);
    $disconnect_string = implode(" ", $offender_users);
    shell_exec("sudo $disconnect_string");
    ServersDatabase::connect(DATABASE);
    foreach (ServersDatabase::getAllServers() as $server) {
        run_command_to_server($server->ip, "root", $server->password, $server->port, "$disconnect_string");
    }
    ServersDatabase::closeConnection();
    route();
}
?>

    <div class="contents">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="todo-breadcrumb">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">
                            کاربران با چند کانکشن
                        </h4>
                        <a href="?disconnectAll=1" class="btn btn-primary float-start btn-sm">قطع اتصال همه</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
    <div class="card">
        <div class="card-body p-0">
            <div class="table4 p-25 mb-30">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr class="userDatatable-header">
                            <th>
                                <span class="userDatatable-title">نام کاربری</span>
                            </th>
                            <th>
                                <span class="userDatatable-title">تعداد کانکشن تعریف شده</span>
                            </th>
                            <th>
                                <span class="userDatatable-title">تعداد کانکشن متصل</span>
                            </th>
                            <th style="width: 60px">
                                <span class="userDatatable-title"></span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($offender_users as $offender_user) {
                            ?>
                            <tr>
                                <td>
                                    <div class="fs-14">
                                        <?php echo $offender_user->username;?>
                                    </div>
                                </td>
                                <td>
                                    <div class="userDatatable-content">
                                        <?php echo $offender_user->max_users;?>
                                    </div>
                                </td>
                                <td>
                                    <div class="userDatatable-content">
                                        <?php echo $offender_user->connections;?>
                                    </div>
                                </td>
                                <td>
                                    <a href="?disconnect=<?php echo $offender_user->username;?>" class="remove">
                                        <i class="bg-opacity-danger color-danger fs-6 p-1 rounded-pill uil uil-cloud-times"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
require_once "./assets/pages/footer.php";
?>