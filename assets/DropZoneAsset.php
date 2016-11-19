<?php
/**
 * Created by PhpStorm.
 * User: Aldwin
 * Date: 19.11.2016
 * Time: 10:45
 */

namespace app\assets;


use yii\web\AssetBundle;

class DropZoneAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        "js/dropzone.js"
    ];

    public $css = [
        "css/dropzone.css"
    ];

    /**
     * @var array
     */
    public $publishOptions = [
        'forceCopy' => true
    ];

}