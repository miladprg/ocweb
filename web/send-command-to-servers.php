<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

if (isset($_GET["clearHistory"])) {
    CommandsDatabase::connect(DATABASE);
    CommandsDatabase::clearHistory();
    CommandsDatabase::closeConnection();
    route();
}

if (isset($_GET["del"])) {
    CommandsDatabase::connect(DATABASE);
    CommandsDatabase::deleteCommand($_GET["del"]);
    CommandsDatabase::closeConnection();
    route();
}

if (isset($_POST["command"])) {
    CommandsDatabase::connect(DATABASE);
    CommandsDatabase::addCommand($_POST["command"], get_current_date());
    CommandsDatabase::closeConnection();
    ServersDatabase::connect(DATABASE);
    foreach (ServersDatabase::getAllServers() as $server) {
        run_command_to_server($server->ip, "root", $server->password, $server->port, $_POST["command"]);
    }
    ServersDatabase::closeConnection();
}

CommandsDatabase::connect(DATABASE);
$commands = CommandsDatabase::getAllCommands();
CommandsDatabase::closeConnection();
?>

    <div class="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="todo-breadcrumb">
                        <div class="breadcrumb-main">
                            <h4 class="text-capitalize breadcrumb-title">ارسال دستور به سرورها</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content mt-2" id="ueberTabB">
            <div class="tab-pane fade show active" id="panel_b_first" role="tabpanel" aria-labelledby="first-tab">
                <div class="chat">
                    <div class="chat-body bg-white radius-xl">
                        <div class="chat-header">
                            <div class="media chat-name align-items-center">
                                <div class="media-body align-self-center ">
                                    <h5 class=" mb-0 fw-500 mb-2">لیست دستورات ارسال شده</h5>
                                </div>
                            </div>
                            <!-- Chat Options -->
                            <ul class="nav flex-nowrap">
                                <li class="nav-item list-inline-item me-0">
                                    <div class="dropdown">
                                        <a href="#" role="button" title="Details" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img class="svg" src="img/svg/more-vertical.svg" alt="more-vertical">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item align-items-center d-flex" href="?clearHistory=1">
                                                <!-- Default :: Inline SVG -->
                                                <img class="svg" src="img/svg/trash-2.svg" alt="">
                                                <span>حذف تاریخچه</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        <div class="chat-box chat-box--big p-xl-30 ps-lg-20 pe-lg-0">
                            <!-- Start -->
                            <div class="flex-1 justify-content-end d-flex outgoing-chat">
                                <div class="chat-text-box">
                                    <div class="media ">
                                        <div class="media-body">
                                            <?php
                                            foreach ($commands as $command) {
                                            ?>
                                            <div class="chat-text-box__content">
                                                <div class="chat-text-box__title d-flex align-items-center justify-content-end mb-2">
                                             <span class="chat-text-box__time fs-12 color-light fw-400"><?php echo get_datetime_by_timestamp($command->addDate);?></span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end" dir="ltr">
                                                    <div class="chat-text-box__subtitle p-20 bg-deep">
                                                        <p class="color-gray"><?php echo $command->command;?></p>
                                                    </div>
                                                    <div class="chat-text-box__other d-flex">
                                                        <div class="px-15">
                                                            <div class="dropdown dropdown-click">
                                                                <button class="btn-link border-0 bg-transparent p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <img src="img/svg/more-horizontal.svg" alt="more-horizontal" class="svg">
                                                                </button>
                                                                <div class="dropdown-default dropdown-bottomRight dropdown-menu-right dropdown-menu" style="">
                                                                    <a class="dropdown-item" href="?del=<?php echo $command->id;?>">حذف</a>
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
                            <!-- End  -->
                        </div>
                        <div class="chat-footer px-xl-30 px-lg-20 pb-lg-30 pt-1">
                            <div class="chat-type-text">
                                <form method="post">
                                    <div class="pt-0 outline-0 pb-0 pe-0 ps-0 rounded-0 position-relative d-flex align-items-center" tabindex="-1">
                                        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">
                                            <div class="chat-type-text__btn me-3">
                                                <button type="submit" class="border-0 btn-primary wh-50 rounded-circle">
                                                    <img class="svg" src="img/svg/send.svg" alt="send"></button>
                                            </div>
                                            <div class=" flex-1 d-flex align-items-center chat-type-text__write">
                                                <input class="form-control border-0 bg-transparent box-shadow-none" dir="ltr" name="command" minlength="1" maxlength="300" placeholder="Enter Command..." required>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- ends: .chat-->
            </div>

        <?php
            require_once "./assets/pages/footer.php";
        ?>