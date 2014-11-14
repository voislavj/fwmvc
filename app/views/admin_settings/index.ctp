<h1>Podešavanja</h1>

<form action="/admin_settings/save" method="post">
    <?= $this->submit('Sačuvaj', array('class' => 'top')) ?>
    
    <fieldset class="left" style="width:30%;margin-right:10px">
        <legend>Opšte</legend>

        <p><?= $this->text(array('data', 'general', 'name'), array(
            'label' => 'Ime:',
            'value' => @$settings['general']['name']
        )) ?></p>

        <p><?= $this->text(array('data', 'general', 'description'), array(
            'label' => 'Kratak opis:',
            'value' => @$settings['general']['description']
        )) ?></p>
        <p><?= $this->text(array('data', 'general', 'keywords'), array(
            'label' => 'Ključne reči:',
            'value' => @$settings['general']['keywords']
        )) ?></p>
        <p><?= $this->text(array('data', 'general', 'email'), array(
            'label' => 'E-Mail:',
            'value' => @$settings['general']['email']
        )) ?></p>
    </fieldset>

    <fieldset style="width:30%">
        <legend>Adresa</legend>

        <p><?= $this->text(array('data', 'address', 'street'), array(
            'label' => 'Ulica:',
            'value' => @$settings['address']['street']
        )) ?></p>
        <p><?= $this->text(array('data', 'address', 'city'), array(
            'label' => 'Grad:',
            'value' => @$settings['address']['city']
        )) ?></p>
        <p><?= $this->text(array('data', 'address', 'zip'), array(
            'label' => 'Poštanski broj:',
            'value' => @$settings['address']['zip'],
            'class' => 'medium'
        )) ?></p>
    </fieldset>

    <br clear="all">

    <fieldset class="left" style="width:30%;margin-right:10px">
        <legend>Kontakt podaci</legend>
        <p><span class="left"><?= $this->text(array('data', 'phone', 'home'), array(
            'label' => 'Fiksni:',
            'value' => @$settings['phone']['home'],
            'class' => 'medium'
        )) ?></span>
        <span class="left"><?= $this->text(array('data', 'phone', 'mobile'), array(
            'label' => 'Mobilni:',
            'value' => @$settings['phone']['mobile'],
            'class' => 'medium'
        )) ?></span></p>
    </fieldset>

    <fieldset class="left" style="width:30%">
        <legend>Banka</legend>

        <p><?= $this->text(array('data', 'bank', 'name'), array(
            'label' => 'Ime:',
            'value' => @$settings['bank']['name']
        )) ?></p>

        <p><?= $this->text(array('data', 'bank', 'number'), array(
            'label' => 'Broj računa:',
            'value' => @$settings['bank']['number']
        )) ?></p>
    </fieldset>

    <br clear="all">
    <?= $this->map(array('data'), array(
        'label' => 'Lokacija:',
        'value' => array(
            'map' => @$settings['map'],
            'streetview' => @$settings['streetview']
        ),
        'width' => 400,
        'height'=> 300
    )) ?>

    <br clear="all">
</form>