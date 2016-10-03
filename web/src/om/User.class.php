<?php
/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 3:14 PM
 */

require_once __DIR__ . "/../utils/Crypto.class.php";
require_once __DIR__ . "/../utils/Utils.class.php";
require_once __DIR__ . "/I18n.class.php";
require_once __DIR__ . "/../db/DBUser.class.php";

class User {
    /** @var DBUser $db */
    private $db;

    /** @var boolean $logged */
    private $logged = false;

    private $userId;
    private $name;
    private $email;
    private $picture;
    private $rights;

    // Encrypted information
    private $cypher;
    private $keyName;

    private $firstName;
    private $lastName;
    private $addressLine1;
    private $addressLine2;
    private $postalCode;
    private $phoneNumber1;
    private $phoneNumber2;

    public function __construct() {
        $this->db = new DBUser();
        if (isset($_SESSION["userId"])) {
            $this->userId = $_SESSION["userId"];
            $this->loadUserInfo();
        }
    }

    /**
     * @param $login
     * @param $password
     * @return bool
     */
    public function login($login, $password, &$err = null) {
        $u = $this->db->getUserLoginTools($login);
        if (!$u) {
            $err[] = I18n::get("error_username_or_email_not_found");
        } else if (!password_verify($password, $u["password"])) {
            $err[] = I18n::get("error_incorrect_pwd");
        } else {
            $this->userId = $u["userId"];
            $this->loadUserInfo();
            $_SESSION["userId"] = $this->userId;
            session_write_close();
            return true;
        }
        return false;
    }

    public function register($userName, $email, $password, $pwdChk, &$err = null) {
        $init_err = $err;

        Utils::checkEmail($email, $err);
        Utils::checkPassword($password, $err);

        if (strcmp($password, $pwdChk) !== 0) {
            $err[] = I18n::get("error_pwd_mismatch");
        }

        $email = mb_strtolower($email, 'UTF-8');
        $lowUsername = mb_strtolower($userName, 'UTF-8');
        $others = $this->db->getUsersByUserNameOrEmail($lowUsername, $email);
        if ($others) {
            foreach ($others as $o) {
                if (strcmp(mb_strtolower($o["username"], 'UTF-8'), $lowUsername) == 0) {
                    $err[] = I18n::get("error_username_already_taken");
                }
                if (strcmp($o["email"], $email) == 0) {
                    $err[] = I18n::get("error_email_already_taken");
                }
            }
        }

        if ($init_err != $err) {
            return false;
        }


        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->db->createUser($userName, $email, $password);
        return true;
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        $this->logged = false;
    }

    /**
     * @return boolean
     */
    public function isLogged() {
        $res = $this->logged;
        if ($res) {
            session_write_close();
        }
        return $res;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    private function loadUserInfo() {
        $user = $this->db->getUserInfo($this->userId);
        if ($user) {
            $this->name = $user["username"];
            $this->email = $user["email"];
            $this->picture = $user["picture"];
            $this->rights = $user["rights"];
            $this->cypher = $user["info"];
            $this->keyName = $user["key"];
            $this->logged = true;
        }
    }

    /////////////////////////////////////////////
    // UTILS
    /////////////////////////////////////////////

    public function decryptUserPrivateInfo() {
        if ($this->cypher && $this->keyName) {
            $info = Crypto::decryptInfo($this->cypher, $this->keyName);

            $this->firstName = isset($info["firstName"]) ? $info["firstName"] : null;
            $this->lastName = isset($info["lastName"]) ? $info["lastName"] : null;
            $this->addressLine1 = isset($info["adressLine1"]) ? $info["adressLine1"] : null;
            $this->addressLine2 = isset($info["adressLine2"]) ? $info["adressLine2"] : null;
            $this->postalCode = isset($info["postalCode"]) ? $info["postalCode"] : null;
            $this->phoneNumber1 = isset($info["phoneNumber1"]) ? $info["phoneNumber1"] : null;
            $this->phoneNumber2 = isset($info["phoneNumber2"]) ? $info["phoneNumber2"] : null;
        }
    }
}