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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%properties}}');
    }
}
