<?php

/**
 * Created by PhpStorm.
 * User: eygle
 * Date: 8/18/16
 * Time: 10:32 AM
 */

require_once __DIR__ . "/Settings.class.php";
require_once __DIR__ . "/ErrorManager.class.php";

class I18n {
    const ERROR_NO_SENTENCE_FOUND   = 0;
    const ERROR_NO_I18N_FILE_FOUND  = 1;

    /** @var Settings $settings */
    private static $settings;

    private static $sentences = [];

    private static $lang = null;

    /**
     * @var string $key
     * @var ... $args
     * @return null|string
     */
    public static function get() {
        if (!self::$settings) {
            self::$settings = Settings::getInstance();
        }

        if (self::$lang !== self::$settings->getLang()) {
            self::loadLang(self::$settings->getLang());
        }

        $args = func_get_args();
        $key  = array_shift($args);

        if (!isset(self::$sentences[$key])) {
            ErrorManager::push("No sentence found for key '$key'", ErrorManager::ERROR_I18N_NO_SENTENCE_FOUND, ErrorManager::SEVERITY_WARNING);
            return $key;
        }

        return vsprintf(self::$sentences[$key], $args);
    }

    private static function loadLang($lang) {
        if (!file_exists(__DIR__ . "/../I18n/$lang.json")) {
            ErrorManager::push("No I18n file for lang '$lang'", ErrorManager::ERROR_I18N_NO_LANG_FILE_FOUND);
        }

        self::$sentences = json_decode(file_get_contents(__DIR__ . "/../I18n/$lang.json"), true);
        self::$lang = $lang;
    }
}