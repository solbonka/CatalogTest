<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_properties}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%product}}`
 * - `{{%property}}`
 * - `{{%property_value}}`
 */
class m230902_074955_create_product_properties_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_properties}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'product_id' => $this->integer(),
            'property_id' => $this->integer(),
            'value_id' => $this->integer(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-product_properties-category_id}}',
            '{{%product_properties}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-product_properties-category_id}}',
            '{{%product_properties}}',
            'category_id',
            '{{%categories}}',
            'id',
            'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-product_properties-product_id}}',
            '{{%product_properties}}',
            'product_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-product_properties-product_id}}',
            '{{%product_properties}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        // creates index for column `property_id`
        $this->createIndex(
            '{{%idx-product_properties-property_id}}',
            '{{%product_properties}}',
            'property_id'
        );

        // add foreign key for table `{{%property}}`
        $this->addForeignKey(
            '{{%fk-product_properties-property_id}}',
            '{{%product_properties}}',
            'property_id',
            '{{%properties}}',
            'id',
            'CASCADE'
        );

        // creates index for column `value_id`
        $this->createIndex(
            '{{%idx-product_properties-value_id}}',
            '{{%product_properties}}',
            'value_id'
        );

        // add foreign key for table `{{%property_value}}`
        $this->addForeignKey(
            '{{%fk-product_properties-value_id}}',
            '{{%product_properties}}',
            'value_id',
            '{{%property_values}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-product_properties-category_id}}',
            '{{%product_properties}}',
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-product_properties-category_id}}',
            '{{%product_properties}}',
        );

        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-product_properties-product_id}}',
            '{{%product_properties}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-product_properties-product_id}}',
            '{{%product_properties}}'
        );

        // drops foreign key for table `{{%property}}`
        $this->dropForeignKey(
            '{{%fk-product_properties-property_id}}',
            '{{%product_properties}}'
        );

        // drops index for column `property_id`
        $this->dropIndex(
            '{{%idx-product_properties-property_id}}',
            '{{%product_properties}}'
        );

        // drops foreign key for table `{{%property_value}}`
        $this->dropForeignKey(
            '{{%fk-product_properties-value_id}}',
            '{{%product_properties}}'
        );

        // drops index for column `value_id`
        $this->dropIndex(
            '{{%idx-product_properties-value_id}}',
            '{{%product_properties}}'
        );

        $this->dropTable('{{%product_properties}}');
    }
}
