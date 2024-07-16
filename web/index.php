<?php
require_once "./assets/pages/header.php";
if (!isUserAuthenticated()) route("login.php");

UsersDatabase::connect(DATABASE);

$usersCount = (object) UsersDatabase::getUsersCount();
$lastUsers = UsersDatabase::getLastUsers(10);
UsersDatabase::closeConnection();
?>

    <div class="contents">

        <div class="demo7 mb-25 t-thead-bg">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-main">
                            <h4 class="text-capitalize breadcrumb-title">داشبورد</h4>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6  col-ssm-12 mb-25">
                        <!-- Card 1  -->
                        <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                            <div class="overview-content w-100">
                                <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                                    <div class="ap-po-details__titlebar">
                                        <h1><?php echo $usersCount->allUsers;?></h1>
                                        <p>تعداد کل کاربران</p>
                                    </div>
                                    <div class="ap-po-details__icon-area">
                                        <div class="svg-icon order-bg-opacity-primary color-primary">
                                            <i class="uil uil-users-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 1 End  -->
                    </div>
                    <div class="col-xxl-3 col-sm-6  col-ssm-12 mb-25">
                        <!-- Card 2 -->
                        <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                            <div class="overview-content w-100">
                                <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                                    <div class="ap-po-details__titlebar">
                                        <h1><?php echo $usersCount->enableUsers;?></h1>
                                        <p>تعداد کاربران فعال</p>
                                    </div>
                                    <div class="ap-po-details__icon-area">
                                        <div class="svg-icon order-bg-opacity-success color-success">
                                            <i class="uil uil-hard-hat"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 2 End  -->
                    </div>
                    <div class="col-xxl-3 col-sm-6  col-ssm-12 mb-25">
                        <!-- Card 3 -->
                        <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                            <div class="overview-content w-100">
                                <div class=" ap-po-details-content d-flex flex-wrap justify-content-between">
                                    <div class="ap-po-details__titlebar">
                                        <h1><?php echo $usersCount->disableUsers;?></h1>
                                        <p>تعداد کاربران غیرفعال</p>
                                    </div>
                                    <div class="ap-po-details__icon-area">
                                        <div class="svg-icon order-bg-opacity-danger color-danger">
                                            <i class="uil uil-user-times"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 3 End  -->
                    </div>
                    <div class="col-xxl-12 mb-25">
                        <div class="card border-0 px-25">
                            <div class="card-header px-0 border-0">
                                <h6 class="m-3 m-md-0">کاربران اخیر</h6>
                                <div class="card-extra">
                                    <ul class="card-tab-links nav-tabs nav" role="tablist">
                                        <li>
                                            <a class="active" href="users-list.php">لیست کاربران</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="t_selling-today222" role="tabpanel" aria-labelledby="t_selling-today222-tab">
                                        <div class="selling-table-wrap selling-table-wrap--source">
                                            <div class="table-responsive">
                                                <table class="table table--default table-borderless">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">نام کاربری</th>
                                                        <th class="text-center">رمز عبور</th>
                                                        <th class="text-center">گروه</th>
                                                        <th class="text-center">تاریخ</th>
                                                        <th class="text-center">وضعیت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($lastUsers as $user) { ?>
                                                        <tr>
                                                            <td><div class="align-items-center ps-3 text-gray text-center"><?php echo $user->username; ?></div></td>
                                                            <td class="text-gray text-center"><?php echo $user->password; ?></td>
                                                            <td class="text-gray text-center"><?php echo $user->group; ?></td>
                                                            <td class="text-gray text-center"><?php echo get_datetime_by_timestamp($user->addDate); ?></td>
                                                            <td class="text-center text-<?php echo ($user->status=="فعال") ? "success" : "danger"; ?>"><?php echo $user->status; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="t_selling-week222" role="tabpanel" aria-labelledby="t_selling-week222-tab">
                                        <div class="selling-table-wrap selling-table-wrap--source">
                                            <div class="table-responsive">
                                                <table class="table table--default table-borderless">
                                                    <thead>
                                                    <tr>
                                                        <th>نام فروشنده</th>
                                                        <th>شرکت</th>
                                                        <th>محصول</th>
                                                        <th>درآمد</th>
                                                        <th>وضعیت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-primary align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-1.png" alt="img">
                                                                </div>
                                                                <span>حسین نوری</span>
                                                            </div>
                                                        </td>
                                                        <td>سامسونگ</td>
                                                        <td>Smart Phone</td>
                                                        <td>
                                                            $38,536
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-primary align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-2.png" alt="img">
                                                                </div>
                                                                <span> مایکل جانسون </span>
                                                            </div>
                                                        </td>
                                                        <td>ایسوس</td>
                                                        <td>Laptop</td>
                                                        <td>
                                                            $20,573
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-secondary align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-3.png" alt="img">
                                                                </div>
                                                                <span>دانیل وایت</span>
                                                            </div>
                                                        </td>
                                                        <td>گوگل</td>
                                                        <td>Watch</td>
                                                        <td>
                                                            $17,457
                                                        </td>
                                                        <td>در انتظار</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-success align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-4.png" alt="img">
                                                                </div>
                                                                <span> کریس بارین </span>
                                                            </div>
                                                        </td>
                                                        <td>اپل</td>
                                                        <td>Computer</td>
                                                        <td>
                                                            $15,354
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-info align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-5.png" alt="img">
                                                                </div>
                                                                <span>دانیل پینک</span>
                                                            </div>
                                                        </td>
                                                        <td>پاناسونیک</td>
                                                        <td>Sunglass</td>
                                                        <td>
                                                            $12,354
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="t_selling-month333" role="tabpanel" aria-labelledby="t_selling-month333-tab">
                                        <div class="selling-table-wrap selling-table-wrap--source">
                                            <div class="table-responsive">
                                                <table class="table table--default table-borderless">
                                                    <thead>
                                                    <tr>
                                                        <th>نام فروشنده</th>
                                                        <th>شرکت</th>
                                                        <th>محصول</th>
                                                        <th>درآمد</th>
                                                        <th>وضعیت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-primary align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-1.png" alt="img">
                                                                </div>
                                                                <span>حسین نوری</span>
                                                            </div>
                                                        </td>
                                                        <td>سامسونگ</td>
                                                        <td>Smart Phone</td>
                                                        <td>
                                                            $38,536
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-primary align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-2.png" alt="img">
                                                                </div>
                                                                <span> مایکل جانسون </span>
                                                            </div>
                                                        </td>
                                                        <td>ایسوس</td>
                                                        <td>Laptop</td>
                                                        <td>
                                                            $20,573
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-secondary align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-3.png" alt="img">
                                                                </div>
                                                                <span>دانیل وایت</span>
                                                            </div>
                                                        </td>
                                                        <td>گوگل</td>
                                                        <td>Watch</td>
                                                        <td>
                                                            $17,457
                                                        </td>
                                                        <td>در انتظار</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-success align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-4.png" alt="img">
                                                                </div>
                                                                <span> کریس بارین </span>
                                                            </div>
                                                        </td>
                                                        <td>اپل</td>
                                                        <td>Computer</td>
                                                        <td>
                                                            $15,354
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="selling-product-img d-flex align-items-center">
                                                                <div class="selling-product-img-wrapper order-bg-opacity-info align-items-end">
                                                                    <img class=" img-fluid" src="img/author/robert-5.png" alt="img">
                                                                </div>
                                                                <span>دانیل پینک</span>
                                                            </div>
                                                        </td>
                                                        <td>پاناسونیک</td>
                                                        <td>Sunglass</td>
                                                        <td>
                                                            $12,354
                                                        </td>
                                                        <td>انجام شد</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- ends: .row -->
            </div>
        </div>

        <?php
            require_once "./assets/pages/footer.php";
        ?>