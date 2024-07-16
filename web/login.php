<?php
require_once "./assets/pages/header.php";
if (isUserAuthenticated()) route("index.php");

if (isset($_POST["Login"]) && !empty($_POST["Login"])) {
    $detail = (object) $_POST["Login"];
    if (strlen($detail->username) >= 4 && strlen($detail->username) <= 55 && strlen($detail->password) >= 4 && strlen($detail->password) <= 55) {
        $username = htmlspecialchars($detail->username, ENT_QUOTES, 'UTF-8');;
        $password = htmlspecialchars($detail->password, ENT_QUOTES, 'UTF-8');;
        $loginRes = login($username, $password);
        if ($loginRes) route("index.php");
        else $error = "";
    } else {
        $error = "";
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
                                <h6>ورود به سیستم</h6>
                            </div>
                            <div class="card-body">
                                <div class="basic-form-wrapper">
                                    <?php if (isset($error)) error_login_failed();?>
                                    <form method="post">
                                        <div class="form-basic">
                                            <div class="form-group mb-25">
                                                <label for="username">نام کاربری</label>
                                                <input class="form-control form-control-lg" type="text" id="username" name="Login[username]" pattern="[a-z0-9]*" minlength="4" maxlength="50" placeholder="نام کاربری" required>
                                            </div>
                                            <div class="form-group mb-25">
                                                <label for="password-field">رمز عبور</label>
                                                <div class="position-relative">
                                                    <input id="password-field" type="password" name="Login[password]" class="form-control form-control-lg" minlength="4" maxlength="50" placeholder="رمز عبور" required>
                                                    <span class="uil uil-eye-slash text-lighten fs-15 field-icon toggle-password2"></span>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <button type="submit" class="btn btn-lg btn-primary btn-submit">ورود</button>
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

