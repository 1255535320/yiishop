<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m171117_060108_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->string()->notNull(),
            'name'=>$this->string()->notNull(),
            'province'=>$this->string()->notNull(),
            'city'=>$this->string()->notNull(),
            'area'=>$this->string()->notNull(),
            'address'=>$this->string()->notNull(),
            'tel'=>$this->char(11)->notNull(),
            'delivery_name'=>$this->string()->notNull()->comment('配送方式'),
            'delivery_price'=>$this->float()->notNull()->comment('运费'),
            'payment_name'=>$this->string()->notNull()->comment('在线支付'),
            'total'=>$this->decimal()->notNull()->comment('订单金额'),
            'status'=>$this->integer()->notNull()->comment('订单状态（0已取消1待付款2待发货3待收货4完成）'),
            'trade_no'=>$this->string()->comment('第三方支付交易号'),
            'create_time'=>$this->integer()->comment('第三方支付交易号'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
