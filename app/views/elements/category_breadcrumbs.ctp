<? if(! empty($category->breadcrumbs)): ?>
    <?= implode('&raquo;', array_map(function($c){
        return $this->link($c->name, "/products/index/{$c->id}/".APP::urlize($c->name));
    }, $category->breadcrumbs)) ?>
    <? if (!@$no_append): ?> &raquo; <? endif ?>
<? endif ?>