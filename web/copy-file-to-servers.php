<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

if (isset($_POST["CopyFile"])) {
    ServersDatabase::connect(DATABASE);
    $copy = (object) $_POST["CopyFile"];
    foreach (ServersDatabase::getAllServers() as $server) {
        copy_file_to_server($server->ip, "root", $server->password, $server->port, $copy->source, $copy->destination);
    }
    ServersDatabase::closeConnection();
}
?>

    <div class="contents">
        <div class="ps-2 pe-2">
            <div class="col-lg-6 mx-auto mt-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-default card-md mb-5">
                            <div class="card-header">
                                <h6>کپی فایل از سرور اصلی به دیگر سرورها</h6>
                            </div>
                            <div class="card-body pb-md-30">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="a11" class="il-gray fs-14 fw-500 align-center mb-10">مسیر کامل فایل مبدا</label>
                                        <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="a11" dir="ltr" name="CopyFile[source]" minlength="3" maxlength="300" placeholder="/root/script.sh" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="a12" class="il-gray fs-14 fw-500 align-center mb-10">مسیر کامل فایل مقصد</label>
                                        <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="a12" dir="ltr" name="CopyFile[destination]" minlength="3" maxlength="300" placeholder="/root/script.sh" required>
                                    </div>
                                    <div class="form-group form-element-textarea mt-3 mb-10">
                                        <button type="submit" class="btn btn-primary">کپی فایل</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            require_once "./assets/pages/footer.php";
        ?>