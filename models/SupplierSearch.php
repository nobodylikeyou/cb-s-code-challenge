<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Supplier;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * SupplierSearch represents the model behind the search form of `app\models\Supplier`.
 */
class SupplierSearch extends Supplier
{
    public static function tableName()
    {
        return 'supplier';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'code', 't_status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Supplier::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //分页大小
                'pageSize' => 10,
                //设置地址栏当前页数参数名
                'pageParam' => 'p',
                //设置地址栏分页大小参数名
                'pageSizeParam' => 'pageSize',
            ],
            //设置排序
            'sort' => [
                //默认排序方式
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                //参与排序的字段
                'attributes' => [
                    'id', 'name'
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 't_status', $this->t_status]);

        return $dataProvider;
    }
}
