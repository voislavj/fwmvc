<?php
    if (isset($edit) && $edit) {
        $link = "/admin_categories/edit/%d";
        $options = array(
            'remote' => true,
            'data' => array(
                'before'   => 'categoryLoading()',
                'complete' => 'editCategory(req)'
            )
        );
    } else {
        $link = "javascript:void(0)";
        $options = array();
    }
?>
<? if(! empty($categories)): ?>
<ul class="tree">
    <? foreach ($categories as $category): ?>
    <li value="<?= $category->id ?>">
        <?= $this->link($category->name, sprintf($link, $category->id),
            array_merge(array('class' => 'label'), $options)
        ) ?>
        <? if(! empty($category->children)): ?>
            <?= $this->partial('../elements/category_list', array('categories' => $category->children, 'parent_id' => $category->id, 'edit' => $edit )) ?>
        <? endif ?>
    </li>
    <? endforeach ?>
</ul>
<? endif ?>