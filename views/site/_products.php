<?php if ($products) {
    foreach ($products as $product) { ?>
        <div class="product-item category<?php echo $product->category['id']; ?>">
            <h3><?php echo $product['name']; ?></h3>
            <p class="price"><?php echo $product['price']; ?> руб.</p>
            <p class="category<?php echo $product->category['id']; ?>">Категория: <?php echo $product->category['name']; ?></p>
            <?php
            $groupedProperties = [];
            foreach ($product->productProperties as $productProperty) {
                $groupName = $productProperty->property['name'];
                if (!isset($groupedProperties[$groupName])) {
                    $groupedProperties[$groupName] = [];
                }
                $groupedProperties[$groupName][] = $productProperty->value['value'];
            }
            ?>
            <?php foreach ($groupedProperties as $groupName => $groupedValues) { ?>
                <p class="property<?php echo $productProperty->property['id']; ?>">
                    <?php echo $groupName; ?>:
                    <?php echo implode(', ', $groupedValues); ?>
                </p>
            <?php } ?>
        </div>
    <?php }
    } else { ?>
    <div class="product-item category">
        <h3>Тут пока пусто</h3>
    </div>
<?php }?>
