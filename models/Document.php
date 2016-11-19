<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property string $id
 * @property string $title
 * @property string $text
 */
class Document extends \yii\db\ActiveRecord
{
    public $file;
    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(),['entityID' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }
}
