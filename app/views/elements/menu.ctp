<menu>
    <li class="toggler"></li>
    <? foreach($menu as $page):
        $url   = "/{$page->key}/{$page->id}/";
        $route = APP::parseUrl($url);
        $selected = false;

        $c1 = $route->controller == $this->route->controller;
        $c2 = $route->action     == $this->route->action;
        $c3 = @$route->params[0] == @$this->route->params[0];
        if ($c1 && $c2 && $c3) {
            $selected = true;
        }
    ?>
    <li<? if($selected): ?> class="selected"<? endif ?>><?= $this->link($page->title, $url) ?></li>
    <? endforeach ?>
    <li<? if($this->route->controller == 'kontakt'): ?> class="selected"<? endif ?>><?= $this->link('Kontakt', '/kontakt') ?></li>
</menu>