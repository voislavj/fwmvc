<?php

class AdminPagesController extends AdminController {

    public function index($page=1) {
        $pages = PageModel::build()
                    ->page($page)
                    ->query();

        $this->set(compact('page', 'pages'));
    }

    public function create() {
        $this->set('data', PageModel::create());
        $this->render('form');
    }

    public function edit($id) {
        $this->set('data', PageModel::get($id));
        $this->render('form');
    }

    public function save() {
        $data = $this->POST('data');
        if ($data) {
            $data['key'] = APP::urlize($data['title']);

            if (PageModel::save($data)) {
                $id = $data['id'] ? $data['id'] : PageModel::lastInsertId();
                $this->flash('Strana je sačuvana.', 'ok');
                return $this->redirect("/admin_pages/edit/{$id}");
            } else {
                $error = PageModel::error();
                var_dump(PageModel::sql(), $error);die;
                $this->flash("Greška pri čuvanju strane. " . $error->message);
                $this->set('data', (object)$data);
                return $this->render('form');
            }
        }

        $this->redirect('/admin_pages');
    }

}