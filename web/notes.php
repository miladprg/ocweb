<?php
require_once "./assets/pages/header.php";
if (!isAdminAuthenticated()) route("index.php");

NotesDatabase::connect(DATABASE);

if (isset($_GET["del"])) {
    NotesDatabase::deleteNote($_GET["del"]);
    route();
}

if (isset($_POST["note"])) {
    NotesDatabase::addNote($_POST["note"], "یادداشت", get_current_date());
    NotesDatabase::closeConnection();
    route();
}

if (isset($_POST["idea"])) {
    NotesDatabase::addNote($_POST["idea"], "ایده", get_current_date());
    NotesDatabase::closeConnection();
    route();
}

$content = NotesDatabase::getAllNotes();
$notes = array_filter($content, function ($value){
    return $value->type=="یادداشت";
});
$ideas = array_filter($content, function ($value){
    return $value->type=="ایده";
});
NotesDatabase::closeConnection();
?>

    <div class="contents">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="todo-breadcrumb">
                        <div class="breadcrumb-main">
                            <h4 class="text-capitalize breadcrumb-title">یادداشت و ایده</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card mb-25">
                        <div class="card-header">
                            <h6 class="m-3 m-md-0">یادداشت ها</h6>
                        </div>
                        <div class="card-body px-0 pt-15 pb-25">
                            <div class="todo-task table-responsive todo-task1">
                                <table class="table table-borderless">
                                    <tbody>
                                    <?php
                                    foreach ($notes as $note) {
                                    ?>
                                    <tr class="todo-list ptl--hover draggable" draggable="true">
                                        <td>
                                            <div class="ps-4">
                                                <span class="fs-14 text-dark">
                                                    <?php echo $note->text;?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="todo-list__right">
                                                <ul class="d-flex align-content-center justify-content-end">
                                                    <li>
                                                        <a href="#" class="plus">
                                                            <img src="img/svg/move.svg" alt="move" class="svg">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="?del=<?php echo $note->id;?>">
                                                            <img class="svg" src="img/svg/trash-2.svg" alt="">
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="add-task-action">
                                <button type="submit" class="btn btn-primary btn-transparent-primary btn-lg" data-bs-toggle="modal" data-bs-target="#add-note">
                                    <img class="svg" src="img/svg/plus.svg" alt="">
                                    افزودن یادداشت
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ends: col-12 -->

                <div class="col-12 col-md-6">
                    <div class="card mb-25">
                        <div class="card-header">
                            <h6 class="m-3 m-md-0">ایده ها</h6>
                        </div>
                        <div class="card-body px-0 pt-15 pb-25">
                            <div class="todo-task table-responsive todo-task1">
                                <table class="table table-borderless">
                                    <tbody>
                                    <?php
                                    foreach ($ideas as $idea) {
                                    ?>
                                    <tr class="todo-list ptl--hover draggable" draggable="true">
                                        <td>
                                            <div class="ps-4">
                                                <span class="fs-14 text-dark">
                                                    <?php echo $idea->text;?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="todo-list__right">
                                                <ul class="d-flex align-content-center justify-content-end">
                                                    <li>
                                                        <a href="#" class="plus">
                                                            <img src="img/svg/move.svg" alt="move" class="svg">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="?del=<?php echo $idea->id;?>">
                                                            <img class="svg" src="img/svg/trash-2.svg" alt="">
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="add-task-action">
                                <button type="submit" class="btn btn-primary btn-transparent-primary btn-lg" data-bs-toggle="modal" data-bs-target="#add-idea">
                                    <img class="svg" src="img/svg/plus.svg" alt="">افزودن ایده</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ends: col-12 -->
            </div>
        </div>

        <div class="add-todo-modal modal fade" id="add-note" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md add-todo-dialog modal-dialog-centered" id="add-todo" role="document">
                <div class="modal-content">
                    <div class="modal-header add-todo-header">
                        <h6 class="modal-title add-todo-title">افزودن یادداشت</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <img src="img/svg/x.svg" alt="x" class="svg"></button>
                    </div>
                    <div class="modal-body">
                        <div class="add-todo-form">
                            <form method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="note" minlength="2" max="200" placeholder="متن یادداشت را وارد کنید" required>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-lg btn-primary">افزودن یادداشت</button>
                                </div>
                            </form>
                        </div>
                        <!-- ends: .add-todo-form -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ends: .add-todo-modal -->

        <div class="add-todo-modal modal fade" id="add-idea" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md add-todo-dialog modal-dialog-centered" id="add-todo" role="document">
                <div class="modal-content">
                    <div class="modal-header add-todo-header">
                        <h6 class="modal-title add-todo-title">افزودن ایده</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <img src="img/svg/x.svg" alt="x" class="svg"></button>
                    </div>
                    <div class="modal-body">
                        <div class="add-todo-form">
                            <form method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="idea" minlength="2" max="200" placeholder="متن ایده را وارد کنید">
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-lg btn-primary">افزودن ایده</button>
                                </div>
                            </form>
                        </div>
                        <!-- ends: .add-todo-form -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ends: .add-todo-modal -->

        <?php
            require_once "./assets/pages/footer.php";
        ?>