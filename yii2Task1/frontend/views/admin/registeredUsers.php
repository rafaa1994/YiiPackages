<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\BaseHtml;
use yii\widgets\LinkPager;

$this->title = 'Registered Users';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="admin-registered">
   <h1><?= Html::encode($this->title) ?></h1>
      
   <div class="row">
        <div class="radio">
            <h2>Sortuj za pomocą</h2>
            <?php $form = ActiveForm::begin(['id' => 'filter-form']); ?>
            
                <?= $form->field($filter, 'radio[]')->inline()->radioList(['radio[0]' => 'Name','radio[1]'=> 'Surname','radio[2]' => 'Company', 'radio[3]' => 'Email'])->label(false) ?>

                <?= Html::submitButton('Show users', ['class' => 'btn btn-primary', 'name' => 'show-button']) ?>
           
            
            <?php ActiveForm::end(); ?>
        </div>
       <div class="row">
           <ul>
               <?php foreach ($registeredUsers as $regUser): ?>
               <li>
                   <?= Html::encode("{$regUser->username} {$regUser->surname} {$regUser->slug} {$regUser->email} {$regUser->companies->name}") ?>
               </li>
               
               <?php endforeach; ?>
           </ul>
           <?=LinkPager::widget(['pagination' => $pagination])?>
           
         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'surname',
            'email',
            'company_id',
            'slug',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
           
       </div>
    </div>
   
    
</div>
