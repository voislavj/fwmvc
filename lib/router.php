<?php

class Router {

    private static $definition = array();

    public function __construct() {

    }

    public static function load($defs) {
        self::$definition = $defs;
    }

    public static function find($url) {
        $url = '/' . preg_replace('/^\/+/', '', $url);

        foreach (self::$definition as $rgxp => $urlRgxp) {
            if (preg_match(self::prepare($rgxp), $url, $matches)) {
                return preg_replace(self::prepare($rgxp), $urlRgxp, $url);
            }
        }
        return false;
    }

    private static function prepare($rgxp) {
        return '/' . str_replace('/', "\\/", $rgxp) . '/i';
    }
}

?>