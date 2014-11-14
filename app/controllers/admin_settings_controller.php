<?php

class AdminSettingsController extends AdminController {

    public function index() {
        $this->set('settings', APP::settings('*'));
    }

    public function save() {
        $data = $this->POST('data');
        if ($data) {
            $data = serialize($data);
            $path = APP::$ROOT . '/config/settings.data';
            if (file_put_contents($path, $data)) {
                $this->flash('Podešavanja su sačuvana.', 'ok');
            } else {
                $this->flash('Podešavanja nisu sačuvana.');
            }
        }

        $this->redirect('/admin_settings');
    }

}