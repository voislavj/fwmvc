<!DOCTYPE html>
<html lang="sr">
<head>
    <title><?= APP::settings('general.name') . $this->pageTitle() ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    
    <?= $this->favicon() ?>
    <?= $this->meta('keywords', APP::settings('general.keywords')) ?>
    <?= $this->meta('description', APP::settings('general.description')) ?>

    <?= $this->asset('css', 'index') ?>

    <?= $this->js('jquery-2.1.1.min') ?>
    <?= $this->js('modernizr.custom.min') ?>
    <?= $this->asset('js', 'index') ?>

    <?= $this->head('meta') ?>
    <?= $this->head('css') ?>
    <?= $this->head('js') ?>
</head>

<body>
<header>
    <div class="cell">
        <div class="width-container">
            <?= $this->link($this->img('pramac.png'), '/', array(
                'id' => 'logo'
            )) ?>
            <?= $this->element('menu') ?>

            <p class="languages">
            <? $activeLang = Locale::get() ?>
            <? foreach(Locale::languages() as $key=>$language): ?>
                <?= $this->link($language, "/language/{$key}", array(
                    'class' => $key . ($activeLang == $key ? " selected" : "")
                )); ?>
            <? endforeach ?>
            </p>
        </div>
    </div>
</header>
<div id="main">
    <div class="cell">
        <div class="width-container"><?= $__content ?></div>
    </div>
</div>
<footer>
    <div class="cell">
        <div class="width-container">
            <?
                $addr  = APP::settings('address');
                $phone = APP::settings('phone');
            ?>
            <?= $addr['street'] ?> &middot; <?= $addr['zip'] ?> <?= $addr['city'] ?>
            <span class="block">
                <span class="separator">&middot;</span> <?= $phone['home'] ?>
                &middot; <?= $phone['mobile'] ?>
            </span>
        </div>
    </div>
</footer>
</body>
</html>