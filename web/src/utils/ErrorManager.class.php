<?php

/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 10:54 AM
 */
class ErrorManager {
    const GENERIC_ERROR = -1;
    const ERROR_I18N_NO_SENTENCE_FOUND  = 0;
    const ERROR_I18N_NO_LANG_FILE_FOUND = 1;

    const SEVERITY_WARNING = 0;
    const SEVERITY_ERROR = 1;

    /** @var SplQueue $errors */
    private static $errors;

    /** @var Logger $logger */
    private static $logger;

    public static function push($msg, $code = self::GENERIC_ERROR, $severity = self::SEVERITY_ERROR) {
        self::init();
        self::$errors->enqueue(new Error($msg, $code, $severity));
        if ($severity == self::SEVERITY_ERROR) {
            self::$logger->error($msg);
        } else {
            self::$logger->warn($msg);
        }
    }

    /** @return Error|null */
    public static function pop() {
        if (!self::$errors) return null;
        return self::$errors->count() > 0 ? self::$errors->dequeue() : null;
    }

    public static function init() {
        if (!self::$errors) {
            self::$errors = new SplQueue();
        }
        if (!self::$logger) {
            self::$logger = Logger::getLogger(self::class);
        }
    }
}

class Error {
    public $msg;
    public $code;
    public $severity;

    public function __construct($msg, $code, $severity) {
        $this->msg = $msg;
        $this->code = $code;
        $this->severity = $severity;
    }
}