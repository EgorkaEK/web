<?php
if (isset($_POST["name"], $_POST["login"], $_POST["password"])) {
    require "vendor/autoload.php";
    $db = new \Photos\DB();
    $login_exist = $db -> check_login($_POST["login"]);
    if ($login_exist) {
        header("location: user.php?sign_error=login");
    }
    elseif(empty($_POST["name"]) || empty($_POST["login"]) || empty($_POST["password"])) {
        header("location: user.php?sign_error_empty=empty");
    }
    else {
        $db->new_user($_POST["name"], $_POST["login"], $_POST["password"]);
        header("location: user.php?sign_success=ok");
    }
}
