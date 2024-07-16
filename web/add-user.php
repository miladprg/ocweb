<?php
require_once "assets/pages/header.php";
if (!isUserAuthenticated()) route("index.php");

UsersDatabase::connect(DATABASE);
ServersDatabase::connect(DATABASE);

if (isset($_GET["type"]) ) {
    $user = UsersDatabase::checkUserExist($_GET["username"]);
    if (!$user) {
        UsersDatabase::closeConnection();
        route("users-list.php");
    }
}

if (isset($_POST["AddUser"])) {
    $user = (object) $_POST["AddUser"];
    if (!$user->group) $user->group = "*";
    $command_create = "echo -e \"$user->password\n$user->password\" | ocpasswd -c /etc/ocserv/ocpasswd $user->username";
    if (isset($user->edit)) {
        UsersDatabase::editUser($user->edit, '', $user->username, $user->password, '', $user->group, $user->status);
        UsersDatabase::closeConnection();
	    terminal("add_user $user->username $user->password");
        foreach (ServersDatabase::getAllServers() as $server) {
            run_command_to_server($server->ip, "root", $server->password, $server->port, $command_create);
        }
        if ($user->group != "*") {
            $command_changeGroup = "sed -i \"s/$user->username:.*:/$user->username:g$user->group:/g\" /etc/ocserv/ocpasswd";
            terminal("create_group $user->group");
            terminal("change_user_group $user->username g$user->group");
            foreach (ServersDatabase::getAllServers() as $server) {
                copy_file_to_server($server->ip, "root", $server->password, $server->port, "/etc/ocserv/group/g$user->group", "/etc/ocserv/group/g$user->group");
                run_command_to_server($server->ip, "root", $server->password, $server->port, $command_changeGroup);
            }
        }
        if ($user->status == "قفل شده") {
            $command_lockUser = "ocpasswd -l $user->username";
            terminal("lock_user $user->username");
            foreach (ServersDatabase::getAllServers() as $server) {
                run_command_to_server($server->ip, "root", $server->password, $server->port, $command_lockUser);
            }
        }
        route("users-list.php");
    }
    else if (UsersDatabase::checkUserExist($user->username)) $error = true;
    else {
        UsersDatabase::addUser("", $user->username, $user->password, "", $user->group, $user->status, get_current_date());
        UsersDatabase::closeConnection();
        $command_create = "echo -e \"$user->password\n$user->password\" | ocpasswd -c /etc/ocserv/ocpasswd $user->username";
        terminal("add_user $user->username $user->password");
        foreach (ServersDatabase::getAllServers() as $server) {
            run_command_to_server($server->ip, "root", $server->password, $server->port, $command_create);
        }
        if ($user->group != "*") {
            $command_changeGroup = "sed -i \"s/$user->username:.*:/$user->username:g$user->group:/g\" /etc/ocserv/ocpasswd";
            terminal("create_group $user->group");
            terminal("change_user_group $user->username g$user->group");
            foreach (ServersDatabase::getAllServers() as $server) {
                copy_file_to_server($server->ip, "root", $server->password, $server->port, "/etc/ocserv/group/g$user->group", "/etc/ocserv/group/g$user->group");
                run_command_to_server($server->ip, "root", $server->password, $server->port, $command_changeGroup);
            }
        }
        if ($user->status == "قفل شده") {
            $command_lockUser = "ocpasswd -l $user->username";
            terminal("lock_user $user->username");
            foreach (ServersDatabase::getAllServers() as $server) {
                run_command_to_server($server->ip, "root", $server->password, $server->port, $command_lockUser);
            }
        }
        route("users-list.php");
    }
}
ServersDatabase::closeConnection();
UsersDatabase::closeConnection();
?>

    <div class="contents">
        <div class="p-2 pt-5">
            <div class="col-lg-6 mx-auto mt-md-5">
                <div class="card card-Vertical card-default card-md mb-4">
                    <div class="card-header">
                        <h6>ویرایش یا اضافه کردن کاربر</h6>
                    </div>
                    <div class="card-body pb-md-30">
                        <div class="Vertical-form">
                            <?php if (isset($error)) error_username_exist(); ?>
                            <form method="post">
                                <div class="form-group">
                                    <label for="formGroupExampleInput4" class=" color-dark fs-14 fw-500 align-center mb-10">نام کاربری</label>
                                    <div class="with-icon">
                                        <span class="la-user lar"></span>
                                        <input type="text" name="AddUser[username]" pattern="[a-zA-Z0-9]+[a-zA-Z0-9$#@&_-]*" minlength="3" maxlength="50" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput4" <?php if (isset($user) && $user) {echo "value=\"$user->username\""; echo " readonly";}?> placeholder="نام کاربری" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput5" class=" color-dark fs-14 fw-500 align-center mb-10">رمز عبور</label>
                                    <div class="with-icon">
                                        <span class="las la-lock"></span>
                                        <input type="text" name="AddUser[password]" minlength="1" maxlength="50" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput5" value="<?php if (isset($_GET["username"])) echo $user->password;?>" placeholder="رمز عبور" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput10" class=" color-dark fs-14 fw-500 align-center mb-10">تعداد کانکشن</label>
                                    <div class="with-icon">
                                        <span class="la-sort-numeric-down las"></span>
                                        <input type="text" name="AddUser[group]" maxlength="2" class="form-control ih-medium ip-gray radius-xs b-light" pattern="[1-9][0-9]*" id="formGroupExampleInput10" value="<?php echo (isset($_GET["username"]) && $user->group != "*") ? $user->group : "";?>" placeholder="تعداد کانکشن">
                                    </div>
                                </div>
                                <div class="mb-25 select-style">
                                    <span class=" color-dark fs-14 fw-500 align-center mb-10">وضعیت</span>
                                    <div class="dm-select">
                                        <select name="AddUser[status]" id="select-alerts2" class="form-control">
                                            <option value="فعال" <?php if (isset($_GET["username"]) && $user->status=="فعال") echo "selected";?>>فعال</option>
                                            <option value="قفل شده"<?php if (isset($_GET["username"]) && $user->status=="قفل شده") echo "selected";?>>قفل شده</option>
                                        </select>
                                    </div>
                                </div>
                                <?php if (isset($_GET["username"])) echo "<input type='text' name='AddUser[edit]' value='$user->id' hidden>";?>
                                <div class="layout-button mt-25">
                                    <a type="button" href="users-list.php" class="btn btn-default btn-squared btn-light px-20 ">بازگشت</a>
                                    <button type="submit" class="btn btn-primary btn-default btn-squared px-30"><?php echo (isset($_GET["username"])) ? "ویرایش" : "افزودن";?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ends: .card -->

        <?php
            require_once "./assets/pages/footer.php";
        ?>
