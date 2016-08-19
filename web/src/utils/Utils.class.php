<?php

/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/19/16
 * Time: 3:55 PM
 */

require_once __DIR__ . "/../om/I18n.class.php";

class Utils {
    public static function checkEmail($email, &$err = null) {
        $res = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$res && $err !== null) {
            $err[] = I18n::get("error_incorrect_email");
        }
        return $res;
    }

    public static function checkPassword($pwd, &$err) {
        $errors_init = $err;

        if (strlen($pwd) < 8) {
            $errors[] = I18n::get("error_pwd_too_short");
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors[] = I18n::get("error_pwd_no_number");
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors[] = I18n::get("error_pwd_no_letter");
        }

        return $err == $errors_init;
    }

    public static function checkUserName($userName, $err) {
        $errors_init = $err;

        if (mb_strlen($userName, 'UTF-8') < 3) {
            $errors[] = I18n::get("error_username_too_short");
        }
        if (mb_strlen($userName, 'UTF-8') > 32) {
            $errors[] = I18n::get("error_username_too_long");
        }
        if (!preg_match("^[a-zA-Z0-9\-_]+$", $userName)) {
            $errors[] = I18n::get("error_username_invalid_char");
        }

        return $err == $errors_init;
    }

    public static function getPost($str) {
        return isset($_POST[$str]) && !empty($_POST[$str]) ? $_POST[$str] : false;
    }
}