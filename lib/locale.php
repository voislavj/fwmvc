<?php

require "I18n/I18n.php";

class Locale {

    const SESSION_KEY = 'locale';

    private $languages = array();

    private static $instance;
    private static function getInstance() {
        if (! self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function initLanguages() {
        $args     = func_get_args();
        $instance = self::getInstance();
        
        $l10n = I18n::getInstance()->l10n;
        foreach($args as $lang) {
            $catalog = $l10n->catalog($lang);
            $instance->languages[$lang] = $catalog['language'];
        }
    }

    public static function languages() {
        return self::getInstance()->languages;
    }

    public static function translate($key) {
        // no translation
        if (! self::get()) {
            return $key;
        }

        $instance = self::getInstance();
        return I18n::translate($key);
    }

    public static function get() {
        $locale = @$_SESSION[self::SESSION_KEY];
        $languages = self::getInstance()->languages;
        if (! $locale) {
            $locale = @$languages[0];
            self::set($locale);
        }
        if (! in_array($locale, array_keys($languages))) {
            return false;
        }

        return $locale;
    }

    public static function set($locale) {
        $_SESSION[self::SESSION_KEY] = $locale;
    }

}

?>