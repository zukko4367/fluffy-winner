<?php

namespace app\controllers;

use app\models\Attachment;
use Yii;
use app\models\Document;
use app\models\DocumentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Document();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $p = Yii::$app->request->post();
            if(isset($p['attachment']))
            {
                foreach($p['attachment'] as $attachmentFields)
                {
                    $attachment = Attachment::findOne($attachmentFields['id']);
                    $attachment->setAttribute('weight', $attachmentFields['weight']);
                    $attachment->setAttribute('entityID', $model->id);
                    $attachment->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $documentModel = $this->findModel($id);
        if ($documentModel->load(Yii::$app->request->post()) && $documentModel->save()) {
            $p = Yii::$app->request->post();
            if(isset($p['attachment']))
            {
                foreach($p['attachment'] as $attachmentFields)
                {
                    $attachment = Attachment::findOne($attachmentFields['id']);
                    $attachment->setAttribute('weight', $attachmentFields['weight']);
                    $attachment->setAttribute('entityID', $id);
                    $attachment->save();
                }
            }
            return $this->redirect(['view', 'id' => $documentModel->id]);
        } else {
            return $this->render('update', [
                'model' => $documentModel,
            ]);
        }
    }

    public function actionUpload()
    {

        $uploadDir = '/upload';
        //$file = UploadedFile::getInstance($document, 'file');
        $file = UploadedFile::getInstanceByName('file');
        $path =  $uploadDir . '/' . $file->name;
        if(isset($_FILES['file']))
        {
            if($file->saveAs(Yii::getAlias('@webroot').$path))
            {
                $attachment = new Attachment();
                $attachment->load(['Attachment' => [
                    'entityID' => 0,
                    'filename' => $file->name,
                    'filesize' => $file->size,
                    'path' => $path,
                    'weight' => 0,
                ]]);
                if($attachment->save())
                {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $attachment;
                }
            }
        }
        return false;
    }

    public function actionRemove()
    {
        $g = Yii::$app->request->get();
        $attachment = Attachment::findOne($g['id']);
        $path = $attachment->getAttribute('path');
        unlink(Yii::getAlias('@webroot').$path);
        $attachment->delete();
        return false;
    }


    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $documents = $this->findModel($id);

        foreach($documents->attachments as $attachment)
        {
            $path = $attachment->getAttribute('path');
            unlink(Yii::getAlias('@webroot').$path);
            $attachment->delete();
        }
        $documents->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
