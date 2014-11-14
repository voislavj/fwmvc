<script type="text/javascript">
MAP_DATA    = <?= json_encode(APP::settings('map')) ?>;
STREET_DATA = <?= json_encode(APP::settings('streetview')) ?>;

SETTINGS_NAME = '<?= APP::settings('general.name') ?>';
</script>
<?

    $this->head('css', 'assets/map');
    $this->head('js', 'assets/map');

?>

<div class="white-bg">
    <h1><?= APP::settings('general.name') ?></h1>
    <p class="left">    
        <? $addr = APP::settings('address') ?>
        <b><?= __('Adresa') ?>:</b><?= $addr['street'] ?><br>
        <b></b><?= $addr['zip'] ?> <?= $addr['city'] ?>
    </p>
    <p class="left">
        <? $phone = APP::settings('phone') ?>
        <b><?= __('telefon') ?>:</b> <?= $phone['home'] ?><br>
        <b><?= __('mobilni') ?>:</b> <?= $phone['mobile'] ?>
    </p>
    <p class="left">
        <? $bank = APP::settings('bank') ?>
        <b><?= __('Br.računa') ?>:</b> <?= $bank['name'] ?><br>
        <b></b> <?= $bank['number'] ?>
    </p>

    <div id="map" class="clear white-bg">
        <div class="map"></div>
    </div>

    <form method="post" action="/kontakt#kontakt-forma">
        <h2 id="kontakt-forma"><span><?= __('Imate pitanje, komentar') ?>?</span></h2>
        <p><?= $this->text(array('data', 'name'), array(
            'value' => @$data['name'],
            'label' => __('Vaše ime').":"
        )) ?></p>

        <p><?= $this->email(array('data', 'email'), array(
            'value' => @$data['email'],
            'label' => __('Vaša e-mail adresa').":"
        )) ?></p>

        <p><?= $this->textarea(array('data', 'message'), array(
            'value' => @$data['message'],
            'label' => __('Poruka').':'
        )) ?></p>

        <?= $this->flash() ?>
        <p class="submit">
            <span class="captcha">
            <label for="data_captcha"><?= __('Sigurnosno pitanje') ?>:</label>
            <img src="/kontakt/captcha">
            <?= $this->text(array('data', 'captcha'), array(
                'value' => @$data['captcha']
            )) ?></span>

            <?= $this->submit(__('Pošalji')) ?>
        </p>
    </form>
</div>

<script type="text/javascript">
window.onload = function() {
    var script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&callback=initMap&language=<?= Locale::get() ?>';
    script.type = 'text/javascript';
    document.body.appendChild(script);
}
</script>