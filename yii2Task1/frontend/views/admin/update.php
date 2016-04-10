<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Account: ' . ' ' . $model->name . ' ' . $model->surname;
$this->params['breadcrumbs'][] = ['label' => $model->name . ' ' . $model->surname,
    'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit Account';
$list = array('Admin', 'Root');
?>
<div class="Admin-edition">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
    <?= $form->field($model, 'name')->label('Name')->textInput() ?>

    <?= $form->field($model, 'surname')->label('Username')->textInput() ?>

    <?= $form->field($model, 'email')->label('Email')->textInput() ?>

    <?php
    if ($access) {
        echo $form->field($model, 'company_id')->label('Company ID')->textInput();
        echo $form->field($model, 'role')->label('Uprawnienia')->dropDownList([20 => 'Root', 10 => 'Admin']);
    }
    ?>

    <div class="form-group">
        <?=
        Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'update-button'], ['data' => [
                'confirm' => 'Na pewno chcesz zmienić dane użytkownika ?',
                'method' => 'post',
            ]
        ])
        ?>
    </div>
    <?php ActiveForm::end() ?>

</div>