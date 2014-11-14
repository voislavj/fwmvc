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
    <?= $this->asset('css', 'error') ?>
</head>

<body><div class="cell">
    <?= $this->img('cvecaraflos.png') ?>
    <p><?= $__content ?></p>
</div></body>
</html>