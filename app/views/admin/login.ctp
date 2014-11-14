<form action="/admin/login" method="post">
    <?= $this->link($this->img('cvecaraflos.png'), '/admin', array(
        'id' => 'logo'
    )) ?>
    <?= $this->text(array('data', 'email'), array('label' => 'E-Mail:')) ?>
    <?= $this->password(array('data', 'password'), array('label' => 'Lozinka:')) ?>

    <?= $this->submit('Prijavite se') ?>
    <?= $this->link('Zaboravili ste lozinku?', '/admin/password_reset') ?>
    <?= $this->flash() ?>
</form>