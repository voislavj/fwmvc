<?php

class Mail {

    private $from;
    private $to;
    private $subject;
    public $body;

    private $format = "text/html; charset=utf-8";

    public function from($email, $name=null) {
        if ($name) {
            $email = "{$name}<{$email}>";
        }
        $this->from = $email;
        return $this;
    }
    public function to($to) {
        $this->to = $to;
        return $this;
    }
    public function subject($subject) {
        $this->subject = $subject;
        return $this;
    }
    public function body($body) {
        $this->body = $body;
        return $this;
    }
    public function format($format) {
        $this->format = $format;
        return $this;
    }

    public function send() {
        $headers = "Content-Type: {$this->format}\n".
                   "From: {$this->from}";

        return mail($this->to, $this->subject, $this->body, $headers);
    }

    public function debug() {
        echo "<pre>From: ".htmlentities($this->from)."\n".
             "To: {$this->to}\n".
             "Content-Type: {$this->format}\n".
             "Subject: {$this->subject}\n\n".
             "{$this->body}".
             "</pre>";
    }

}

?>