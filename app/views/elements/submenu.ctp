<? if (! empty($data)): ?>

<menu class="submenu">
<? foreach($data as $item): ?>
<?
    $options  = array();
    $url      = $link($item);
    $selected = is_callable($select) ? $select($item, $entity) : false;

    if ($selected) {
        $options['class'] = 'selected';
    }
?>
    <li<? if($selected): ?> class="selected"<? endif ?>><?= $this->link($item->{$label}, $url, $options) ?></li>
<? endforeach ?>
</menu>
<? endif ?>