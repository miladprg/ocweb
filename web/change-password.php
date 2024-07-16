<?php
require_once "./assets/pages/header.php";
if (!isUserAuthenticated()) route("index.php");

if (isset($_POST["ChangePassword"])) {
    $detail = (object) $_POST["ChangePassword"];
    if ($detail->password != $detail->repeat) {
        $error = "";
    } else {
        AdminsDatabase::connect(DATABASE);
        AdminsDatabase::changePassword($detail->username, $detail->password);
        AdminsDatabase::closeConnection();
        route("admins.php");
    }
}
?>

    <div class="contents">
        <div class="dm-page-content mt-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 mx-auto">
                        <div class="card card-default card-md mb-4">
                            <div class="card-header">
                                <h6>تغییر رمز عبور</h6>
                            </div>
                            <div class="card-body">
                                <div class="basic-form-wrapper">
                                    <?php if (isset($error)) error_password_not_same();?>
                                    <form method="post">
                                        <div class="form-basic">
                                            <div class="form-group mb-25">
                                                <label for="username">نام کاربری</label>
                                                <input class="form-control form-control-lg" type="text" id="username" name="ChangePassword[username]" placeholder="نام کاربری" value="<?php echo (isset($_GET['change-password'])) ? $_GET['change-password'] : $_SESSION['user']->username;?>" readonly>
                                            </div>
                                            <div class="form-group mb-25">
                                                <label for="password-field">رمز عبور</label>
                                                <div class="position-relative">
                                                    <input id="password-field" type="password" class="form-control form-control-lg" name="ChangePassword[password]" minlength="4" maxlength="50" placeholder="رمز عبور" required>
                                                    <span class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                                </div>
                                            </div>
                                            <div class="form-group mb-25">
                                                <label for="password-field2">تکرار رمز عبور</label>
                                                <div class="position-relative">
                                                    <input id="password-field2" type="password" class="form-control form-control-lg" name="ChangePassword[repeat]" minlength="4" maxlength="50" placeholder="تکرار رمز عبور" required>
                                                    <span class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <button type="submit" class="btn btn-lg btn-primary btn-submit">تغییر رمز</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- ends: .card -->
                    </div>
                    <!-- ends: .col-lg-6 -->
                </div>
            </div>
        </div>
        <!-- ends: .dm-page-content -->
        <?php
            require_once "./assets/pages/footer.php";
        ?>