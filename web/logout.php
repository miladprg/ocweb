<?php
require_once "./assets/pages/header.php";
if (!isUserAuthenticated()) route("index.php");

logout();
route("login.php");
?>

    <div class="contents">

        <?php
            require_once "./assets/pages/footer.php";
        ?>