<?php

abstract class AppController extends Controller {
    
    public function beforeFilter() {
        $this->set('menu', PageModel::menu());
    }
}