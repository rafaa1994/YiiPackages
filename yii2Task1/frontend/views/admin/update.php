<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Account: ' . ' ' . $model->name . ' ' . $model->surname;
$this->params['breadcrumbs'][] = ['label' => $model->name . ' ' . $model->surname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit Account';
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

        echo $form->field($model, 'role')->label('Uprawnienia')->textInput();
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
    </div>
    <?php ActiveForm::end() ?>

</div>