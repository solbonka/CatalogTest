<div class="admin-panel" id="admin-panel">
    <h1>Административная панель</h1>

    <div class="product-form" id="product-form">
        <h2>Добавить или обновить продукт?</h2>

        <button type="button" onclick="addProduct()">Добавить продукт</button>
        <button type="button" onclick="updateProduct()">Обновить продукт</button>
    </div>
    <div class="product-grid"  id="product-grid"></div>
</div>
<style>
    input[type="submit"],input[type="button"] {
        background-color: #337ab7;
        color: #fff;
        padding: 10px 20px;
        border-radius: 3px;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }

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
        margin-right: 10px;
        margin-left: 10px;
        margin-top: 10px;
    }

    #properties-container {
        margin-top: 10px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let i = 1;
    function addProperty() {
        var container = document.getElementById("properties-container");

        var propertyLabel = document.createElement("label");
        propertyLabel.textContent = "Свойство:";
        container.appendChild(propertyLabel);

        var propertyInput = document.createElement("input");
        propertyInput.type = "text";
        propertyInput.name = "property[" + i + "]";
        propertyInput.placeholder = "Свойство";
        container.appendChild(propertyInput);

        var valueLabel = document.createElement("label");
        valueLabel.textContent = "Значение свойства:";
        container.appendChild(valueLabel);

        var valueInput = document.createElement("input");
        valueInput.type = "text";
        valueInput.name = "value[" + i + "]";
        valueInput.placeholder = "Значение свойства";
        container.appendChild(valueInput);
        ++i;
    }

    function addProduct() {
        var container = document.getElementById("product-form");

        container.innerHTML = '';
        var heading = document.createElement("h2");
        heading.textContent = "Добавление продукта";
        container.appendChild(heading);
        // Создаем форму
        var form = document.createElement("form");
        form.action = "/admin-panel/save";
        form.method = "POST";
        form.id = "form";

        var model = document.createElement("input");
        model.type = "hidden";
        model.name = "model";
        model.value = "AdminPanelForm";
        form.appendChild(model);

        var csrfToken = document.createElement("input");
        csrfToken.type = "hidden";
        csrfToken.name = "_csrf";
        csrfToken.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(csrfToken);

        var productNameContainer = document.createElement("div");
        var productNameLabel = document.createElement("label");
        productNameLabel.textContent = "Название продукта:";
        productNameContainer.appendChild(productNameLabel);
        var productNameInput = document.createElement("input");
        productNameInput.type = "text";
        productNameInput.id = "product_name";
        productNameInput.name = "AdminPanelForm[name]";
        productNameContainer.appendChild(productNameInput);
        form.appendChild(productNameContainer);

        var priceContainer = document.createElement("div");
        var priceLabel = document.createElement("label");
        priceLabel.textContent = "Цена:";
        priceContainer.appendChild(priceLabel);
        var priceInput = document.createElement("input");
        priceInput.type = "text";
        priceInput.id = "price";
        priceInput.name = "AdminPanelForm[price]";
        priceContainer.appendChild(priceInput);
        form.appendChild(priceContainer);

        var categoryContainer = document.createElement("div");
        var categoryLabel = document.createElement("label");
        categoryLabel.textContent = "Категория:";
        categoryContainer.appendChild(categoryLabel);
        var categoryInput = document.createElement("input");
        categoryInput.type = "text";
        categoryInput.id = "category";
        categoryInput.name = "AdminPanelForm[category]";
        categoryContainer.appendChild(categoryInput);
        form.appendChild(categoryContainer);

        var propertiesContainer = document.createElement("div");
        propertiesContainer.id = "properties-container";
        form.appendChild(propertiesContainer);

        var propertyLabel = document.createElement("label");
        propertyLabel.textContent = "Свойство:";
        propertiesContainer.appendChild(propertyLabel);

        var propertyInput = document.createElement("input");
        propertyInput.type = "text";
        propertyInput.name = "property[" + 0 + "]";
        propertyInput.placeholder = "Свойство";
        propertiesContainer.appendChild(propertyInput);

        var valueLabel = document.createElement("label");
        valueLabel.textContent = "Значение свойства:";
        propertiesContainer.appendChild(valueLabel);

        var valueInput = document.createElement("input");
        valueInput.type = "text";
        valueInput.name = "value[" + 0 + "]";
        valueInput.placeholder = "Значение свойства";
        propertiesContainer.appendChild(valueInput);

        var addPropertyButton = document.createElement("button");
        addPropertyButton.type = "button";
        addPropertyButton.textContent = "Добавить свойство";
        addPropertyButton.onclick = addProperty;
        form.appendChild(addPropertyButton);

        var saveButton = document.createElement("button");
        saveButton.type = "submit";
        saveButton.textContent = "Сохранить";
        saveButton.id = "my-btn";
        form.appendChild(saveButton);

        container.appendChild(form);
    }

    function updateProduct() {
        var container = document.getElementById("product-form");
        container.innerHTML = '';
        var cont = document.getElementById("admin-panel");
        $.ajax({
            url: '/admin-panel/products',
            type: 'GET',
            success: function(response) {
                $('.product-grid').html(response);
                var newDiv = document.createElement("div")
                var newButton = document.createElement("input");
                newButton.setAttribute("type", "button");
                newButton.setAttribute("value", "Добавить или обновить ещё товар");
                newButton.setAttribute("onClick", 'location.href="http://localhost:82/admin-panel/panel"');
                newDiv.appendChild(newButton);
                cont.appendChild(newDiv);
            }
        });
    }
</script>
<?php
$js = <<<JS
    $('#my-btn').on('click', function() {
      var priceField = $('#price').val();
      
      if (priceField !== '') {
        var priceValue = parseFloat(priceField);
    
        if (!isNaN(priceValue) && priceValue % 1 !== 0 || Number.isInteger(priceValue)) {
          var formData = $(this).parent().serializeArray();
          
          $.ajax({
            url: '/admin-panel/save',
            type: 'POST',
            header: '&csrf=' + $('meta[name="csrf-token"]').attr('content'),
            data: formData,
            success: function() {
                alert('Продукт добавлен!');
            },
            error: function() {
                alert('Error!');
            }
          });
        } else {
          alert('Поле "Цена" должно содержать целое или дробное число!');
        }
      } else {
        alert('Поле "Цена" должно быть заполнено!');
      }
    });
JS;
$this->registerJs($js);
?>

