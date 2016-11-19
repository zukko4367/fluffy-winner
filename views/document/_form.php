<?php

use app\components\DropZoneWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js', ['depends' => ['yii\web\JqueryAsset'],]);
$this->registerJsFile('js/main.js', ['depends' => ['yii\web\JqueryAsset'],]);
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data'
    ]]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>


    <div class="preview-attachments-container">
        <? if(!empty($model->attachments)):?>

        <? else: ?>
            No document attachments!
        <? endif; ?>

    </div>
    <div class="add-attachments-container">
        <?= DropZoneWidget::widget([
            'options' => [
                'maxFilesize' => '2',
                'url' => '/document/upload',
                'addRemoveLinks' => true,
            ],
            'events' => [
                'removedfile' => "function(file){alert(file.name + ' is removed')}",
                'success' => "function(file, response){
                    var id = response.id;
                    //var fields = ['id', 'weight', 'title'];
                        $('<input>', {
                            type: 'hidden',
                            name: 'attachment['+id+'][id]',
                            value: id
                        }).appendTo($(file.previewElement).find('.dz-id'));

                        $('<input>', {
                            type: 'hidden',
                            name: 'attachment['+id+'][weight]',
                            value: 0
                        }).appendTo($(file.previewElement).find('.dz-weight'));

                        $('<input>', {
                            type: 'textfield',
                            name: 'attachment['+id+'][title]',
                            value: ''
                        }).appendTo($(file.previewElement).find('.dz-title'));

                    $('#dropzone-preview .dz-image-preview').each(function(k,v){
                        $(this).find('.dz-weight input').val(k);
                    });
                }",
                'addedfile' => "function(file, response){
                    if(file.id)
                    {
                        var id = file.id;

                        //var fields = ['id', 'weight', 'title'];
                            $('<input>', {
                                type: 'hidden',
                                name: 'attachment['+id+'][id]',
                                value: id
                            }).appendTo($(file.previewElement).find('.dz-id'));

                            $('<input>', {
                                type: 'hidden',
                                name: 'attachment['+id+'][weight]',
                                value: 0
                            }).appendTo($(file.previewElement).find('.dz-weight'));

                            $('<input>', {
                                type: 'textfield',
                                name: 'attachment['+id+'][title]',
                                value: ''
                            }).appendTo($(file.previewElement).find('.dz-title'));

                        $('#dropzone-preview .dz-image-preview').each(function(k,v){
                            $(this).find('.dz-weight input').val(k);
                        });
                    }
                }",
            ],
            'previews' => $model->attachments,
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

