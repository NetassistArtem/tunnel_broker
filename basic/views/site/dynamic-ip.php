<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$url = $base_url.'/autochangeip?l=YOURLOGIN&p=YOURPASSWORD&ip=YOURIP';

$this->title = 'Dynamic Ip';
?>
<div class="site-dynamic-ip">

    <h1><?= Html::encode($this->title) ?></h1>

    <div  class="row">
        <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10 main-text" >
            <p>
                If you have the dynamic IP, you can use the auto-update endpoint URL:
                <b><?= $url ?></b>
                The update time is less than 60 seconds.
            </p>
        </div>

    </div>


</div>
