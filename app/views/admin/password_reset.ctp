<form action="/admin/password_reset" method="post">
    <?= $this->link($this->img('cvecaraflos.png'), '/admin', array(
        'id' => 'logo'
    )) ?>

    <?= $this->text(array('data', 'email'), array(
        'label' => 'E-Mail:',
        'value' => @$data['email']
    )) ?>

    <?= $this->submit('Obnovi lozinku') ?>
    <?= $this->link('&laquo; nazad', '/admin') ?>
    <?= $this->flash() ?>
</form>