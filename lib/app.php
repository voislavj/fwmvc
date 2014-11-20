<?php

define('DS', DIRECTORY_SEPARATOR);

require_once 'controller.php';
require_once 'model.php';
require_once 'router.php';
include_once '../config/routes.php';

class APP {

    const TIMEZONE = 'Europe/Belgrade';

    public static $ROOT = '';
    public static $settings;

    private $config = array();

    private static $instance;
    private static function getInstance() {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct() {
        self::$ROOT = dirname(dirname(__FILE__));

        if (! session_id()) session_start();
        ob_start();

        $this->default_timezone();
        $this->autoload();
    }

    private function default_timezone() {
        date_default_timezone_set(self::TIMEZONE);
    }

    private function autoload($className = null) {
        if ($className === null) {
            spl_autoload_register(array($this, 'autoload'));
        } else {
            $fileName = self::underscore($className);
            $fileName .= ".php";

            // controllers
            if (preg_match('/Controller$/', $className)) {
                $this->_require('/app/controllers/' . $fileName, $className);

            // models
            } else if(preg_match('/Model$/', $className)) {
                $this->_require('/app/models/' . $fileName, $className);

            // try lib
            } else if(file_exists(self::$ROOT . DS . 'lib' . DS . $fileName)) {
                $this->_require('/lib/' . $fileName, $className);
            }
        }
    }

    private function _require($path, $className) {
        $path = self::$ROOT . $path;
        if (file_exists($path)) {
            require_once $path;
        } else {
            throw new Exception("{$className} nije pronađeno.");
        }
    }

    public static function parseUrl($url) {
        $url = preg_replace('/^\/+|\/+$/', '', $url);
        $request = (object)array(
            'controller' => 'index',
            'action'     => 'index',
            'params'     => array(),
            'named'      => array()
        );

        if ($r = Router::find($url)) {
            $url = $r;
        }

        $tmp = explode("/", preg_replace('/^\/+|\/+$/', '', $url));
        foreach($tmp as $k=>$v) {
            if(preg_match('/^[a-z0-9]+:[^\/]*$/i', $v)) {
                list($name, $value) = explode(":", $v, 2);
                if(! empty($name) && !empty($value)) {
                    $request->named[$name] = $value;
                }
                unset($tmp[$k]);
            }
        }

        if (! empty($tmp[0])) {
            $request->controller = $tmp[0];
        }

        if (! empty($tmp[1])) {
            $request->action = $tmp[1];
        }

        if (count($tmp)>2) {
            $request->params = array_slice($tmp, 2);
        }

        return $request;
    }

    public static function request2url($req) {
        $url = "/{$req->controller}";
        if ($req->action != 'index') {
            $url .= "/{$req->action}";
        }

        if (! empty($req->params)) {
            if ($req->action == 'index') {
                $url .= "/{$req->action}";
            }
            $url .= "/" . implode("/", $req->params);
        }

        return $url;
    }

    public static function settings($name, $locale=null) {
        if (! self::$settings) {
            if (! $locale) {
                if (! $locale = Locale::get()) {
                    $langs = array_keys(Locale::languages());
                    $locale = @$langs[0];
                    Locale::set($locale);
                }
            }
            $path = implode(DS, array(self::$ROOT, "config", "settings" . ($locale ? ".{$locale}" : "") .".data"));
            self::$settings = unserialize(file_get_contents($path));
        }

        if ($name == '*') {
            $value = self::$settings;
        } else {
            foreach(explode(".", $name) as $n) {
                $value = isset($value) ? @$value[$n] : @self::$settings[$n];
            }
        }

        return $value;
    }

    public static function underscore($camelCase) {
        $underscore = strtolower(substr($camelCase, 0, 1));
        for ($i=1; $i<strlen($camelCase); $i++) {
            $c = substr($camelCase, $i, 1);
            if (strtoupper($c) == $c && !empty($underscore)) {
                $underscore .= "_".strtolower($c);
            } else {
                $underscore .= strtolower($c);
            }
        }
        return $underscore;
    }

    public static function urlize($string) {
        return mb_eregi_replace('[^a-z0-9čćšđž]', '-', strtolower($string));
    }

    public static function camelcase($underscore) {
        return ucfirst(preg_replace('/_(.)/e', 'strtoupper("$1")', $underscore));
    }

    public static function config($name, $value=null) {
        $inst = self::getInstance();
        if ($value === null) {
            return $inst->config[$name];
        } else {
            $inst->config[$name] = $value;
        }
    }

    public static function parseFileSize($string) {
        $string = preg_replace('/B/i', '', $string);
        $string = preg_replace('/K/i', ' * 1024', $string);
        $string = preg_replace('/M/i', ' * 1024 * 1024', $string);
        $string = preg_replace('/G/i', ' * 1024 * 1024 * 1024', $string);
        eval('$size = ' . $string . ';');
        return $size;
    }

    public static function humanizeFileSize($size) {

        if ($size >= 1024*1024*1024) {
            $size = ($size/(1024*1024*1024)) . "G";
        } elseif($size >= 1024*1024) {
            $size = ($size/(1024*1024)) . "M";
        } elseif($size >= 1024) {
            $size = ($size/1024) . "K";
        }

        return "{$size}B";
    }
}

?>