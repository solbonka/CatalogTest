<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_values}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%property}}`
 */
class m230902_074722_create_property_values_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_values}}', [
            'id' => $this->primaryKey(),
            'value' => $this->string()->notNull(),
            'property_id' => $this->integer(),
        ]);

        // creates index for column `property_id`
        $this->createIndex(
            '{{%idx-property_values-property_id}}',
            '{{%property_values}}',
            'property_id'
        );

        // add foreign key for table `{{%property}}`
        $this->addForeignKey(
            '{{%fk-property_values-property_id}}',
            '{{%property_values}}',
            'property_id',
            '{{%properties}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%property}}`
        $this->dropForeignKey(
            '{{%fk-property_values-property_id}}',
            '{{%property_values}}'
        );

        // drops index for column `property_id`
        $this->dropIndex(
            '{{%idx-property_values-property_id}}',
            '{{%property_values}}'
        );

        $this->dropTable('{{%property_values}}');
    }
}
