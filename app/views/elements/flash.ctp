<div class="flash-message <?= $flash->class ?>">
    <a href="javascript:void(0)" onclick="hideFlash()" class="close">x</a>
    <?= $flash->message ?>
</div>
<script type="text/javascript">
function hideFlash() {
    $('.flash-message').animate({top: -100});
}
$(function(){
    setTimeout(hideFlash, 5000);
});
</script>