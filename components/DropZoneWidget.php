<?php

namespace app\components;

use app\models\Attachment;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use app\assets\DropZoneAsset;
use yii\helpers\Json;
use yii\web\Request;

class DropZoneWidget extends Widget
{
    public $options = [];
    public $events = [];
    public $previews = [];

    public $id = 'dropzone-custom-widget';
    public $uploadUrl = '/site/upload';
    public $dropzoneContainer = 'dropzone-custom-widget';
    public $previewsContainer = 'dropzone-preview';
    public $autoDiscover = false;

    public function init()
    {
        parent::init();


        if (!isset($this->options['url'])) {
            $this->options['url'] = $this->uploadUrl;
        }

        if (!isset($this->options['previewsContainer'])) {
            $this->options['previewsContainer'] = '#' . $this->previewsContainer;
        }

        if (!isset($this->options['clickable'])) {
            $this->options['clickable'] = true;
        }

        if (!isset($this->options['previewTemplate'])) {
            $this->options['previewTemplate'] = $this->dropzonePreviewTemplate();
        }

        $this->autoDiscover = $this->autoDiscover === false ? 'false' : 'true';

        if (Yii::$app->getRequest()->enableCsrfValidation) {
            $this->options['headers'][Request::CSRF_HEADER] = Yii::$app->getRequest()->getCsrfToken();
            $this->options['params'][Yii::$app->getRequest()->csrfParam] = Yii::$app->getRequest()->getCsrfToken();
        }

        Yii::setAlias('@dropzone', dirname(__FILE__));
        $this->registerAssets();
    }

    public function run()
    {
        return Html::tag('div', $this->renderDropzone(), ['id' => $this->dropzoneContainer, 'class' => 'dropzone']);
    }

    private function renderDropzone()
    {
        $data = Html::tag('div', '', ['id' => $this->previewsContainer, 'class' => 'dropzone-previews']);

        return $data;
    }

    public function registerAssets()
    {
        $view = $this->getView();

        $js = 'Dropzone.autoDiscover = ' . $this->autoDiscover . ';';
        $js .= 'var myDropzone = new Dropzone("div#' . $this->dropzoneContainer . '", ' . Json::encode($this->options) . ');';

        if (!empty($this->events)) {
            foreach ($this->events as $event => $handler) {
                $js .= "myDropzone.on('$event', $handler);";
            }
        }

        if(!empty($this->previews))
        {
            $file = [];
            foreach($this->previews as $attachment)
            {
                $file[$attachment->getAttribute('weight')] = [
                  'id' => $attachment->getAttribute('id'),
                  'name' => $attachment->getAttribute('filename'),
                  'size' => $attachment->getAttribute('filesize'),
                  'weight' => $attachment->getAttribute('weight'),
                  'thumbnail' => $attachment->getAttribute('path'),
                ];
            }
            ksort($file);
            foreach($file as $fileInfo)
            {
                $js.= 'myDropzone.emit("addedfile", '.Json::encode($fileInfo).');';
                $js.= 'myDropzone.emit("thumbnail", '.Json::encode($fileInfo).', "/'.$fileInfo['path'].'");';
            }
        }
        $view->registerJs($js);
        DropZoneAsset::register($view);
    }

    private function dropzonePreviewTemplate()
    {
        return '
	    <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete">
			<div class="dz-image">
				<img data-dz-thumbnail="" >
			</div>
			<div class="dz-details">
			<div class="dz-size"><span data-dz-size=""></span></div>
			<div class="dz-filename"><span data-dz-name=""></span></div>
			<div class="dz-weight"></div>
			<div class="dz-title"></div>
			<div class="dz-id"></div>
			</div>
			<div class="dz-progress">
			</div>
			<div class="dz-error-message"></div>
			<div class="dz-success-mark">
			</div>
		</div>';
    }
}
