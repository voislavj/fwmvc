<ul class="products">
<? foreach($products as $product): ?>
    <li>
        <?= $this->link($this->img("products/{$product->category_id}/{$product->image}"),
            "/products/product/{$product->id}/" . APP::urlize($product->name)) ?>
        <h3><?= $product->name ?></h3>
        <p class="price"><?= $this->currency($product->price) ?></p>
    </li>
<? endforeach ?>
</ul><br clear="all">