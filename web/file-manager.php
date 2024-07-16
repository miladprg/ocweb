<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

$backup_directory = dirname(DATABASE)."/backup/";

if (isset($_GET["del"])) {
    unlink($backup_directory.$_GET["del"]);
    route();
}

if (isset($_GET["recover"])) {
    copy($backup_directory.$_GET["recover"], DATABASE);
    route("rebuild-ocpasswd.php?rebuild=1");
}

// Get the list of files in the directory
$files = scandir($backup_directory);

// Remove '.' and '..' from the list
$files = array_diff($files, array('..', '.', 'index.php'));

// Reverse array for sort files desc
$files = array_reverse($files);
?>

    <div class="contents">
        <div class="dm-page-content">
            <div class="container-fluid">
                <div class="fileM-wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumb-main">
                                <h4 class="text-capitalize breadcrumb-title">فایل منیجر</h4>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="fileM-contents">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="fileM-grid-wrapper mb-30 ">
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                    <div class="fileM-wrapper">
                                                        <h6 class="fileM-wrapper__title">فایل های اخیر</h6>
                                                        <div class="row">
                                                            <?php
                                                            foreach ($files as $file) {
                                                                $date = explode(".", $file);
                                                                $date = explode("_", $date[0]);
                                                                $date = get_datetime_by_timestamp(strtotime(date("Y-m-d H:i:s", filemtime($backup_directory.$file))));
                                                            ?>
                                                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                                                <div class="fileM-single mb-25">
                                                                    <div class="fileM-card ">
                                                                        <div class="card border-0">
                                                                            <div class="card-body pb-40 pt-45">
                                                                                <div class="fileM-img">
                                                                                    <img class="wh-50" src="img/zip.png" alt="">
                                                                                </div>
                                                                                <p class="fileM-excerpt" dir="ltr"><?php echo $file;?></p>
                                                                                <p class="fs-12 mt-2"><?php echo $date;?></p>
                                                                                <div class="fileM-action">
                                                                                    <div class="fileM-action__right ">
                                                                                        <div class="dropdown dropleft position-absolute">
                                                                                            <button class="btn-link border-0 bg-transparent p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                <img class="svg" src="img/svg/more-vertical.svg" alt="more-vertical">
                                                                                            </button>
                                                                                            <div class="dropdown-menu">
                                                                                                <a class="dropdown-item" href="?download=<?php echo $file;?>"><img src="img/svg/download.svg" alt="download" class="svg">دانلود</a>
                                                                                                <a class="dropdown-item" href="?recover=<?php echo $file;?>"><img src="img/svg/repeat.svg" alt="download" class="svg">بازگردانی</a>
                                                                                                <a class="dropdown-item" href="?del=<?php echo $file;?>"><img class="svg" src="img/svg/trash-2.svg" alt="">حذف</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- ends: .col-lg-10 -->
                                    </div>
                                </div>
                            </div>
                        </div><!-- ends: .col-lg-12 -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ends: .dm-page-content -->

        <?php
            require_once "./assets/pages/footer.php";
        ?>