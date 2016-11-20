<?php

use yii\db\Migration;

/**
 * Handles adding mimetype to table `attachment`.
 */
class m161120_032229_add_mimetype_column_to_attachment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('attachment', 'mimetype', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('attachment', 'mimetype');
    }
}
