<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
/** @var array $categories */
/** @var array $products */
/** @var array $properties */
?>
<div class="site-index">
    <div class="container">

        <div class="sidebar">
            <h2>Фильтры</h2>

            <form action="/site/catalog" method="POST" id="filter-form">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                <label for="category">Категория:</label>
                <select name="category" id="category">
                    <option value="">Все категории</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="properties">

                </div>
                <input type="submit" value="Применить">
            </form>
        </div>
        <div class="product-grid">
            <?php
            foreach ($products as $product): ?>
                <div class="product-item category<?= $product->category['id'] ?>">
                    <h3><?= $product['name'] ?></h3>
                    <p class="price"><?= $product['price'] ?> руб.</p>
                    <p class="category<?= $product->category['id'] ?>">Категория: <?= $product->category['name'] ?></p>
                </div>
            <?php endforeach; ?>
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

    .category<?= $product->category['id'] ?>{
        font-style: italic;
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
                header: '&csrf=' + $('meta[name="csrf-token"]').attr('content'),
                data: formData,
                success: function(response) {

                    $('.product-grid').html(response['products']);
                    $('.properties').html(response['properties']);
                }
            });
        });
    });

</script>

