<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170718_070612_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id'=> $this->primaryKey(),
            'name'=> $this->string(20),
            'intro'=> $this->text(),
            'logo'=> $this->string(255),
            'sort'=> $this->integer(),
            'status'=> $this->integer(),

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
