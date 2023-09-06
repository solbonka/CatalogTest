<?php if(isset($properties)):
    foreach ($properties as $property): ?>
        <label for="property_<?= $property['id'] ?>"><?= $property['name'] ?>:</label>
        <select name="property_<?= $property['id'] ?>" id="property_<?= $property['id'] ?>">
            <option value="">Все</option>
            <?php foreach ($property->propertyValues as $value): ?>
                <option value="<?= $value['id'] ?>"><?= $value['value'] ?></option>
            <?php endforeach; ?>
        </select>
    <?php endforeach; else: ?>
<?php endif;?>
<script>
    $('select[name^="property_"]').on('change', function(e) {
        e.preventDefault();

        var formData = $(this).closest('form').serializeArray();
        console.log(formData);
        $.ajax({
            url: '/site/catalog',
            type: 'POST',
            header: '&csrf=' + $('meta[name="csrf-token"]').attr('content'),
            data: formData,
            success: function(response) {
                $('.product-grid').html(response);
            }
        });
    });
</script>
