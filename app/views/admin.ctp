<!DOCTYPE html>
<html>
<head>
    <title><?= APP::settings('general.name') ?> : Administracija<?= $this->pageTitle() ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

    <?= $this->css('jquery-ui') ?>
    <?= $this->asset('css', 'admin') ?>

    <?= $this->js('jquery-2.1.1.min') ?>
    <?= $this->js('jquery-ui-1.11.2.min') ?>
    <?= $this->js('tinymce-4.1/tinymce.min') ?>
    <?= $this->asset('js', 'index') ?>
    <?= $this->asset('js', 'admin') ?>

    <?= $this->head('meta') ?>
    <?= $this->head('css') ?>
    <?= $this->head('js') ?>
</head>

<body>
    <header>
        <?= $this->link($this->img('pramac.png'), '/admin', array(
            'id' => 'logo'
        )) ?>
        <div id="throbber">Molim sačekajte</div>
        <menu>
            <li class="toggler"></li>
            <? foreach ($menu as $label => $url):
                $route = APP::parseUrl($url);

                $selected = $route->controller == $this->route->controller;
            ?>
                <li<? if($selected): ?> class="selected"<? endif ?>><?= $this->link($label, $url) ?></li>
            <? endforeach ?>
        </menu>
        
    </header>
    <div id="main">
        <?= $this->flash() ?>
        <?= $__content ?>
    </div>
</body>
</html>