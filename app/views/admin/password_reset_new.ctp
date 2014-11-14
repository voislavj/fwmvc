<form action="/admin/password_reset_save" method="post">
    <?= $this->link($this->img('cvecaraflos.png'), '/admin', array(
        'id' => 'logo'
    )) ?>

    <?= $this->hidden(array('data', 'key'), $key) ?>

    <?= $this->password(array('data', 'password'), array(
        'label' => 'Nova loznika:'
    )) ?>

    <?= $this->password(array('data', 'password_test'), array(
        'label' => 'Nova loznika, još jednom:'
    )) ?>

    <?= $this->submit('Postavi novu lozinku') ?>
    <?= $this->link('&laquo; nazad', '/admin') ?>
    <?= $this->flash() ?>
</form>