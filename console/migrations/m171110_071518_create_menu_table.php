<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171110_071518_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),
            'lft'=> $this->integer()->notNull(),
            'rgt'=> $this->integer()->notNull(),
            'depth'=> $this->integer()->notNull(),
            'name'=> $this->string()->notNull(),
            'top_menu'=>$this->string(),
            'address'=>$this->string(),
            'sort'=>$this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
