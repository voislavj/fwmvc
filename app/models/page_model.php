<?php

class PageModel extends Model {
    public $table = 'pages';
    public $order = 'position';

    public $validate = array(
        'title' => array(
            'rule' => '/.+/',
            'message' => 'Polje `Naslov` je obavezno.'
        )
    );

    public static function menu() {
        return self::build()
            ->where(array('menu'=>1))
            ->query();
    }
}