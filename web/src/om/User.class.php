<?php
/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 3:14 PM
 */

class User {
    /** @var DBUser $db */
    private $db;

    /** @var boolean $logged */
    private $logged = false;

    private $userId;
    private $name;
    private $email;
    private $rights;

    public function __construct() {
        $this->db = new DBUser();
        if (isset($_SESSION["userId"])) {
            $this->userId = $_SESSION["userId"];
            $this->loadUser();
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
            $this->loadUser();
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

    private function loadUser() {
        $user = $this->db->getUserInfo($this->userId);
        if ($user) {
            $this->name = $user["name"];
            $this->email = $user["email"];
            $this->rights = $user["rights"];
        }
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

    /////////////////////////////////////////////
    // UTILS
    /////////////////////////////////////////////

    private function checkEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}