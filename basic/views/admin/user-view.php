<?php
use yii\helpers\Html;
use app\components\debugger\Debugger;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = 'View and Edit user: '.$user_data->email;

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row margin-bottom-3" >
        <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-2 col-md-2 col-lg-2" >
            <a href="/admin-panel/user-delete" onclick="return confirm('Are you sure you want to delete account <?= $user_data->email  ?>?')"
               class="btn btn-danger btn-lg">Delete account</a>
        </div>
        <div class="col-sm-2 col-md-2 col-lg-2" >
            <a href="/admin-panel/user-history" class="btn btn-primary btn-lg">Actions history</a>
        </div>
    </div>



    <div class="col-sm-12 col-md-12 col-lg-12">
        <table id="table-services" class="table table-bordered table-hover table-custom">
            <thead>
            <tr>
                <th colspan="3"><h3>Details</h3></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Server IPv4 address</td>
                <td colspan="2"><?= $user_settings['server_ipv4_address'] ?></td>
            </tr>
            <tr data-toggle="collapse" data-target='#change_ip'>
                <td>Client IPv4 address</td>
                <td><?= long2ip((float)$user_data['ip']) ?></td>
                <td class="btn btn-primary btn-block btn-custom ">
                    <span class="glyphicon glyphicon-refresh"></span>
                </td>
            </tr>
            <tr id="change_ip" class="collapse">


                <td colspan="3">


                    <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">
                        <div class=" panel panel-default custom-panel-style form-inside-styles">
                            <div class="panel-heading">
                                <h3>Change ipV4 address</h3>
                            </div>
                            <div class="panel-body">
                                <?php Pjax::begin(['id' => 'ip-change']); ?>


                                <?php $model_ip_change_form = ActiveForm::begin([
                                    'id' => 'modelIpChangeForm',
                                    'options' => ['data-pjax' => true],
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "{label}\n<div class=\"col-lg-6 col-md-6 col-sm-6\">{input}</div>\n<div class=\"col-lg-3 col-md-3 col-sm-3\">{error}</div>",
                                        'labelOptions' => ['class' => 'col-lg-3 col-md-3 col-sm-3 control-label'],
                                    ],

                                ]); ?>


                                <?php $modelIpUpdate->ip = long2ip((float)$user_data['ip']);
                                echo $model_ip_change_form->field($modelIpUpdate, 'ip')->textInput()->label('New ipV6') ?>


                                <div class="form-group">
                                    <div
                                        class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4 col-lg-4 col-md-4 col-sm-4">
                                        <?= Html::submitButton('Change', ['class' => 'btn btn-primary btn-block btn-custom', 'name' => 'change-ip-button']) ?>
                                    </div>
                                </div>

                                <?php ActiveForm::end(); ?>
                                <?php Pjax::end(); ?>


                            </div>
                        </div>
                    </div>


                </td>
            </tr>
            <tr>
                <td>Server IPv6 address</td>
                <td colspan="2"><?= $user_settings['server_ipv4_address'] . "/64" ?></td>
            </tr>
            <tr>
                <td>Client IPv6 address</td>
                <td colspan="2"><?= $user_settings['ipv6_if_their'] . "/64" ?></td>
            </tr>
            <tr>
                <td>Routed /48 IPv6 network</td>
                <td colspan="2"><?= $user_settings['ipv6_routed'] ?></td>
            </tr>
            <tr>
                <td>IPv6 DNS server</td>
                <td colspan="2"><?= $user_settings['ipv6_dns_server'] ?></td>
            </tr>

            <tr>
                <td>Your e-mail (login)</td>
                <td><?= $user_data->email ?></td>
                <td data-toggle="collapse" data-target='#change_email' class="btn btn-primary btn-block btn-custom ">
                    <span class="glyphicon glyphicon-refresh"></span></td>
            </tr>
            <tr id="change_email" class="collapse">
                <td colspan="3">Смена email тест</td>
            </tr>
            </tbody>
        </table>


        <?php echo '';//Pjax::begin(['id' => 'dns-change']); ?>
        <table id="table-services" class="table table-bordered table-hover table-custom">
            <thead>
            <tr>
                <th colspan="4"><h3>Reverse (backresolve) DNS</h3></th>
            </tr>
            </thead>
            <tbody>
            <tr data-toggle="collapse" data-target='#change_ptr'>
                <td><?= $user_settings['ipv6_if_their'] ?></td>
                <td> in ptr</td>
                <td><?= $user_settings['ptr'] ?></td>
                <td class="btn btn-primary btn-block btn-custom "><span class="glyphicon glyphicon-refresh"></span></td>
            </tr>
            <tr id="change_ptr" class="collapse" >
                <td colspan="4" >


                    <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">
                        <div class=" panel panel-default custom-panel-style form-inside-styles">
                            <div class="panel-heading">
                                <h3>Change Ptr</h3>
                            </div>
                            <div class="panel-body">
                                <?php Pjax::begin(['id' => 'ptr-change']); ?>

                                <?php $model_ptr_form = ActiveForm::begin([
                                    'id' => 'modelPtrForm',
                                    'options' => ['data-pjax' => true],
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "{label}\n<div class=\"col-lg-6 col-md-6 col-sm-6\">{input}</div>\n<div class=\"col-lg-3 col-md-3 col-sm-3\">{error}</div>",
                                        'labelOptions' => ['class' => 'col-lg-3 col-md-3 col-sm-3 control-label'],
                                    ],

                                ]); ?>


                                <?php $modelPtr->ptr = $user_settings['ptr'];
                                echo $model_ptr_form->field($modelPtr, 'ptr')->textInput()->label('PTR') ?>


                                <div class="form-group">
                                    <div
                                        class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4 col-lg-4 col-md-4 col-sm-4">
                                        <?= Html::submitButton('Change', ['class' => 'btn btn-primary btn-block btn-custom', 'name' => 'change-ptr-button']) ?>
                                    </div>
                                </div>

                                <?php ActiveForm::end(); ?>

                                <?php Pjax::end(); ?>
                            </div>
                        </div>
                    </div>




                </td>
            </tr>
            <tr data-toggle="collapse" data-target='#change_dns'>
                <td><?= $user_settings['rdns'] ?></td>
                <td> in ns</td>
                <td><?= $user_settings['ns1'] ?></td>
                <td class="btn btn-primary btn-block btn-custom "><span class="glyphicon glyphicon-refresh"></span></td>
            </tr>
            <tr data-toggle="collapse" data-target='#change_dns'>
                <td><?= $user_settings['rdns'] ?></td>
                <td> in ns</td>
                <td><?= $user_settings['ns2'] ?></td>
                <td class="btn btn-primary btn-block btn-custom "><span class="glyphicon glyphicon-refresh"></span></td>
            </tr>

            <tr id="change_dns" class="collapse">
                <td colspan="4">


                    <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">
                        <div class=" panel panel-default custom-panel-style form-inside-styles">
                            <div class="panel-heading">
                                <h3>Change DNS</h3>
                            </div>
                            <div class="panel-body">
                                <?php Pjax::begin(['id' => 'dns-change']); ?>

                                <?php $model_dns_form = ActiveForm::begin([
                                    'id' => 'modelDnsForm',
                                    'options' => ['data-pjax' => true],
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "{label}\n<div class=\"col-lg-6 col-md-6 col-sm-6\">{input}</div>\n<div class=\"col-lg-3 col-md-3 col-sm-3\">{error}</div>",
                                        'labelOptions' => ['class' => 'col-lg-3 col-md-3 col-sm-3 control-label'],
                                    ],

                                ]); ?>



                                <?php $modelDns->dns1 = $user_settings['ns1'];
                                echo $model_dns_form->field($modelDns, 'dns1')->textInput()->label('DNS 1') ?>
                                <?php $modelDns->dns2 = $user_settings['ns2'];
                                echo $model_dns_form->field($modelDns, 'dns2')->textInput()->label('DNS 2') ?>


                                <div class="form-group">
                                    <div
                                        class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4 col-lg-4 col-md-4 col-sm-4">
                                        <?= Html::submitButton('Change', ['class' => 'btn btn-primary btn-block btn-custom', 'name' => 'change-dns-button']) ?>
                                    </div>
                                </div>

                                <?php ActiveForm::end(); ?>

                                <?php Pjax::end(); ?>
                            </div>
                        </div>
                    </div>


                </td>
            </tr>
            </tbody>
        </table>
        <?php echo '';// Pjax::end(); ?>
    </div>




</div>
