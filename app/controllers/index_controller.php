<?php

class IndexController extends AppController {

    public function index() {
        
    }

    public function language($lang) {
        $referer = @$_SERVER['HTTP_REFERER'];
        Locale::set($lang);
        $this->redirect(isset($referer) ? $referer : '/');
    }

}


?>