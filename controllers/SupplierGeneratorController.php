<?php

namespace app\controllers;

use app\models\Supplier;
use app\models\SupplierSearch;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierGeneratorController implements the CRUD actions for Supplier model.
 */
class SupplierGeneratorController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Supplier model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Supplier();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Supplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExport() {
        if (\Yii::$app->request->isAjax) {
            ini_set('memory_limit', '2048M');
            set_time_limit(0);

            $ids = \Yii::$app->request->post('ids');
            if (empty($ids)) {
                echo json_encode(['code'=>600, 'message'=>'请选择要导出的数据行']);exit;
            }
            $exportFields = \Yii::$app->request->post('export_field');

            if (empty($exportFields)) {
                echo json_encode(['code'=>601, 'message'=>'请选择要导出的字段']);exit;
            }

            $exportFields[] = 'id'; // ID列为强制的
            $exportDatas = Supplier::find()->select($exportFields)->where(['in', 'id', $ids])->all();

            $title = implode(',', $exportFields)."\n";
            $fileName = '供应商列表'.date('YmdHis').'.csv';

            $wrstr = '';

            foreach ($exportDatas as $key => $value) {
                $tempwrstr = '';
                foreach ($exportFields as $k => $v) {
                    if ($k === count($exportFields)-1 ) {
                        $tempwrstr .= $value[$v];
                    } else {
                        $tempwrstr .= $value[$v].',';
                    }
                }
                $wrstr .= $tempwrstr;
                $wrstr .= "\n";
            }
            $this->Csvexport( $fileName, $title, $wrstr);
        }
    }

    private function Csvexport($file = '', $title = '', $data) {
        header("Content-Disposition:attachment;filename=".$file);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

         ob_start();

        //表头
        $wrstr = $title;

        //内容
        $wrstr .= $data;

        $wrstr = iconv("utf-8", "GBK//ignore", $wrstr);

        file_put_contents(getcwd().'/download/'.$file, $wrstr);

        echo json_encode(['code'=>0, 'message'=>'success', 'data'=>['file_name'=>$file]]);exit;

    }

    /**
     * @return void
     */
    public  function actionDownload() {
        $fileName = \Yii::$app->request->get('file_name');

        // 获取文件的大小
        $size = filesize(getcwd().'/download/'.$fileName,);

        header("Content-type:application/octet-stream");
        // 设置下载的文件名称
        header("Content-Disposition:attachment;filename={$fileName}");
        header("Accept-ranges:bytes");
        header("Accept-length:" . $size);
        echo file_get_contents(getcwd().'/download/'.$fileName);
        exit;
    }
    /**
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Supplier::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
