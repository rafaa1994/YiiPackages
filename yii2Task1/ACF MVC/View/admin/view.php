<?php

use yii\widgets\DetailView;

$this->title = 'Edit Account: ' . ' ' . $model->name . ' ' . $model->surname;
$this->params['breadcrumbs'][] = ['label' => $model->name . ' ' . $model->surname,
    'url' => ['view', 'id' => $model->id]];

?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'surname',
            'email',
        ],
    ]) ?>