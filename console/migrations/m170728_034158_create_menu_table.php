<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_034158_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30),
            'route' => $this->string(50),
            'parent_id' =>$this->integer(),
            'sort' => $this->integer(),
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
