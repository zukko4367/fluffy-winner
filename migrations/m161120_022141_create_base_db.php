<?php

use yii\db\Migration;

class m161120_022141_create_base_db extends Migration
{
    public function up()
    {

        $this->createTable('document', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'text' => $this->text(),
        ]);

        $this->createTable('attachment', [
            'id' => $this->primaryKey(),
            'filename' => $this->string(255),
            'path' => $this->text(),
            'filesize' => $this->integer(11),
            'entityID' => $this->integer(11),
            'weight' => $this->integer(11)->notNull(),
        ]);

        //$this->addForeignKey('fk_document_id_entityID', 'attachment', 'entityID', 'document', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('attachment');
        $this->dropTable('document');
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
