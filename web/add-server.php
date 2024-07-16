<?php
require_once "./assets/pages/header.php";

if (!isAdminAuthenticated()) route("index.php");
?>

    <div class="contents">
        <div class="p-2 pt-5">
            <div class="col-lg-6 mx-auto mt-md-5">
                <div class="card card-Vertical card-default card-md mb-4">
                    <div class="card-header">
                        <h6>افزودن یا ویرایش سرور</h6>
                    </div>
                    <div class="card-body pb-md-30">
                        <div class="Vertical-form">
                            <form action="#">
                                <div class="form-group">
                                    <label for="formGroupExampleInput4" class=" color-dark fs-14 fw-500 align-center mb-10">عنوان</label>
                                    <div class="with-icon">
                                        <span class="la-ad las"></span>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput4" placeholder="عنوان سرور">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput5" class=" color-dark fs-14 fw-500 align-center mb-10">آیپی</label>
                                    <div class="with-icon">
                                        <span class="las la-sort-numeric-down"></span>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput5" placeholder="آدرس آیپی">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput6" class=" color-dark fs-14 fw-500 align-center mb-10">رمز عبور</label>
                                    <div class="with-icon">
                                        <span class="las la-key"></span>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput6" placeholder="رمز عبور">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput10" class=" color-dark fs-14 fw-500 align-center mb-10">پورت</label>
                                    <div class="with-icon">
                                        <span class="la-sort-numeric-down las"></span>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light" id="formGroupExampleInput10" placeholder="شماره پورت SSH">
                                    </div>
                                </div>
                                <div class="layout-button mt-25">
                                    <a type="button" href="servers-list.php" class="btn btn-default btn-squared btn-light px-20 ">بازگشت</a>
                                    <button type="submit" class="btn btn-primary btn-default btn-squared px-30">ذخیره</button>
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