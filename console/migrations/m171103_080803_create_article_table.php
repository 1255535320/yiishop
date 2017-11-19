<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m171103_080803_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50),
            'intro'=>$this->text(),
            'article_category_id'=>$this->integer(),
            'sort'=>$this->integer(11),
            'status'=>$this->integer(2),
            'create_at'=>$this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
