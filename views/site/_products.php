<?php if ($products):
    foreach ($products as $product): ?>
    <div class="product-item category<?= $product->category['id'] ?>">
        <h3><?= $product['name'] ?></h3>
        <p class="price"><?= $product['price'] ?> руб.</p>
        <p class="category<?= $product->category['id'] ?>">Категория: <?= $product->category['name'] ?></p>
    </div>
<?php endforeach; else: ?>
    <div class="product-item category">
        <h3>Тут пока пусто</h3>
    </div>
<?php endif;?>
