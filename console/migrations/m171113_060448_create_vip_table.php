<?php

use yii\db\Migration;

/**
 * Handles the creation of table `vip`.
 */
class m171113_060448_create_vip_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('vip', [
            'id' => $this->primaryKey(),
            'auth_key'=>$this->string(),
            'password_hash'=>$this->string()->notNull()->comment('密码'),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'tel'=>$this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->comment('状态'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'last_login_time' => $this->integer()->comment('最后登陆时间'),
            'last_login_ip'=>$this->string()->comment('最后登陆ip'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('vip');
    }
}
