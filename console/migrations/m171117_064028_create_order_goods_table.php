<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m171117_064028_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->notNull()->comment('订单号'),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'goods_name'=>$this->string()->notNull()->comment('商品名称'),
            'logo'=>$this->string()->notNull()->comment('LOGO'),
            'price'=>$this->decimal()->notNull()->comment('价格'),
            'amount'=>$this->integer()->notNull()->comment('数量'),
            'total'=>$this->decimal()->notNull()->comment('小计'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
