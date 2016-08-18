<?php
/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 3:14 PM
 */

require_once __DIR__ . "/../utils/Crypto.class.php";

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
    public function login($login, $password) {
        $user = $this->db->getUserLoginTools($login);
        if (password_verify($password, $user["password"])) {
            $this->userId = $user["id"];
            $this->loadUserInfo();
            return true;
        }
        return false;
    }

    public function register($userName, $email, $password) {
        if (!$this->checkEmail($email)) return false;
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->db->createUser($userName, $email, $password);
        return true;
    }

    public function logout() {

    }

    /**
     * @return boolean
     */
    public function isLogged() {
        return $this->logged;
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
            $this->name = $user["name"];
            $this->email = $user["email"];
            $this->picture = $user["picture"];
            $this->rights = $user["rights"];
            $this->cypher = $user["info"];
            $this->keyName = $user["key"];
        }
    }

    /////////////////////////////////////////////
    // UTILS
    /////////////////////////////////////////////

    public function decryptUserPrivateInfo() {
        $info = Crypto::decryptInfo($this->cypher, $this->keyName);

        $this->firstName = isset($info["firstName"]) ? $info["firstName"] : null;
        $this->lastName = isset($info["lastName"]) ? $info["lastName"] : null;
        $this->addressLine1 = isset($info["adressLine1"]) ? $info["adressLine1"] : null;
        $this->addressLine2 = isset($info["adressLine2"]) ? $info["adressLine2"] : null;
        $this->postalCode = isset($info["postalCode"]) ? $info["postalCode"] : null;
        $this->phoneNumber1 = isset($info["phoneNumber1"]) ? $info["phoneNumber1"] : null;
        $this->phoneNumber2 = isset($info["phoneNumber2"]) ? $info["phoneNumber2"] : null;
    }

    private function checkEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}