<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-sm-6 col-md-6 col-lg-6 site-reset-password">
    <div class="panel panel-default custom-panel-style"  >
        <div class="panel-heading" >
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body" >
            <p>Please choose your new password:</p>
            <div class="row">
                <div class="col-lg-5">

                    <?php $form = ActiveForm::begin([
                        'id' => 'reset-password-form',
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-6 col-md-6 col-lg-6\">{input}</div>\n<div class=\"col-sm-3 col-md-3 col-lg-3\">{error}</div>",
                        'labelOptions' => ['class' => 'col-sm-3 col-md-3 col-lg-3 control-label'],
                    ],
                    ]); ?>
                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-block btn-custom']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>

    </div>




</div>