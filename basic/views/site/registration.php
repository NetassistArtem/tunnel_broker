<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\debugger\Debugger;

$this->title = 'Registration';
?>

<div class=" col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10 site-request-password-reset">
    <div class="panel panel-default custom-panel-style" >
        <div class="panel-heading" >
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body" >
            <p>Please fill out your email. A link to next step of registration  will be sent there. And change ip address if it necessary.</p>
            <div class="row">


                <?php $form = ActiveForm::begin([
                    'id' => 'request-password-reset-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-4 col-md-4 col-lg-4\">{input}</div>\n<div class=\"col-sm-4 col-md-4 col-lg-4\">{error}</div>",
                        'labelOptions' => ['class' => 'col-sm-4 col-md-4 col-lg-4 control-label'],
                    ],
                ]); ?>
                <?php
                $model_registration_request->ip =  isset($model_registration_request->ip) ? $model_registration_request->ip : $ip;
                echo  $form->field($model_registration_request, 'login')->input('email', ['autofocus' => true])->label('E-mail (will be your login)') ?>
                <?= $form->field($model_registration_request, 'ip')->textInput()->label('IPv4 address of your side of the tunnel') ?>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-6">
                        <?= Html::submitButton('Register', ['class' => 'btn btn-primary btn-block  btn-custom']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>


</div>