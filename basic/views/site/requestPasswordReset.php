<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class=" col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-sm-6 col-md-6 col-lg-6 site-request-password-reset">
    <div class="panel panel-default custom-panel-style" >
        <div class="panel-heading" >
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body" >
            <p>Please fill out your email. A link to reset password will be sent there.</p>
            <div class="row">


                    <?php $form = ActiveForm::begin([
                        'id' => 'request-password-reset-form',
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-sm-6 col-md-6 col-lg-6\">{input}</div>\n<div class=\"col-sm-3 col-md-3 col-lg-3\">{error}</div>",
                            'labelOptions' => ['class' => 'col-sm-3 col-md-3 col-lg-3 control-label'],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-6">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-block  btn-custom']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>


</div>