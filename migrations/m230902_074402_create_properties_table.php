<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%properties}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%category}}`
 */
class m230902_074402_create_properties_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%properties}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'category_id' => $this->integer(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-properties-category_id}}',
            '{{%properties}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-properties-category_id}}',
            '{{%properties}}',
            'category_id',
            '{{%categories}}',
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
            '{{%fk-properties-category_id}}',
            '{{%properties}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-properties-category_id}}',
            '{{%properties}}'
        );

        $this->dropTable('{{%properties}}');
    }
}
