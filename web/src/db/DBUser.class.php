<?php

/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 3:17 PM
 */

require_once __DIR__ . "/DAO.class.php";

class DBUser extends DAO {
    private $logger;

    public function __construct() {
        parent::__construct();
        $this->logger = Logger::getLogger(get_class($this));
    }

    public function getUserInfo($userId) {
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'In ' . __METHOD__ );
        $start = self::getTimestamp();

        $stmt = $this->getPDO()->prepare("SELECT username, email, picture, rights, info, `key`
                                          FROM users
                                          WHERE userId = :userId LIMIT 1");
        $parameters = array("userId" => $userId);
        $stmt->execute($parameters);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->traceQuery ( $this->logger, $start, $stmt, $parameters );
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'Out ' . __METHOD__ );

        return $res;
    }

    public function getUserLoginTools($login) {
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'In ' . __METHOD__ );
        $start = self::getTimestamp();

        $stmt = $this->getPDO()->prepare("SELECT userId, `password`
                                          FROM users
                                          WHERE username = :login OR email = :login LIMIT 1");
        $parameters = array("login" => $login);
        $stmt->execute($parameters);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->traceQuery ( $this->logger, $start, $stmt, $parameters );
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'Out ' . __METHOD__ );

        return $res;
    }

    public function getUsersByUserNameOrEmail($username, $email) {
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'In ' . __METHOD__ );
        $start = self::getTimestamp();

        $stmt = $this->getPDO()->prepare("SELECT username, email
                                          FROM users
                                          WHERE LOWER(username) = :username OR email = :email LIMIT 1");
        $parameters = array("username" => $username, "email" => $email);
        $stmt->execute($parameters);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->traceQuery ( $this->logger, $start, $stmt, $parameters );
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'Out ' . __METHOD__ );

        return $res;
    }

    public function createUser($userName, $email, $password) {
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'In ' . __METHOD__ );
        $start = self::getTimestamp();

        $stmt = $this->getPDO()->prepare("INSERT INTO users (username, email, `password`)
                                          VALUES (:userName, :email, :password)");
        $parameters = array("userName" => $userName, "email" => $email, "password" => $password);
        $stmt->execute($parameters);

        $this->traceQuery ( $this->logger, $start, $stmt, $parameters );
        if ($this->logger->isTraceEnabled ()) $this->logger->trace ( 'Out ' . __METHOD__ );
    }
}