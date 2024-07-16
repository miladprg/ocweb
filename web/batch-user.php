<?php
require_once "assets/pages/header.php";
if (!isUserAuthenticated()) route("index.php");

if (isset($_POST["AddUser"])) {
    UsersDatabase::connect(DATABASE);

    $batch = (object) $_POST["AddUser"];
    if ($batch->group=="") $batch->group = "*";
    if ($batch->group!="*") terminal("create_group $batch->group");
    $users = [];
    for ($i=$batch->from; $i<=$batch->to; $i++) {
        $username = "$batch->prefix$i";
        $password = random_password_generator($batch->password_difficulty, $batch->password_length);
        $users[] = [
            "fullname" => "",
            "username" => $username,
            "password" => $password,
            "reagent" => "",
            "group" => $batch->group,
            "status" => "فعال",
            "addDate" => get_current_date()
        ];
	terminal("add_user $username $password");
	if ($batch->group!="*") terminal("change_user_group $username g$batch->group");
    }
    UsersDatabase::addBatchUser($users);
    UsersDatabase::closeConnection();
    route("users-list.php");
}
?>
    <div class="contents">
        <div class="p-2 pt-5">
            <div class="col-lg-6 mx-auto mt-md-1">
                <div class="card card-Vertical card-default card-md mb-4">
                    <div class="card-header">
                        <h6>اضافه کردن کاربر گروهی</h6>
                    </div>
                    <div class="card-body pb-md-30">
                        <div class="Vertical-form">
                            <form method="post">
                                <div class="form-group">
                                    <label for="formGroupExampleInput10" class=" color-dark fs-14 fw-500 align-center mb-10">تعداد کانکشن</label>
                                    <div class="with-icon">
                                        <span class="la-sort-numeric-down las"></span>
                                        <input type="text" name="AddUser[group]" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput10" pattern="[0-9][0-9]*" maxlength="2" placeholder="تعداد کانکشن">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput9" class=" color-dark fs-14 fw-500 align-center mb-10">پیشوند</label>
                                    <div class="with-icon">
                                        <span class="la-ad las"></span>
                                        <input type="text" name="AddUser[prefix]" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput9" pattern="[a-z][a-z0-9]*" minlength="1" maxlength="10" placeholder="پیشوند نام کاربری" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="formGroupExampleInput4" class=" color-dark fs-14 fw-500 align-center mb-10">از شماره</label>
                                        <div class="with-icon">
                                            <span class="la-sort-numeric-down las"></span>
                                            <input type="text" name="AddUser[from]" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput4" pattern="[0-9][0-9]*" minlength="1" maxlength="10" placeholder="از شماره" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="formGroupExampleInput7" class=" color-dark fs-14 fw-500 align-center mb-10">تا شماره</label>
                                        <div class="with-icon">
                                            <span class="la-sort-numeric-down las"></span>
                                            <input type="text" name="AddUser[to]" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput7" pattern="[0-9][0-9]*" minlength="1" maxlength="10" placeholder="تا شماره" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput8" class=" color-dark fs-14 fw-500 align-center mb-10">طول</label>
                                    <div class="with-icon">
                                        <span class="la-sort-numeric-down las"></span>
                                        <input type="text" name="AddUser[password_length]" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput8" pattern="[0-9][0-9]*" minlength="1" maxlength="2" placeholder="طول رمز عبور" required>
                                    </div>
                                </div>
                                <div class="form-group mt-10 mb-25">
                                    <span class=" color-dark fs-14 fw-500 align-center mb-10">کاراکتر های رمز عبور</span>
                                    <div class="custom-control custom-radio mb-3 ps-0">
                                        <input type="radio" class="radio" id="customControlValidation1" name="AddUser[password_difficulty]" value="easy" checked>
                                        <label for="customControlValidation1">اعداد</label>
                                        <br>
                                        <input type="radio" class="radio" id="customControlValidation2" name="AddUser[password_difficulty]" value="medium">
                                        <label for="customControlValidation2">اعداد و حروف کوچک انگلیسی</label>
                                        <br>
                                        <input type="radio" class="radio" id="customControlValidation3" name="AddUser[password_difficulty]" value="difficult">
                                        <label for="customControlValidation3">اعداد و حروف کوچک و حروف بزرگ انگلیسی</label>
                                    </div>
                                </div>
                                <div class="layout-button mt-25">
                                    <a type="button" href="users-list.php" class="btn btn-default btn-squared btn-light px-20 ">بازگشت</a>
                                    <button type="submit" class="btn btn-primary btn-default btn-squared px-30">ایجاد کاربر</button>
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
