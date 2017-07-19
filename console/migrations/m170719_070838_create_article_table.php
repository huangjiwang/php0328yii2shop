<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170719_070838_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id'=> $this->primaryKey(),
            'name'=> $this->string(20),
            'intro'=> $this->text(),
            'article_category_id'=>$this->integer(),
            'sort'=> $this->integer(),
            'status'=> $this->integer(),
            'create_time'=> $this->integer(),
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
