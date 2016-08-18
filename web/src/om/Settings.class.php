<?php

/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 10:31 AM
 */

require_once __DIR__ . "/../conf.php";

class Settings {
    /** @var  Settings $me */
    private static $me = null;

    /// User settings
    private $lang = DEFAULT_LANGUGAGE;

    /// Global settings
    private $availableLangs = ["fr", "en"];

    /**
     * Get Singleton instannce
     * @return Settings
     */
    public static function getInstance() {
        if (self::$me) {
            return self::$me;
        }
        self::$me = new Settings();
        return self::$me;
    }

    /**
     * @return string
     */
    public function getLang() {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang) {
        $this->lang = $lang;
    }
}