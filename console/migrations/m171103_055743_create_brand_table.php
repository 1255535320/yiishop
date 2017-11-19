<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m171103_055743_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50),
            'intro'=>$this->text(),
            'logo'=>$this->string(),
            'sort'=>$this->integer(11),
            'status'=>$this->integer(2),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
