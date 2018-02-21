<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\debugger\Debugger;
use app\models\User;

AppAsset::register($this);
$flash = Yii::$app->session->getAllFlashes();
$guest = Yii::$app->user->isGuest;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/netAssist3.png', ['alt' => Yii::$app->name, 'class' =>'logo', 'id' => 'logo']),
        'brandUrl' => '/main',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top custom-style-navbar',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
          //  User::isAdmin() ? (
                [
                    'label' => 'Admin panel',
                    'url' => ['/admin/admin-panel'],
                    'visible' => User::isAdmin(),
                    'linkOptions' => ['class' => 'admin-panel']
                ],

           // ) : ( ''

           // ),


            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'login-nav']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout ',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ),
            ['label' => 'Information', 'url' => '/', 'linkOptions' => ['class' => 'info-nav'],
                 'active' => (
                  Yii::$app->request->url == "/dynamic-ip" ||
                  Yii::$app->request->url == "/autonomous-system" ||
                 Yii::$app->request->url == "/additional-services"
                   ),
                'items' => [
                    ['label' => 'Dynamic IP', 'url' => '/dynamic-ip',],
                    ['label' => 'Autonomous System', 'url' => "/autonomous-system",],
                    ['label' => 'Additional services', 'url' => '/additional-services',],
                    $guest ? ('') : (['label' => 'Configuration Examples', 'url' => ['/configuration-examples']]),

                ]
            ],


        ],

    ]);
    NavBar::end();

    $this->registerJsFile(
        'scripts/tb.js',
        ['depends' => 'app\assets\AppAsset']
    );

    ?>

    <div class="container">

        <div>
            <?php
            if (!empty($flash)):
                foreach ($flash as $k => $v):?>
                    <div class="alert alert-<?= $k ?> alert-dismissible  show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="alert-heading"><?= $k ?>!</h4>

                        <p><?= $v ?></p>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
        <div class="row content-position">
            <?= $content ?>
        </div>




        <div class="row">
            <div class="col-sm-5 col-md-5 col-lg-5">
                <div class="panel panel-default custom-panel-style footer-block-height">
                    <div class="panel-heading">
                        <h3>Contacts</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                            Support:
                        </p>
                        <ul>
                            <li><a href="http://conference.netassist.ua/index.php?t=index&cat=6">Forum</a></li>
                            <li><a href="mailto:support@netassist.ua">E-mail: support@netassist.ua</a></li>
                            <li>
                                <p>24h duty emergency phones:</p>
                                <p>+380(44)239-89-99</p>

                            </li>
                        </ul>


                    </div>

                </div>
            </div>
            <div class="col-sm-7 col-md-7 col-lg-7">
                <div class="panel panel-default custom-panel-style footer-block-height">
                    <div class="panel-heading">
                        <h3>Donate</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                            We provide our service free of charge. If you like it and found it useful, you can help us
                            to maintain and improve it. Please, donate this project via PayPal or WebMoney:
                        </p>
                        <div class="col-sm-6 col-md-6 col-lg-6 donate-style">
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="7MT2KZ7RKE6W6">
                                <input class="btn btn-primary btn-custom" type="submit"  border="0" value = "Donate by PayPal"  name="submit">
                                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                            </form>
                            <img class="donate-image" src="/basic/web/images/paypal5.png" alt="">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 donate-style">
                            <button data-toggle="collapse" data-target='#web-many-donate' class="btn btn-primary btn-custom" href="#" role="button">Donate by WebMony </button>
                            <img class="donate-image" src="/basic/web/images/webmany5.png" alt="">
                        </div>
                        <div>

                        </div>


                    </div>

                </div>
            </div>

        </div>
        <div class="row collapse" id="web-many-donate" >
            <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-2 col-sm-8 col-md-8 col-lg-8">
                <div class="panel panel-default custom-panel-style footer-block-height">
                    <div class="panel-heading">
                        <h3>Donate by WebMony</h3>
                    </div>
                    <div class="panel-body">



                        <form class="form-webmony" action="https://merchant.webmoney.ru/lmi/payment.asp" method="post">
                            <p ><strong>Thank you for supporting our project!</strong></p>
                            <p>Please, enter amount in WMZ you wish to donate</p>
                            <div class="form-group" >
                                <label for="LMI_PAYMENT_AMOUNT" class="col-lg-3 col-md-3 col-sm-3 control-label" >Amount in WMZ</label>
                                <div class=" col-lg-6 col-md-6 col-sm-6" >
                                    <input class="form-control" type="number" name="LMI_PAYMENT_AMOUNT" value="">
                                </div>

                            </div>
                                <input type="hidden" name="LMI_PAYEE_PURSE" value="Z232943325580">
                                <input type="hidden" name="LMI_PAYMENT_NO" value="1">
                                <input type="hidden" name="LMI_PAYMENT_DESC" value="NetAssist IPv6 tunnel broker donation">
                            <div class="form-group" >
                                <div class="col-lg-offset-4 col-sm-offset-4 col-md-offset-4 col-lg-4 col-md-4 col-sm-4" >
                                    <button class="btn btn-primary btn-block btn-custom margin-pot-2" type="submit"  >Donate!</button>
                                </div>

                            </div>


                        </form>



                    </div>

                </div>
            </div>
        </div>


    </div>
</div>

<footer class="footer footer-custom">
    <div class="container">
        <p class="pull-left">&copy; NetAssist <?= date('Y') ?></p>


    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
