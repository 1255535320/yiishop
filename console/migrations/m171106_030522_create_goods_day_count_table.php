<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day_count`.
 */
class m171106_030522_create_goods_day_count_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_count', [
            'id' => $this->date()->comment('日期'),
            'count'=>$this->integer()->comment('每日商品数'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_count');
    }
}
