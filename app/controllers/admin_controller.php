<?php

class AdminController extends Controller {

    private $IMAGES = array('png', 'jpeg', 'jpg', 'bmp', 'gif');

    public $safeActions = array('login', 'password_reset', 'password_reset_save');

    public $layout = 'admin';

    public function beforeFilter() {
        $this->requestAuthentication();

        $this->set('menu', array(
            'Strane'         => '/admin_pages',
            'Podešavanja'    => '/admin_settings',
            'Izlaz'          => '/admin/logout',
        ));
    }

    public function index() {
        $this->redirect('/admin_pages');
    }

    public function login_form() {
        $this->layout = 'login';
        $this->render('../admin/login');
    }

    public function login() {
        $data = @$_POST['data'];
        if (! empty($data)) {

            $user = UserModel::build()
                        ->where(array(
                            'email' => $data['email'],
                            'password' => $this->hash($data['password'])
                        ))
                        ->first();
        }

        if (! $user) {
            $this->flash('Prijava neuspela');
        }

        $this->session('auth_user', $user);
        $this->redirect($this->session('referer'));
    }

    public function password_reset($key=null) {
        $this->layout = 'login';

        if ($key) {
            return $this->password_reset_validate($key);
        }

        $data = $this->POST('data');
        if (! empty($data)) {
            $email = $data['email'];

            if (empty($email)) {
                $this->flash('Morate uneti Vašu E-Mail adresu.');
            } else {
                $user = UserModel::build()
                            ->where(array('email' => $email))
                            ->first();
                if ($user) {
                    $buffer = "qwertyuiopasdfghjklzxcvbnm0123456789";
                    $key = "";
                    for($i=0; $i<30; $i++) {
                        $key .= substr($buffer, rand(0, strlen($buffer)-1), 1);
                    }
                    $expiration = date("Y-m-d H:i:s", time() + 60*60*24*1);

                    UserModel::update(array(
                        'change_password_key' => $key,
                        'change_password_expire' => $expiration
                    ), array( 'id' => $user->id));
                    
                    $this->set(compact('key', 'expiration'));
                    
                    $mail = new Mail();
                    $mail->from(APP::settings('general.email'), APP::settings('general.name'))
                         ->to($user->email)
                         ->subject("Zahtev za obnovu lozinke")
                         ->body($this->fetch('admin/password_reset_email'));

                    if ($mail->send()) {
                        $this->flash('Upustvo za obnovu lozinke je prosleđeno na navedenu E-Mail adresu. Hvala.', 'ok');
                        return $this->redirect('/admin');
                    } else {
                        $this->flash('Greška pri slanju E-Mail poruke sa uputstvom za obnovu lozinke. Molimo pokušajte ponovo.');
                    }

                } else {
                    $this->flash('Za unetu E-Mail adresu nije pronađen ni jedan nalog.');
                }
            }

            $this->set('data', $data);
        }
    }

    public function password_reset_save() {
        $this->layout = 'login';

        $data = $this->POST('data');
        if ($data) {
            extract($data);

            if (! $user = $this->getPasswordResetUser($key)) {
                $this->flash('Nevalidan zahtev.');
                return $this->redirect('/admin');
            }

            if ($password_test != $password) {
                $this->flash('Unete lozinke se ne podudaraju.');
            } elseif(strlen($password) < 6) {
                $this->flash('Minimalna dužina lozinke je 6 karaktera.');
            } else {
                UserModel::update(array(
                    'change_password_key' => NULL,
                    'change_password_expire' => NULL,
                    'password' => $this->hash($password)
                ), array('id' => $user->id));

                $this->flash('Uspešno ste postavili novu lozinku. Možete nastaviti sa prijavom.', 'ok');
                return $this->redirect('/admin');
            }

            $this->set(compact('key'));
            return $this->render('password_reset_new');
        }

        $this->redirect('/admin');
    }

    protected function password_reset_validate($key) {
        $user = $this->getPasswordResetUser($key);
        if (! $user) {
            $this->flash('Nevalidan zahtev.');
            return $this->redirect('/admin/password_reset');
        }

        $this->set(compact('key', 'user'));
        $this->render('password_reset_new');
    }

    protected function getUploadImages($name, $newData, $originalData) {
        return $this->getUploadFiles($name, $newData, $originalData, 'image', $this->IMAGES);
    }

    protected function getUploadFiles($name, $newData, $originalData, $fieldName, $extensions=null) {
        return new FileUpload($name, $newData, $originalData, $fieldName, $extensions);
    }

    private function hash($pass) {
        return md5($pass);
    }

    private function getPasswordResetUser($key) {
        return UserModel::build()
                ->where(array(
                    'change_password_key' => $key,
                    'change_password_expire >' => date('Y-m-d H:i:s')
                ))
                ->first();
    }

}

?>