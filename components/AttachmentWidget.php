<?php
/**
 * Created by PhpStorm.
 * User: Aldwin
 * Date: 19.11.2016
 * Time: 13:50
 */

namespace app\components;


class AttachmentWidget extends Widget
{
    public function init()
    {
        parent::init();

    }

    public function run()
    {
        return Html::tag('div', $this->renderDropzone(), ['id' => $this->dropzoneContainer, 'class' => 'dropzone']);
    }
}