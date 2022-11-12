<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\data\Pagination;

class Supplier extends BaseModel
{

    //设置规则
    //注意，如果没有给字段设置规则，GridView的筛选项是不会出现的
    public function rules()
    {
        return [
            [['id'], 'integer'],
            ['name', 'string'],
            ['code', 'string'],
            ['t_status', 'string'],
        ];
    }

    //查询
    public function search($params)
    {
        //首先我们先获取一个ActiveQuery
        $query = self::find();
        //然后创建一个ActiveDataProvider对象
        $provider = new ActiveDataProvider([
            //为ActiveDataProvider对象提供一个查询对象
            'query' => $query,
            //设置分页参数
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

        //如果验证没通过，直接返回
        if (!($this->load($params) && $this->validate())) {
            return $provider;
        }

        //增加过滤条件
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['t_status' => $this->t_status]);

        return $provider;
    }
    /**
     * @param $param page pageSize name code
     * @return array
     */
    public function getList($param = []) {
        $query = Supplier::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $suppliers = $query->orderBy('id desc')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->where($param)
                    ->all();

        return [$pagination, $suppliers];
    }

}