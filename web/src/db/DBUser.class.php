<?php

/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 3:17 PM
 */

require_once __DIR__ . "/DAO.class.php";

class DBUser extends DAO {
    public function getUserInfo($userId) {
        $stmt = $this->getPDO()->prepare("SELECT userName, email, rights
                                          FROM users
                                          WHERE userId = :userId LIMIT 1");
        $stmt->execute(array("userId" => $userId));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserLoginTools($login) {
        $stmt = $this->getPDO()->prepare("SELECT userId, password
                                          FROM users
                                          WHERE userName = :login OR email = :login LIMIT 1");
        $stmt->execute(array("login" => $login));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($userName, $email, $password) {
        $stmt = $this->getPDO()->prepare("INSERT INTO users (userName, email, password)
                                          VALUES (:userName, :email, :password)");
        $stmt->execute(array("userName" => $userName, "email" => $email, "password" => $password));
    }
}