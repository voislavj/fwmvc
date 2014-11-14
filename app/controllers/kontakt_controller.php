<?php

class KontaktController extends AppController {

    public $pageTitle = 'Kontakt';

    public function index() {
        $data = $this->POST('data');
        if (! empty($data)) {

            $captcha = $this->session('captcha');
            $captcha_formula = $this->session('captcha_formula');
            if(empty($data['name']) || empty($data['email']) || empty($data['message']) || empty($data['captcha'])) {
                $this->flash('Morate popuniti sva polja.');
            } else if ($captcha != $data['captcha']) {
                $this->flash("Pogrešan sigurnosni tekst. {$captcha_formula} {$captcha}, a ne ".$data['captcha']);
            } else {
                extract($data);

                $mail = (new Mail())
                    ->to(APP::settings('general.email'))
                    ->from($email, $name)
                    ->subject("Kontakt sa ".$_SERVER['SERVER_NAME'].": {$name}")
                    ->body(nl2br($message));

                if ($mail->send()) {
                    $this->flash('Vaša poruka je uspešno prosleđena. Hvala.', 'ok');
                    return $this->redirect('/kontakt');
                } else {
                    $this->flash('Greška pri slanju poruke. Molimo pokušajte ponovo.');
                }
            }


            $this->set('data', $data);
        }
    }

    public function captcha() {
        $this->autoRender = false;

        $img  = new Imagick();
        $draw = new ImagickDraw();
        $bg   = new ImagickPixel('white');
        $fg   = new ImagickPixel('#555');

        $w = 100; $h = 30;
        $img->newImage($w, $h, $bg);

        $draw->setFillColor($fg);
        $draw->setFontSize(24);

        $draw->setFont(APP::$ROOT . DS . 'public' . DS . 'fonts' . DS . 'PTSansNarrow.ttf');

        $min = 10; $max = 30;
        $num1 = rand($min, $max);
        $num2 = rand($min, $max);
        $text = "{$num1} + {$num2} =";
        $this->session('captcha_formula', $text);
        $this->session('captcha', $num1+$num2);

        // calculate text position
        $metrics = $img->queryFontMetrics($draw, $text);
        $x = $w - $metrics['textWidth'];
        $y = $metrics['textHeight'] - ($h-$metrics['textHeight']) - 4;
        
        $img->annotateImage($draw, $x, $y, 0, $text);

        header('Content-Type: image/png');
        $img->setImageFormat('png');
        echo $img;
    }

}