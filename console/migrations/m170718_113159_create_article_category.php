<?php

use yii\db\Migration;

class m170718_113159_create_article_category extends Migration
{
    public function up()
    {
        $this->createTable('article_category', [
            'id'=> $this->primaryKey(),
            'name'=> $this->string(20),
            'intro'=> $this->text(),
            'sort'=> $this->integer(),
            'status'=> $this->integer(),

        ]);
    }

    public function down()
    {
        echo "m170718_113159_create_article_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
