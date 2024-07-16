<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

AdminsDatabase::connect(DATABASE);

if ($_GET["del"]) {
    AdminsDatabase::deleteAdmin($_GET["del"]);
    AdminsDatabase::closeConnection();
    route();
}

if (isset($_POST["AddAdmin"])) {
    $admin = (object) $_POST["AddAdmin"];
    if (AdminsDatabase::isAdminExist($admin->username)) route();
    else {
        AdminsDatabase::addAdmin($admin->fullname, $admin->username, $admin->password, $admin->type, get_current_date());
    }
}

$admins = AdminsDatabase::getAllAdmins();

AdminsDatabase::closeConnection();
?>

    <div class="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="contact-breadcrumb">
                        <div class="breadcrumb-main add-contact justify-content-sm-between ">
                            <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                                <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
                                    <h4 class="text-capitalize fw-500 breadcrumb-title">لیست مدیر</h4>
                                    <span class="sub-title ms-sm-25 ps-sm-25"></span>
                                </div>
                            </div>
                            <div class="action-btn">
                                <a href="#" class="btn px-15 btn-primary" data-bs-toggle="modal" data-bs-target="#add-contact">
                                    <i class="las la-plus fs-16"></i>افزودن مدیر
                                </a>

                                <!-- Modal -->
                                <div class="modal fade add-contact " id="add-contact" role="dialog" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content radius-xl">
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-500" id="staticBackdropLabel">اطلاعات ادمین</h6>
                                                <button type="button" class="close shadow-none border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                                                    <img src="img/svg/x.svg" alt="x" class="svg">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="add-new-contact">
                                                    <form method="post">
                                                        <div class="form-group mb-20">
                                                            <label for="fullname">نام</label>
                                                            <input type="text" id="fullname" class="form-control form-control-lg" name="AddAdmin[fullname]" minlength="4" maxlength="50" placeholder="نام و نام خانوادگی" required>
                                                        </div>
                                                        <div class="form-group mb-20">
                                                            <label for="username">نام کاربری</label>
                                                            <input type="text" id="username" class="form-control form-control-lg" name="AddAdmin[username]" pattern="[a-z0-9]*" minlength="4" maxlength="50" placeholder="نام کاربری" required>
                                                        </div>
                                                        <div class="form-group mb-20">
                                                            <label for="password">رمز عبور</label>
                                                            <input type="text" id="password" class="form-control form-control-lg" name="AddAdmin[password]" pattern="[a-z0-9]*" minlength="4" maxlength="50" placeholder="رمز عبور" required>
                                                        </div>
                                                        <div class="mb-25 select-style">
                                                            <span class=" color-dark fs-14 fw-500 align-center mb-10">سمت</span>
                                                            <div class="dm-select">
                                                                <select name="AddAdmin[type]" id="select-alerts2" class="form-control">
                                                                    <option value="ادمین">ادمین</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="button-group d-flex pt-20">
                                                            <button type="submit" class="btn btn-primary btn-default btn-squared ">افزودن مدیر</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="contact-list-wrap mb-25">
                        <div class="contact-list radius-xl w-100">
                            <div class="table-responsive table-responsive--dynamic">
                                <table class="table mb-0 table-borderless table-rounded">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>آیدی</span>
                                        </th>
                                        <th>
                                            <span>نام</span>
                                        </th>
                                        <th>
                                            <span>نام کاربری</span>
                                        </th>
                                        <th>
                                            <span>نوع</span>
                                        </th>
                                        <th>
                                            <span>تاریخ</span>
                                        </th>
                                        <th style="width: 40px">
                                            <span class="float-end"></span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($admins as $admin) {
                                    ?>

                                    <tr>
                                        <td>
                                            <span><?php echo $admin->id;?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $admin->fullname;?></span>
                                        </td>
                                        <td>
                                            <span><?php echo $admin->username;?></span>
                                        </td>
                                        <td>
                                            <span><?php echo ($admin->type=="administrator") ? "مدیر سیستم" : $admin->type;?></span>
                                        </td>
                                        <td>
                                            <span><?php echo get_date_by_timestamp($admin->addDate);?></span>
                                        </td>
                                        <td>
                                            <div class="table-actions <?php if ($admin->type=="administrator") echo 'd-none';?>">
                                                <div class="dropdown dropdown-click">
                                                    <button class="btn-link border-0 bg-transparent p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <img class="svg" src="img/svg/more-vertical.svg" alt="more-vertical">
                                                    </button>
                                                    <div class="dropdown-default dropdown-menu dropdown-menu--dynamic">
                                                        <a class="dropdown-item" href="change-password.php?change-password=<?php echo $admin->username;?>">تغییر رمز</a>
                                                        <a class="dropdown-item" href="?del=<?php echo $admin->id;?>">حذف</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ends: col-12 -->
            </div>
        </div>

        <?php
            require_once "./assets/pages/footer.php";
        ?>