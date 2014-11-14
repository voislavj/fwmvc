<!DOCTYPE html>
<html class="login">
<head>
    <title><?= APP::settings('general.name') ?> : Administracija<?= $this->pageTitle() ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

    <?= $this->css('jquery-ui') ?>
    <?= $this->asset('css', 'admin') ?>

    <?= $this->js('jquery-2.1.1.min') ?>
    <?= $this->js('jquery-ui-1.11.2.min') ?>

    <?= $this->head('meta') ?>
    <?= $this->head('css') ?>
    <?= $this->head('js') ?>
</head>

<body>
    <div id="main">
        <?= $__content ?>
    </div>
</body>
</html>