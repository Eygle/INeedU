<?php
/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 11:33 AM
 */

@session_start();

require_once __DIR__ . "/conf.php";
require_once __DIR__ . "/utils/ErrorManager.class.php";
require_once __DIR__ . "/om/I18n.class.php";
require_once __DIR__ . "/om/Settings.class.php";
require_once __DIR__ . "/om/User.class.php";
require_once  __DIR__ . "/vendors/log4php/Logger.php";

Logger::configure(__DIR__ . "/logger-config.php");

$user = new User();