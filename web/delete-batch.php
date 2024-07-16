<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

if (isset($_POST["DeleteUsers"])) {
    UsersDatabase::connect(DATABASE);

    $input = $_POST["DeleteUsers"]["list"];
    $input = explode("\n", $input);
    $connections = "";
    foreach ($input as $user) {
        if (!$user) continue;
        UsersDatabase::deleteUser(trim($user));
        terminal("delete_user ".trim($user));
        $connections .= "sed -i '/".trim($user)."/d' /etc/ocserv/logs.log; ";
    }
    UsersDatabase::closeConnection();
    ServersDatabase::connect(DATABASE);
    foreach (ServersDatabase::getAllServers() as $server) {
        run_command_to_server($server->ip, "root", $server->password, $server->port, "$connections");
    }
    ServersDatabase::closeConnection();

    route("users-list.php");
}
?>

<div class="contents">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="todo-breadcrumb">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">حذف کاربر</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-2">
        <div class="form-element">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-default card-md mb-4">
                                <div class="card-header">
                                    <h6>حذف گروهی کاربران</h6>
                                </div>
                                <div class="card-body pb-md-30">
                                    <form method="post">
                                        <button type="submit" class="btn btn-primary btn-sm position-absolute top-0 mt-3" style="left: 30px">حذف</button>
                                        <span>
                                            </span>
                                        <div class="clearfix"></div>
                                        <div class="form-group form-element-textarea mt-2 mb-20">
                                                <span class="fs-13 text-danger">
                                                    برای حذف گروهی کاربران در هر ردیف نام کاربری را وارد کنید. مثال:
                                                    <br>
                                                    username1
                                                    <br>
                                                    username2
                                                    <br>
                                                    ...
                                                </span>
                                            <textarea class="form-control mt-1" style="height: 600px" name="DeleteUsers[list]" dir="ltr" placeholder="username"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once "./assets/pages/footer.php";
    ?>
