<?php

namespace app\controllers;

use app\models\Supplier;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class SupplierController extends Controller
{
    /**
     * @param $param name,code
     * @return string
     */
    public function actionIndex() {
        $supplier = new Supplier();
        //调用模型search方法，把get参数传进去
        $provider = $supplier->search(\Yii::$app->request->get());

        return $this->render('index', [
            'model' => $supplier,
            'provider' => $provider,
        ]);
    }
    public function export() {

    }
}