<?php

require_once 'view.php';

class Controller {

    public static $errors = array(
        500 => 'Server error',
        404 => 'Not found'
    );

    public $autoRender = true;
    public $layout     = 'layout';

    protected $request;
    private $View;
    public $rendered = false;

    public $safeActions = array();

    public function __construct($request) {
        $this->request = $request;

        $this->View = new View($this->request, $this);
    }

    public function beforeFilter() {

    }

    public function http_error($code, $message = 'Server Error') {
        header("HTTP/1.1 {$code} {$message}");

        if (App::config('environment') != 'development') {
            $message = self::$errors[$code];
        }
        $this->set('message', $message);
        $this->pageTitle = "Greška {$code}";
        echo $this->View->render("errors/{$code}", 'error');
        die;
    }

    public function render($view = null, $layout = null) {
        if ($layout === null) {
            $layout = $this->layout;
        }

        if ($view == null) {
            $view = $this->request->action;
        }

        echo $this->View->render("{$this->request->controller}/{$view}", $layout);
        $this->rendered = true;
    }
    public function renderText($text, $layout = null) {
        if ($layout === null) {
            $layout = $this->layout;
        }

        $this->set('__content', $text);
        echo $this->View->fetch($layout);
        $this->rendered = true;
    }
    public function fetch($view) {
        return $this->View->fetch($view, false);
    }

    public function set($name, $value=null) {
        if (is_array($name) && $value === null) {
            foreach ($name as $k=>$v) {
                $this->View->set($k, $v);
            }
        } else {
            $this->View->set($name, $value);
        }
    }

    public function redirect($url) {
        ob_clean();
        header('Location: ' . $url);
        exit;
    }

    public function requestAuthentication() {
        $user = @$_SESSION['auth_user'];
        $isSafe = in_array($this->request->action, $this->safeActions);
        if (!$user && !$isSafe) {
            $this->session('referer', APP::request2url($this->request));
            $this->login_form();
            die;
        }
    }

    public function logout() {
        $this->session_delete('auth_user');
        $this->redirect('/admin');
    }

    public function session($name, $value=null) {
        if ($value === null) {
            return @$_SESSION[$name];
        } else {
            $_SESSION[$name] = $value;
        }
    }

    public function session_delete($name) {
        unset($_SESSION[$name]);
    }

    protected function flash($message, $class='error') {
        $this->session('flash', array(
            'message' => $message,
            'class'   => $class
        ));
    }

    protected function POST($name, $default=false) {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    protected function GET($name, $default=false) {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    protected function REQUEST($name, $default=false) {
        $ret = $this->GET($name, $default);
        if ($ret === $default) {
            $ret = $this->POST($name, $default);
        }

        return $ret;
    }

    protected function FILE($name, $default=false) {
        return isset($_FILES[$name]) ? $_FILES[$name] : $default;
    }

    protected function isAjax() {
        return isset($_REQUEST['__ajax_req']) && $_REQUEST['__ajax_req'];
    }
}

?>