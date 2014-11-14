<?php

require_once "app.php";
include "../config/config.php";

class Dispatcher {

    private $_request;
    private $request;
    
    private static $_instance;

    public static function dispatch() {
        self::getInstance(@$_REQUEST['__request'])
            ->parseRequest();
    }

    private static function getInstance($request) {
        if (! self::$_instance) {
            self::$_instance = new self();

            $request = preg_replace('/\/+$/', '', $request);
            self::$_instance->_request = $request;
        }

        return self::$_instance;
    }

    public function parseRequest() {
        $this->request = APP::parseUrl($this->_request);

        try {
            new APP();
            require_once '../config/boot.php';

            $className = APP::camelcase($this->request->controller);
            $ctrlName = "{$className}Controller";
            $ctrl = new $ctrlName($this->request);

            if (method_exists($ctrl, $this->request->action)) {
                $ctrl->beforeFilter();
                call_user_func_array(array($ctrl, $this->request->action), $this->request->params);
                if ($ctrl->autoRender && !$ctrl->rendered) {
                    $ctrl->render();
                }
            } else {
                $ctrl->http_error(404, 'Nepoznata akcija');
            }
        } catch (Exception $e) {
            $ctrl = new Controller($this->request);
            $ctrl->http_error(404, $e->getMessage());
        }
    }
}

?>