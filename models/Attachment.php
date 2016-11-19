<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachment".
 *
 * @property string $id
 * @property string $filename
 * @property string $path
 * @property integer $filesize
 * @property string $entityID
 */
class Attachment extends \yii\db\ActiveRecord
{
    public $file;

    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'entityID']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'path', 'filesize', 'entityID'], 'required'],
            [['path'], 'string'],
            [['filesize', 'entityID'], 'integer'],
            [['filename'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'path' => 'Path',
            'filesize' => 'Filesize',
            'entityID' => 'Entity ID',
        ];
    }
}
