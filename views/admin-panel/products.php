<?php /** @var array $products */
if ($products) {
    foreach ($products as $product) { ?>
        <div class="product-item product<?php echo $product->id; ?>" onclick="showForm(this)">
            <input type="hidden" value=<?= $product->id ?>>
            <h3><?php echo $product['name']; ?></h3>
            <p class="price"><?php echo $product['price']; ?> руб.</p>
            <p class="category">Категория: <?php echo $product->category['name']; ?></p>
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
                <p class="property<?php echo $groupName; ?>">
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
<style>

</style>
<script>
    function showForm(clickedItem){
        var pf = document.getElementById("product-form");
        var pg = document.getElementById("product-grid");
        var id = clickedItem.querySelector("input").value;
        var name = clickedItem.querySelector("h3").textContent;
        var price = clickedItem.querySelector(".price").textContent;
        var category = clickedItem.querySelector(".category").textContent.replace("Категория: ", "");

        var groupedProperties = clickedItem.querySelectorAll("p[class^='property']");
        pg.remove();

        var heading = document.createElement("h2");
        heading.textContent = "Обновление продукта";
        pf.appendChild(heading);
        // Создаем форму
        var form = document.createElement("form");
        form.action = "/admin-panel/update";
        form.id = "form";
        form.method = "POST";

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

        var idInput = document.createElement("input");
        idInput.type = "hidden";
        idInput.name = "AdminPanelForm[id]";
        idInput.value = id;
        form.appendChild(idInput);

        var productNameContainer = document.createElement("div");
        var productNameLabel = document.createElement("label");
        productNameLabel.textContent = "Название продукта:";
        productNameContainer.appendChild(productNameLabel);
        var productNameInput = document.createElement("input");
        productNameInput.type = "text";
        productNameInput.id = "product_name";
        productNameInput.name = "AdminPanelForm[name]";
        productNameInput.value = name;
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
        priceInput.value = price;
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
        categoryInput.value = category;
        categoryContainer.appendChild(categoryInput);
        form.appendChild(categoryContainer);

        var propertiesContainer = document.createElement("div");
        propertiesContainer.id = "properties-container";
        form.appendChild(propertiesContainer);

        for (let i = 0; i < groupedProperties.length; i++) {
            var propertyText = groupedProperties[i].textContent;
            var propertyName = propertyText.split(":")[0].trim();
            var propertyValue = propertyText.split(":")[1].trim();

            var propertyLabel = document.createElement("label");
            propertyLabel.textContent = "Свойство:";
            propertiesContainer.appendChild(propertyLabel);

            var propertyInput = document.createElement("input");
            propertyInput.type = "text";
            propertyInput.name = "property[" + i + "]";
            propertyInput.value = propertyName;
            propertiesContainer.appendChild(propertyInput);

            var valueLabel = document.createElement("label");
            valueLabel.textContent = "Значение свойства:";
            propertiesContainer.appendChild(valueLabel);

            var valueInput = document.createElement("input");
            valueInput.type = "text";
            valueInput.name = "value[" + i + "]";
            valueInput.value = propertyValue;
            propertiesContainer.appendChild(valueInput);
        }

        var addPropertyButton = document.createElement("button");
        addPropertyButton.type = "button";
        addPropertyButton.textContent = "Добавить свойство";
        addPropertyButton.onclick = addProperty;
        form.appendChild(addPropertyButton);

        var updateButton = document.createElement("button");
        updateButton.type = "submit";
        updateButton.textContent = "Обновить";
        updateButton.id = "update-btn";
        form.appendChild(updateButton);

        // Добавляем форму в документ
        pf.appendChild(form);
    }
</script>
