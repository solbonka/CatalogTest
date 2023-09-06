
<div class="admin-panel">
    <h1>Административная панель</h1>

    <div class="product-form">
        <h2>Добавление/обновление продукта</h2>

        <?php $form = \yii\widgets\ActiveForm::begin(['action' => '/admin-panel/update', 'method' => 'POST']); ?>

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'name')->textInput(['id' => 'product_name'])->label('Название продукта:') ?>

        <?= $form->field($model, 'price')->textInput(['id' => 'price'])->label('Цена:') ?>

        <?= $form->field($model, 'category')->textInput(['id' => 'category'])->label('Категория:') ?>

        <div id="properties-container">
        </div>

        <button type="button" onclick="addProperty()">Добавить свойство</button>

        <?= \yii\helpers\Html::submitButton('Сохранить', ['id' => 'my-btn']) ?>

        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>
<style>
    .admin-panel {
        background-color: #f2f2f2;
        padding: 10px;
    }

    h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 18px;
        color: #333;
        margin-bottom: 10px;
    }

    .product-form {
        background-color: #fff;
        padding: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-size: 16px;
        color: #333;
    }

    input[type="text"] {
        width: 100%;
        padding: 5px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    button {
        background-color: #337ab7;
        color: #fff;
        padding: 10px 20px;
        border-radius: 3px;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }

    #properties-container {
        margin-top: 10px;
    }
</style>
<script>
    let i = 0;
    function addProperty() {
        var container = document.getElementById("properties-container");

        var div = document.createElement("div");

        var propertyLabel = document.createElement("label");
        propertyLabel.textContent = "Свойство:";
        div.appendChild(propertyLabel);

        var propertyInput = document.createElement("input");
        propertyInput.type = "text";
        propertyInput.name = "property[" + i + "]";
        propertyInput.placeholder = "Свойство";
        div.appendChild(propertyInput);

        var valueLabel = document.createElement("label");
        valueLabel.textContent = "Значение свойства:";
        div.appendChild(valueLabel);

        var valueInput = document.createElement("input");
        valueInput.type = "text";
        valueInput.name = "value[" + i + "]";
        valueInput.placeholder = "Значение свойства";
        div.appendChild(valueInput);
        ++i;
        container.appendChild(div);
    }
</script>
<?php
$js = <<<JS
    $('#my-btn').on('click', function() {
        var formData = $(this).parent().serializeArray();
        console.log(formData);
        $.ajax({
            url: '/admin-panel/update',
            type: 'POST',
            header: '&csrf=' + $('meta[name="csrf-token"]').attr('content'),
            data: formData,
            success: function(response) {
                alert('Продукт добавлен!');
            },
            error: function() {
                alert('Error!');
            }
        });
    });
JS;
$this->registerJs($js);
?>

