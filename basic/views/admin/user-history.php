<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\debugger\Debugger;
use yii\widgets\LinkPager;

$this->title = $selected_all ? 'Actions history, user: All users' : 'Actions history, user: '.$user_data->email;
?>
    <div class="  col-lg-offset-1 col-sm-12 col-md-12 col-lg-10 site-request-password-reset">
        <div class="panel panel-default custom-panel-style" >
            <div class="panel-heading" >
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body" >
                <div class="row">


                    <?php $model_history_form = ActiveForm::begin([
                        'id' => 'history-form',
                       // 'layout' => 'horizontal',
                       // 'fieldConfig' => [
                         //   'template' => "{label}\n<div class=\"col-sm-4 col-md-4 col-lg-4\">{input}</div>\n<div class=\"col-sm-4 col-md-4 col-lg-4\">{error}</div>",
                           // 'labelOptions' => ['class' => 'col-sm-4 col-md-4 col-lg-4 control-label'],
                     //   ],
                    ]); ?>
                    <div class=" col-lg-offset-1 col-md-offset-1  col-lg-3 col-md-3 col-sm-3 button-position">
                        <?php $modelHistory->action = $action;
                        echo $model_history_form->field($modelHistory, 'action')->label('Action')
                            ->dropDownList($actions_array, ['prompt' => 'Action']) ?>
                    </div>
                    <div class="   col-lg-3 col-md-3 col-sm-3 button-position">
                        <?php $modelHistory->time_from = Yii::$app->formatter->asDate($time_from, 'yyyy-MM-dd') ;
                        echo $model_history_form->field($modelHistory, 'time_from')->label('Date from')
                            ->input('date',['prompt' => 'yyyy-MM-dd']) ?>
                    </div>
                    <div class="   col-lg-3 col-md-3 col-sm-3 button-position">
                        <?php $modelHistory->time_to = Yii::$app->formatter->asDate($time_to, 'yyyy-MM-dd');
                        echo $model_history_form->field($modelHistory, 'time_to')->label('Date to')
                            ->input('date',['prompt' => 'yyyy-MM-dd']) ?>
                    </div>
                    <div class="   col-lg-2 col-md-2 col-sm-2 button-position">
                        <?php $modelHistory->all_users = [$selected_all];
                        echo $model_history_form->field($modelHistory, 'all_users')->checkboxList([1 =>'All users'])->label(false); ?>
                    </div>
                    <div class="btn-change-user" >
                        <a href="/admin-panel" class="btn btn-primary  btn-custom" >Change user</a>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-md-6 col-sm-6 col-lg-6">
                            <?= Html::submitButton('Get history', ['class' => 'btn btn-primary btn-block  btn-custom']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>



                </div>
            </div>
        </div>


    </div>

<div class=" col-sm-12 col-md-12 col-lg-12" >
    <div class="alert alert-success" >
        Search query parameters: <br>
        User: <?= $selected_all ? 'All users' : $user_data->email ?> <br>
        Action: <?= $actions_name_array[$modelHistory->action]  ?> <br>
        Time interval: <?= $modelHistory->time_from ?> - <?= $modelHistory->time_to ?> <br>
    </div>

</div >



















<div class=" col-sm-12 col-md-12 col-lg-12 table-responsive" >
    <table id="table-services" class="table table-bordered table-hover table-striped  table-custom">
        <thead>
        <tr>
            <th><h4>ID</h4></th>
            <th><h4>Action</h4></th>
            <th><h4>User ID</h4></th>
            <th><h4>Action time</h4></th>
            <th><h4>E-mail</h4></th>
            <th>
                <h4 class="infotext"
                    data-title="ipV4 address attached to the user's account and stored in the database" >
                    IP data base
                    <span class="glyphicon glyphicon-info-sign "></span>
                </h4>
            </th>
            <th>
                <h4 class="infotext"
                    data-title="ipV4 address from which this change was made">
                    IP action
                    <span class="glyphicon glyphicon-info-sign infotext"></span>
                </h4>
            </th>
            <th>
                <h4 class="infotext" data-title="Actual only for the action - change ip address">
                    IP new
                    <span class="glyphicon glyphicon-info-sign infotext"></span>
                </h4>
            </th>
            <th>
                <h4 class="infotext" data-title="Actual only for the action - change e-mail">
                    E-mail new
                    <span class="glyphicon glyphicon-info-sign infotext"></span>
                </h4>
            </th>
            <th>
                <h4 class="infotext" data-title="The action was committed by the administrator">
                    Admin action
                    <span class="glyphicon glyphicon-info-sign infotext"></span>
                </h4>
            </th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($history_data_page as $k => $v): ?>
        <tr>
            <td><?= $v['id'] ?></td>
            <td><?= $v['action'] ?></td>
            <td><?= $v['user_id'] ?></td>
            <td><?= $v['created_at'] ?></td>
            <td><?= $v['email'] ?></td>
            <td><?= $v['ip_db'] ?></td>
            <td><?= $v['ip_real'] ?></td>
            <td><?= $v['ip_db_new'] ?></td>
            <td><?= $v['email_new'] ?></td>
            <td><?= $v['by_admin'] ?></td>
        </tr>
        <?php endforeach; ?>


        </tbody>
    </table>

    <div class="col-lg-12 col-md-12 col-sm-12 pagination-custom">
        <?php  echo LinkPager::widget([
            'pagination' => $pages,
        ]); ?>

    </div>


</div>


