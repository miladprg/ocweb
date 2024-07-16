<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

if (isset($_POST["AddUsers"])) {
    UsersDatabase::connect(DATABASE);

    $input = $_POST["AddUsers"]["list"];
    $input = explode("\n", $input);
    $users = [];
    foreach ($input as $user) {
        $user = explode(",", $user);
        $filteredUser = array_filter($user, function ($value) {
            return $value !== "" && $value !== null;
        });
        if (count($filteredUser) != 3) continue;
        UsersDatabase::deleteUserByUsername($filteredUser[0]);
        $users[] = [
            "fullname" => "",
            "username" => trim($filteredUser[0]),
            "password" => trim($filteredUser[1]),
            "reagent" => "",
            "group" => trim($filteredUser[2]),
            "status" => "فعال",
            "addDate" => get_current_date()
        ];
	terminal("add_user ".trim($filteredUser[0])." ".trim($filteredUser[1]));
	if (trim($filteredUser[2])!="*") {
                terminal("create_group ".trim($filteredUser[2]));
                terminal("change_user_group ".trim($filteredUser[0])." g".trim($filteredUser[2]));
        }
    }
    UsersDatabase::addBatchUser($users);
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
                            <h4 class="text-capitalize breadcrumb-title">ایمپورت کاربران</h4>
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
                                        <h6>افزودن کاربر گروهی</h6>
                                    </div>
                                    <div class="card-body pb-md-30">
                                        <form method="post">
                                            <button type="submit" class="btn btn-primary btn-sm position-absolute top-0 mt-3" style="left: 30px">ایمپورت</button>
                                            <span>
                                            </span>
                                            <div class="clearfix"></div>
                                            <div class="form-group form-element-textarea mt-2 mb-20">
                                                <span class="fs-13 text-danger">
                                                    درصورتی که نام کاربری از قبل در پایگاه داده وجود داشته باشد، کاربر قبلی حذف و کاربر جدید جایگزین خواهد شد.
                                                    <br>
                                                    برای اضافه کردن کاربران در هر ردیف نام کاربری و رمز عبور را که با کاما (,) از هم جدا شده اند وارد کنید. مثال:
                                                    <br>
                                                    username1,password1,group1
                                                    <br>
                                                    username2,password2,group2
                                                    <br>
                                                    ...
                                                </span>
                                                <textarea class="form-control mt-1" style="height: 600px" name="AddUsers[list]" dir="ltr" placeholder="username,password,group"></textarea>
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
