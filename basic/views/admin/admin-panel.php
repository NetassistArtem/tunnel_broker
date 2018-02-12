<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\debugger\Debugger;
use yii\widgets\Pjax;

$this->title = 'Admin panel';

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">

            <div class=" panel panel-default custom-panel-style">
                <div class="panel-heading">
                    <h4>Search user by  <b class="color-b" >e-mail</b></h4>
                </div>
                <div class="panel-body">
                    <?php Pjax::begin(['id' => 'search_user']); ?>

                    <?php $form_search_user = ActiveForm::begin([
                        'id' => 'modelFindUser1',
                        'options' => ['data-pjax' => false],
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-8 col-md-8 col-sm-8\">{input}</div>\n<div class=\"col-lg-2 col-md-2 col-sm-2\">{error}</div>",
                        ],

                    ]); ?>


                    <?= $form_search_user->field($modelFindUser, 'email')->input('text', ['list' => 'emails_list', 'autocomplete' => "off"])->label(false) ?>

                    <datalist id="emails_list">
                        <?php foreach ($searchable_array['emails'] as $k => $v): ?>
                            <option value='<?= $v ?>'/>
                        <?php endforeach; ?>

                    </datalist>


                    <div class="form-group">
                        <div class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10">
                            <?= Html::submitButton("View and edit", ['class' => 'btn btn-primary btn-block', 'name' => 'action', 'value' => 1]) ?>
                        </div>
                        <div
                            class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10 margin-top-3">
                            <?= Html::submitButton("Delete", ['class' => 'btn btn-danger btn-block', 'name' => 'action', 'value' => 2]) ?>
                        </div>
                        <div
                            class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10 margin-top-3">
                            <?= Html::submitButton("Actions history", ['class' => 'btn btn-primary btn-block', 'name' => 'action', 'value' => 3]) ?>
                        </div>
                        <div
                            class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10 margin-top-3">
                            <?= Html::submitButton("Add admin rules", ['class' => 'btn btn-danger btn-block', 'name' => 'action', 'value' => 4,'onclick'=> "return confirm('Are you sure you want to add admin rules ?')"] ) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>




                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>


        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class=" panel panel-default custom-panel-style">
                <div class="panel-heading">
                    <h4>Search user by <b class="color-b" >ipV4</b></h4>
                </div>
                <div class="panel-body">
                    <?php Pjax::begin(['id' => 'search_user']); ?>





                    <?php $form_search_user = ActiveForm::begin([
                        'id' => 'modelFindUser2',
                        'options' => ['data-pjax' => false],
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-8 col-md-8 col-sm-8\">{input}</div>\n<div class=\"col-lg-2 col-md-2 col-sm-2\">{error}</div>",

                        ],

                    ]); ?>




                    <?= $form_search_user->field($modelFindUser, 'ipv4')->input('text', ['list' => 'ipv4_list', 'autocomplete' => "off"])->label(false) ?>

                    <datalist id="ipv4_list">
                        <?php foreach ($searchable_array['ipv4'] as $k => $v): ?>
                            <option value='<?= $v ?>'/>
                        <?php endforeach; ?>

                    </datalist>


                    <div class="form-group">
                        <div class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10">
                            <?= Html::submitButton("View and edit", ['class' => 'btn btn-primary btn-block', 'name' => 'action', 'value' => 1]) ?>
                        </div>
                        <div
                            class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10 margin-top-3">
                            <?= Html::submitButton("Delete", ['class' => 'btn btn-danger btn-block', 'name' => 'action', 'value' => 2]) ?>
                        </div>
                        <div
                            class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10 margin-top-3">
                            <?= Html::submitButton("Actions history", ['class' => 'btn btn-primary btn-block', 'name' => 'action', 'value' => 3]) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>




                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>


        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class=" panel panel-default custom-panel-style">
                <div class="panel-heading">
                    <h4>Search user by <b class="color-b" >ipV6</b></h4>
                </div>
                <div class="panel-body">
                    <?php Pjax::begin(['id' => 'search_user']); ?>





                    <?php $form_search_user = ActiveForm::begin([
                        'id' => 'modelFindUser',
                        'options' => ['data-pjax' => false],
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\" col-lg-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-8 col-md-8 col-sm-8\">{input}</div>\n<div class=\"col-lg-2 col-md-2 col-sm-2\">{error}</div>",

                        ],

                    ]); ?>


                    <?= $form_search_user->field($modelFindUser, 'ipv6')->input('text', ['list' => 'ipv6_list', 'autocomplete' => "off"])->label(false) ?>

                    <datalist id="ipv6_list">
                        <?php foreach ($searchable_array['ipv6'] as $k => $v): ?>
                            <option value='<?= $v ?>'/>
                        <?php endforeach; ?>

                    </datalist>
                    <div class="form-group">
                        <div class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10">
                            <?= Html::submitButton("View and edit", ['class' => 'btn btn-primary btn-block', 'name' => 'action', 'value' => 1]) ?>
                        </div>
                        <div
                            class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10 margin-top-3">
                            <?= Html::submitButton("Delete", ['class' => 'btn btn-danger btn-block', 'name' => 'action', 'value' => 2]) ?>
                        </div>
                        <div
                            class="col-lg-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sm-10 margin-top-3">
                            <?= Html::submitButton("Actions history", ['class' => 'btn btn-primary btn-block', 'name' => 'action', 'value' => 3]) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>




                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>


    </div>
</div>