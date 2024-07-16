<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

$file_path = "/etc/ocserv/ocpasswd";

if (isset($_POST["content"])) {
        file_put_contents($file_path, $_POST["content"]);
}

$content = file_get_contents("$file_path");
?>

    <div class="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="todo-breadcrumb">
                        <div class="breadcrumb-main">
                            <h4 class="text-capitalize breadcrumb-title">ویرایش لیست کاربران</h4>
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
                                        <h6>ویرایش فایل اطلاعات کاربران</h6>
                                    </div>
                                    <div class="card-body pb-md-30">
                                        <form method="post">
                                            <button type="submit" class="btn btn-primary btn-sm position-absolute top-0 mt-3" style="left: 30px">ذخیره</button>
                                            <div class="clearfix"></div>
                                            <div class="form-group form-element-textarea mt-2 mb-20">
                                                <textarea name="content" class="form-control" style="height: 600px" dir="ltr"><?php print_r($content);?></textarea>
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
