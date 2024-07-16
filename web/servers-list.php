<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");
ServersDatabase::connect(DATABASE);

if (isset($_GET["del"])) {
    ServersDatabase::connect(DATABASE);
    ServersDatabase::deleteServer($_GET["del"]);
    ServersDatabase::closeConnection();
    route();
}

if (isset($_POST["AddServer"])) {
    $server = (object) $_POST["AddServer"];
    ServersDatabase::addServer($server->title, $server->ip, $server->password, $server->port, get_current_date());
    copy_file_to_server($server->ip, "root", $server->password, $server->port, "/etc/ocserv/logs.sh", "/etc/ocserv/logs.sh");
    run_command_to_server($server->ip, "root", $server->password, $server->port, 'new_cron="@reboot bash /etc/ocserv/logs.sh &"; $(if ! (crontab -l | grep -qF "$new_cron"); then $((crontab -l ; printf "\n#Do not remove below line\n0 1 * * * reboot\n" ; echo "$new_cron") | crontab -); fi); reboot');
}

$servers = ServersDatabase::getAllServers();

ServersDatabase::closeConnection();
?>

    <div class="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="contact-breadcrumb">

                        <div class="breadcrumb-main add-contact justify-content-sm-between ">
                            <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                                <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
                                    <h4 class="text-capitalize fw-500 breadcrumb-title">لیست سرورها</h4>
                                    <span class="sub-title ms-sm-25 ps-sm-25"></span>
                                </div>
                            </div>
                            <div class="action-btn">
                                <a href="#" class="btn px-15 btn-primary" data-bs-toggle="modal" data-bs-target="#add-contact">
                                    <i class="las la-plus fs-16"></i>افزودن سرور
                                </a>

                                <!-- Modal -->
                                <div class="modal fade add-contact " id="add-contact" role="dialog" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content radius-xl">
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-500" id="staticBackdropLabel">اطلاعات سرور</h6>
                                                <button type="button" class="close shadow-none border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                                                    <img src="img/svg/x.svg" alt="x" class="svg">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="add-new-contact">
                                                    <form method="post">
                                                        <div class="form-group mb-20">
                                                            <label>عنوان</label>
                                                            <input type="text" class="form-control form-control-lg" name="AddServer[title]" minlength="4" maxlength="50" placeholder="عنوان سرور" required>
                                                        </div>
                                                        <div class="form-group mb-20">
                                                            <label>آدرس سرور</label>
                                                            <input type="text" class="form-control form-control-lg" pattern="[1-9][0-9]{1,2}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}" name="AddServer[ip]" minlength="7" maxlength="15" placeholder="آدرس سرور" required>
                                                        </div>
                                                        <div class="form-group mb-20">
                                                            <label>رمز عبور</label>
                                                            <input type="text" class="form-control form-control-lg" name="AddServer[password]" minlength="1" maxlength="50" placeholder="رمز عبور" required>
                                                        </div>
                                                        <div class="form-group mb-20">
                                                            <label>پورت</label>
                                                            <input type="text" class="form-control form-control-lg" pattern="[1-6][0-4]{1, 4}" name="AddServer[port]" minlength="1" maxlength="5" placeholder="شماره پورت SSH" required>
                                                        </div>
                                                        <div class="button-group d-flex pt-20">
                                                            <button class="btn btn-primary btn-default btn-squared ">افزودن سرور</button>
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
                                            <span>عنوان</span>
                                        </th>
                                        <th>
                                            <span>آدرس آیپی</span>
                                        </th>
                                        <th>
                                            <span>رمز عبور</span>
                                        </th>
                                        <th>
                                            <span>شماره پورت SSH</span>
                                        </th>
                                        <th>
                                            <span>تاریخ اضافه شدن</span>
                                        </th>
                                        <th style="width: 40px">
                                            <span class="float-end"></span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($servers as $server) { ?>
                                        <tr>
                                            <td>
                                                <span><?php echo $server->id;?></span>
                                            </td>
                                            <td>
                                                <span><?php echo $server->title;?></span>
                                            </td>
                                            <td>
                                                <span><?php echo $server->ip;?></span>
                                            </td>
                                            <td>
                                                <span><?php echo $server->password;?></span>
                                            </td>
                                            <td>
                                                <span><?php echo $server->port;?></span>
                                            </td>
                                            <td>
                                                <span><?php echo get_date_by_timestamp($server->addDate);?></span>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <div class="dropdown dropdown-click">
                                                        <button class="btn-link border-0 bg-transparent p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <img class="svg" src="img/svg/more-vertical.svg" alt="more-vertical">
                                                        </button>
                                                        <div class="dropdown-default dropdown-menu dropdown-menu--dynamic">
<!--                                                            <a class="dropdown-item" href="#">ویرایش</a>-->
                                                            <a class="dropdown-item" href="?del=<?php echo $server->id;?>">حذف</a>
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
