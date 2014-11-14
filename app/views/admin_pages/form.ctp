<?= $this->form('data', '/admin_pages/save'); ?>
    <h1><? if($data->id > 0): ?>
        Izmena strane
    <? else: ?>
        Nova strana
    <? endif ?></h1>

    <?= $this->hidden('id', $data->id) ?>

    <p><?= $this->select('language', Locale::languages(), array(
        'value' => $data->language,
        'label' => 'Jezik:',
        'empty' => false
    )) ?>
    <span style="margin-left:20px"><?= $this->checkbox('menu', array(
        'label'   => 'Prikaz u Meniju?',
        'checked' => $data->menu
    )) ?></span></p>
    <p><?= $this->text('title', array(
        'value' => $data->title,
        'label' => 'Naslov:'
    )) ?></p>

    <div><?= $this->textarea('content', array(
        'value' => $data->content,
        'label' => 'Sadržaj:'
    )) ?></div>

    <p><?= $this->submit('Sačuvaj') ?></p>
<?= $this->formEnd() ?>