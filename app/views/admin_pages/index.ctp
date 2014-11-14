<h1>Strane</h1>

<p><?= $this->link('+Kreiraj stranicu', '/admin_pages/create') ?></p>

<? if(empty($pages)): ?>
    <h2>Trenutno nema stranica.</h2>
<? else: ?>
    <table>
        <tr>
            <th>Id</th>
            <th>Jezik</th>
            <th>Naslov</th>
            <th>U meniju?</th>
            <th>Redosled</th>
            <th></th>
        </tr>
        <? foreach ($pages as $page): ?>
        <tr>
            <td><?= $page->id ?></td>
            <td><?= $page->language ?></td>
            <td><?= $this->link($page->title, "/admin_pages/edit/{$page->id}") ?></td>
            <td><?= $page->menu ? 'da' : 'ne' ?></td>
            <td><?= $page->position ?></td>
            <td><?= $this->link('obriši', '/admin_pages/delete/'.$page->id, array(
                'confirm' => 'Da li želite da obrišete stranicu "'.$page->title.'"?'
            )) ?></td>
        </tr>
        <? endforeach ?>    
    </table>
<? endif ?>