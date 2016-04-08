<?php

use yii\helpers\Html;
use yii\helpers\BaseHtml;
use yii\grid\GridView;

$this->title = 'Registered Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-registered">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'name',
                'surname',
                'email',
                [
                    'attribute' => 'company_id',
                    'value' => 'company.name',
                ],
                'slug',
                ['class' => 'yii\grid\ActionColumn',
                'visible' => true],
                
            ],
        ]);
        ?>

    </div>

</div>
