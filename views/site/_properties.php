<?php if (isset($properties)) {
    foreach ($properties as $property) { ?>
        <label for="property_<?php echo $property['id']; ?>"><?php echo $property['name']; ?>:</label>
        <select name="property_<?php echo $property['id']; ?>" id="property_<?php echo $property['id']; ?>">
            <option value="">Все</option>
            <?php foreach ($property->propertyValues as $value) { ?>
                <option value="<?php echo $value['id']; ?>"><?php echo $value['value']; ?></option>
            <?php } ?>
        </select>
    <?php }
    } else { ?>
<?php }?>
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
