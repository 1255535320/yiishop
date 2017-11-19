<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m171114_011530_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment('收货人'),
            'member_id'=>$this->integer()->notNull()->comment('用户ID'),
            'phone'=>$this->string()->notNull()->comment('电话'),
            'address'=>$this->string()->notNull()->comment('收货地址'),
            'create_at'=>$this->integer()->comment('创建订单时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
