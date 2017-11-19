<?php

use yii\db\Migration;

class m171114_033135_alter_address_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('address','province','string');
        $this->addColumn('address','city','string');
        $this->addColumn('address','area','string');
    }

    public function safeDown()
    {
        echo "m171114_033135_alter_address_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171114_033135_alter_address_table cannot be reverted.\n";

        return false;
    }
    */
}
