<?php

/** @var yii\web\View $this */
$this->title = 'My Yii Application';
/* @var array $categories */
/* @var array $products */
/* @var array $properties */
/* @var array $productProperties */
?>
<div class="site-index">
    <div class="container">

        <div class="sidebar">
            <h2>Фильтры</h2>

            <form action="/site/catalog" method="POST" id="filter-form">
                <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>">
                <label for="category">Категория:</label>
                <select name="category" id="category">
                    <option value="">Все категории</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>
                <div class="properties">

                </div>
                <input type="submit" value="Применить">
            </form>
        </div>
        <div class="product-grid">
            <?php foreach ($products as $product) { ?>
                <div class="product-item product<?php echo $product->id; ?>">
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
            <?php } ?>
        </div>
    </div>
</div>
<style>
    .container {
        display: flex;
    }

    .sidebar {
        flex: 0 0 25%;
        padding: 20px;
        background-color: #f4f4f4;
    }

    .product-grid {
        flex: 0 0 75%;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
        background-color: #fff;
    }

    .product-item {
        border: 1px solid #ccc;
        padding: 20px;
    }

    .price {
        font-weight: bold;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('select[name="category"]').on('change', function(e) {
            e.preventDefault();

            var formData = $(this).serializeArray();
            console.log(formData);
            $.ajax({
                url: '/site/catalog',
                type: 'POST',
                headers: {
                    '<?= Yii::$app->request->csrfParam; ?>': '<?= Yii::$app->request->csrfToken; ?>'
                },
                data: formData,
                success: function(response) {
                    console.log(response);
                    $('.product-grid').html(response['products']);
                    $('.properties').html(response['properties']);
                }
            });
        });
    });
</script>
