<?php
/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/19/16
 * Time: 3:19 PM
 */

require_once __DIR__ . "/autoload.php";

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (Utils::getPost("userNameEmail")) {
        $user->login(Utils::getPost("userNameEmail"), Utils::getPost("login_password"), $errors);
    } else {
        if ($user->register(Utils::getPost("username"), Utils::getPost("email"), Utils::getPost("reg_password"),
            Utils::getPost("pwd_check"), $errors)) {
            $user->login( Utils::getPost("username"), Utils::getPost("reg_password"), $errors);
        }
    }
} else if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
    $user->logout();
}

foreach ($errors as $e) {
    ErrorManager::push($e);
}

if ($user->isLogged()) {
    header("Location: /");
}

$pageTitle = I18n::get("title") . " - ".I18n::get("login");
$page = "login";

include_once __DIR__ . "/template/top.php";
?>

    <div id="login" class="container">
        <div class="row">
            <div class="col-md-6">
                <h2><?php echo I18n::get("connexion");?></h2>
                <form method="post">
                    <input type="text" id="userNameEmail" name="userNameEmail" class="form-control"
                           placeholder="<?php echo I18n::get("username_or_email_address");?>"
                           value="<?php echo Utils::getPost("userNameEmail");?>" />
                    <input type="password" id="login_password" name="login_password" class="form-control"
                           placeholder="<?php echo I18n::get("password");?>"
                           value="<?php echo Utils::getPost("login_password");?>" />
                    <input type="submit" class="btn btn-primary" value="<?php echo I18n::get("login");?>" />
                </form>
            </div>
            <div class="col-md-6">
                <h2><?php echo I18n::get("register");?></h2>
                <form method="post">
                    <input type="email" id="email" name="email" class="form-control" maxlength="256"
                           placeholder="<?php echo I18n::get("email_address");?>"
                           value="<?php echo Utils::getPost("email_address");?>" />
                    <input type="text" id="username" name="username" class="form-control" maxlength="32"
                           placeholder="<?php echo I18n::get("username");?>"
                           value="<?php echo Utils::getPost("userName");?>" />
                    <input type="password" id="reg_password" name="reg_password" class="form-control"
                           placeholder="<?php echo I18n::get("password");?>"
                           value="<?php echo Utils::getPost("reg_password");?>" />
                    <input type="password" id="pwd_check" name="pwd_check" class="form-control"
                           placeholder="<?php echo I18n::get("repeat_password");?>"
                           value="<?php echo Utils::getPost("repeat_password");?>" />
                    <input type="submit" class="btn btn-primary" value="<?php echo I18n::get("register");?>" />
                </form>
            </div>
        </div>
    </div>

<?php
include_once __DIR__ . "/template/bottom.php";