<?php
require_once "./assets/pages/header.php";
if (!isUserAuthenticated()) route("index.php");

if (isset($_POST["rebuild"]) || isset($_GET["rebuild"])) {
    terminal("flush_ocpasswd");
    terminal("flush_groups");
    UsersDatabase::connect(DATABASE);
    $users = UsersDatabase::getAllUsers();
    foreach ($users as $user) {
        terminal("add_user $user->username $user->password");
        if ($user->group != "*") {
            terminal("create_group $user->group");
            terminal("change_user_group $user->username $user->group");
        }
        if ($user->status!="فعال") terminal("lock_user $user->username");
    }
    UsersDatabase::closeConnection();
    route("users-list.php");
}
?>

    <div class="contents">
        <div class="dm-page-content">
            <div class="container-fluid">
                <div class="row mt-5">
                    <div class="col-8 mx-auto">
                        <div class="card card-default card-md mb-4">
                            <div class="card-header">
                                <h6>بازسازی فایل اطلاعات کاربران</h6>
                            </div>
                            <div class="card-body">
                                <div class="dm-empty text-center">
                                    <div class="dm-empty__image">
                                        <img src="img/svg/3.svg" alt="Admin Empty">
                                    </div>
                                    <div class="dm-empty__text">
                                        <p>فایل فعلی حاوی اطلاعات کاربران حذف شده و مجددا بر اساس اطلاعات دیتابیس ساخته خواهد شد</p>
                                        <div class="dm-empty__action">
                                            <form method="post">
                                                <button class="btn btn-primary btn-sm btn-squared" name="rebuild">شروع</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ends: .card -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ends: .dm-page-content -->

<?php
require_once "./assets/pages/footer.php";
?>