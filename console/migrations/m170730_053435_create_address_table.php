<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170730_053435_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'Consignee'=>$this->string(20),
            'Location'=>$this->string(100),
            'detailed'=>$this->string(100),
            'tel'=>$this->integer(11),
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
