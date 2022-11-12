<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary" id="export">导出</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php echo GridView::widget([
                //设置GridView的ID
                'id' => 'mySupplierGridView',
                //设置数据提供器
                'dataProvider' => $provider,
                //设置筛选模型
                'filterModel' => $model,
//                'showFooter' => true, //设置显示最下面的footer
                'columns' => [
                    //复选框列
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'id',
                    ],
                    [
                        //设置字段显示标题
                        'label' => 'ID',
                        //字段名
                        'attribute' => 'id',
                        //格式化
                        'format' => 'raw',
                        //设置单元格样式
                        'headerOptions' => [
                            'style' => 'width:120px;',
                        ],
                        'footerOptions' => ['class'=>'hide']
                    ],
                    [
                        'label' => '供应商名称',
                        'attribute' => 'name',
                        'format' => 'raw',
                        'footerOptions' => ['class'=>'hide']
                    ],
                    [
                        'label' => '供应商编码',
                        'attribute' => 'code',
                        'format' => 'raw',
                        'footerOptions' => ['class'=>'hide']
                    ],
                    [
                        'label' => '状态',
                        //设置筛选选项
                        'filter' => ['ok' => '通过', 'hold' => '待审核'],
                        'attribute' => 't_status',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return ($data->t_status == 'ok') ? '通过' : '待审核';
                        },
                        'footerOptions' => ['class'=>'hide']
                    ],

//                    [
//                        'header' => '操作',
//                        'class' => 'yii\grid\ActionColumn',
//                        //设置显示模板
//                        'template' => '{upd} {del}',
//                        //下面的按钮设置，与上面的模板设置相关联
//                        'buttons' => [
//                            'upd' => function ($url, $model, $key) {
//                                return '<a href="' . Url::toRoute(['test/upd', 'id' => $key]) . '" rel="external nofollow" class="btn btn-warning">修改</a>';
//                            },
//                            'del' => function ($url, $model, $key) {
//                                return '<a href="' . Url::toRoute(['test/del', 'id' => $key]) . '" rel="external nofollow" class="btn btn-danger">删除</a>';
//                            },
//                        ],
//                    ],
                ],
                'pager' => [
                    'linkContainerOptions' => [
                        'class' => 'page-item'
                    ],
                    'linkOptions' => [
                        'class' => 'page-link'
                    ],
                    'disabledListItemSubTagOptions' => [
                        'tag' => 'a',
                        'href' => 'javascript:;',
                        'tabindex' => '-1',
                        'class' => 'page-link'
                    ]
                ],
                'layout' => "{items}\n{pager}",
            ]); ?>
        </div>
        <div class="alert alert-info" role="alert"> All conversations on this pageave been selected </div>
    </div>
</div>
<?php echo Html::jsFile('@web/js/jquery-3.3.1.min.js'); ?>
<script type="text/javascript">
    $("#export").on("click", function () {
        var keys = $("#mySupplierGridView").yiiGridView('getSelectedRows');
        alert(keys);
    });
</script>