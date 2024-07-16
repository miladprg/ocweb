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
?>

    <div class="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="todo-breadcrumb">
                        <div class="breadcrumb-main">
                            <h4 class="text-capitalize breadcrumb-title">
                                کاربران آنلاین
                                <span class="text-light fs-16">(تعداد کاربران آنلاین: <?php echo count($online_users);?>)</span>
                            </h4>
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
                                        <span class="userDatatable-title">شناسه</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">نام کاربری</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">مدت زمان</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">آیپی سرور</span>
                                    </th>
                                    <th style="width: 60px">
                                        <span class="userDatatable-title"></span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($online_users as $user) {
                                    $connection = preg_split('/\s+/', $user, -1, PREG_SPLIT_NO_EMPTY);
                                    if (count($connection)<5) continue;
                                ?>
                                <tr>
                                    <td>
                                        <div class="userDatatable-content">
                                            <?php echo $connection[0];?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-14">
                                            <?php echo $connection[1];?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="userDatatable-content">
                                            <?php echo $connection[6];?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="userDatatable-content">
                                            <?php echo $connection[3];?>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="?disconnect=<?php echo $connection[1];?>" class="remove">
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