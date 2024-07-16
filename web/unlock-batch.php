<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

if (isset($_POST["UnlockUsers"])) {
    UsersDatabase::connect(DATABASE);

    $input = $_POST["UnlockUsers"]["list"];
    $input = explode("\n", $input);
    $connections = "";
    foreach ($input as $user) {
        if (!$user) continue;
        UsersDatabase::changestatus(trim($user), "قفل شده");
        terminal("unlock_user ".trim($user));
    }
    UsersDatabase::closeConnection();

    route("users-list.php");
}
?>

<div class="contents">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="todo-breadcrumb">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">آنلاک کاربر</h4>
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
                                    <h6>آنلاک گروهی کاربران</h6>
                                </div>
                                <div class="card-body pb-md-30">
                                    <form method="post">
                                        <button type="submit" class="btn btn-primary btn-sm position-absolute top-0 mt-3" style="left: 30px">آنلاک</button>
                                        <span>
                                            </span>
                                        <div class="clearfix"></div>
                                        <div class="form-group form-element-textarea mt-2 mb-20">
                                                <span class="fs-13 text-danger">
                                                    برای آنلاک گروهی کاربران در هر ردیف نام کاربری را وارد کنید. مثال:
                                                    <br>
                                                    username1
                                                    <br>
                                                    username2
                                                    <br>
                                                    ...
                                                </span>
                                            <textarea class="form-control mt-1" style="height: 600px" name="UnlockUsers[list]" dir="ltr" placeholder="username"></textarea>
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
