<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

if (isset($_FILES["upload-1"])) {
    $file = $_FILES["upload-1"];
    $uniqueFileName = strtotime('now') . '_' . $file['name'];
    move_uploaded_file($file["tmp_name"], "/var/www/html/OCWeb_DATABASE/backup/".$uniqueFileName);
    route("file-manager.php");
}
?>
    <script>
        function submitForm() {
            document.getElementById('uploadForm').submit();
        }
    </script>
    <div class="contents">
        <div class="dm-page-content">
            <div class="container-fluid">
                <div class="row mt-5">
                    <div class="col-8 mx-auto">
                        <div class="card card-default card-md mb-4">
                            <div class="card-header">
                                <h6>آپلود پایگاه داده</h6>
                            </div>
                            <div class="card-body">
                                <div class="dm-empty text-center">
                                    <div class="dm-empty__image">
                                        <img src="img/svg/3.svg" alt="Admin Empty">
                                    </div>
                                    <div class="mt-4">
                                        <div class="dm-upload">
                                            <div class="dm-upload__button">
                                                <a href="javascript:void(0)" class="btn btn-lg btn-outline-lighten btn-upload mx-auto" onclick="$('#upload-1').click()"> <img class="svg" src="img/svg/upload.svg" alt="upload">برای آپلود کلیک کنید</a>
                                                <form method="post" id="uploadForm" enctype="multipart/form-data">
                                                    <input type="file" name="upload-1" class="upload-one" id="upload-1" accept=".db,.db3,.sqlite,.sqlite3" onchange="submitForm();">
                                                </form>
                                            </div>
                                            <div class="dm-upload__file">
                                                <ul>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dm-empty__text">
                                        <p>
                                            فایل دیتابیس را انتخاب کرده و منتظر بمانید
                                            <br>
                                            تمامی داده های قبلی بطور کامل پاک خواهد شد
                                        </p>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ends: .card -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ends: .dm-page-content -->

        <?php
            require_once "./assets/pages/footer.php";
        ?>