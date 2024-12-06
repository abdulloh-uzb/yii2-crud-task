<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post}}`.
 */
class m241206_012546_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'body' => $this->text(),
            'owner_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey('FK_post_user_owner_id', '{{%post}}', 'owner_id', '{{%user}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_post_user_owner_id', '{{$post}}');
        $this->dropTable('{{%post}}');
    }
}
