<?
    $this->head('css', 'assets/tree');
    $this->head('js',  'assets/tree');
    $edit = isset($edit) ? $edit : false;
?>
<div class="tree-container left">
    <?= $this->partial('../elements/category_list', array('categories' => $categories, 'edit' => !!@$edit)) ?>
</div>

<script type="text/javascript">
$(function(){

    var treeOptions = {};
    <? if ($edit): ?>
    treeOptions = {
        order: function(liFrom, liTo) {
            var parent_id = liTo ? liTo.attr('value') : null;
            var id        = liFrom.attr('value');
            
            var container = $('.tree-container');
            $.ajax({
                url: '/admin_categories/reorder/'+id+'/'+parent_id,
                beforeSend: function() {
                    container.addClass('loading');
                },
                complete: function(req) {
                    location.reload();
                }
            })
        },
        sort: function(uls){
            var order = {}
            uls.each(function(){
                this.children('li').each(function(index){
                    order[this.value] = index;
                });
            });

            var container = $('.tree-container');
            $.ajax({
                url: '/admin_categories/resort',
                data: {order: order},
                beforeSend: function() {
                    container.addClass('loading');
                },
                complete: function(req) {
                    location.reload();
                }
            });
        }
    }
    <? endif ?>

    $('.tree-container>.tree').tree(treeOptions);

    // init tree clicks
    $('.label').click(function(){
        var li = $(this).parent();
        var id = li.val();
        
        $('.tree li').removeClass('selected');
        li.addClass('selected');

        <? if($edit): ?>
        location.hash = '!' + $(this).data('url');
        <? endif ?>
        
        // stop propagation
        return false;
    });
});

function treeSelect(id) {
    $('.tree li').removeClass('selected');
    var li = $('.tree li[value='+id+']');

    li.addClass('selected');
    li.parents('li').addClass('opened');
}
</script>