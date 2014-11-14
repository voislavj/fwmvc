<?php

class PagesController extends AppController {

    public function index($id) {
        $this->set('page', PageModel::get($id));
    }

}