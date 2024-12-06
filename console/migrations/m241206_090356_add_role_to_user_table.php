<?php

use yii\db\Migration;

/**
 * Class m241206_090356_add_role_to_user_table
 */
class m241206_090356_add_role_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'role', $this->string()->defaultValue('client')); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'role');
    }

}
