<?php

use pavlinter\admoplata\models\OplataTransaction;
use pavlinter\admpages\Module;


/* @var $this yii\web\View */
/* @var $model OplataTransaction */

$thFirst    = 'border: 1px solid #ddd;text-align: left;padding: 3px;background: #4e5154;color: white;font-weight: normal;';
$th         = 'border: 1px solid #ddd;text-align: right;padding: 3px;background: #4e5154;color: white;font-weight: normal;';
$tdFirst    = 'border: 1px solid #ddd;padding: 3px;background: #eeeeee;';
$td         = 'border: 1px solid #ddd;text-align: right;padding: 3px;background: #eeeeee;';
$td2        = 'border-top: 1px solid #ddd;text-align: right;padding: 3px;';
$tdFirtsRow2= 'text-align: right;padding: 3px;';

Yii::$app->getI18n()->disableDot();
$this->title = Module::t("print","Invoice: #{id}, {title}", ['id' => $model->id, 'title' => $model->title]);
?>


<div style="width: 100%;">
    <div style="float: left;width: 55%;">
        <?= $logo ?>
    </div>
    <div style="float: right;width: 30%;font-size: 20pt;padding: 20px 0px 0px 0px;text-align: left;">
        <?= Module::t('print','Invoice No. {invoice-number}', [
            'invoice-number' => $model->id,
            'email' => $model->email,
            'date' => Yii::$app->formatter->asDate($model->created_at),
            'time' => Yii::$app->formatter->asTime($model->created_at),
            'status' => $model::status_list($model->response_status),
            'description' => nl2br($model->description),
            'date_end' => Yii::$app->formatter->asDate($model->date_end),
            'dot' => false,
        ]); ?>
    </div>
    <div style="clear: both;"></div>
    <br/>
</div>

<div style="width: 100%;">
    <div style="float: left;width: 55%;">
        <?= Module::t('print','From<br/>Web Services, Inc.') ?>
    </div>
    <div style="float: right;width: 30%;">
        <?php if ($model->user_id) {?>
            <?= Module::t('print','Invoice No. {invoice-number}<br/>To: {email}<br/>Invoice Date: {date}<br/>Payment day: {date_end}<br/>{description}', [
                'invoice-number' => $model->id,
                'email' => $model->email,
                'date' => Yii::$app->formatter->asDate($model->created_at),
                'time' => Yii::$app->formatter->asTime($model->created_at),
                'status' => $model::status_list($model->response_status),
                'description' => nl2br($model->description),
                'date_end' => Yii::$app->formatter->asDate($model->date_end),
                'dot' => false,
            ]); ?>
        <?php } else {?>
            <?= Module::t('print','Invoice No. {invoice-number}<br/>To: {person}<br/>Email: {email}<br/>Invoice Date: {date}<br/>Payment day: {date_end}<br/>{description}', [
                'invoice-number' => $model->id,
                'person' => $model->person,
                'email' => $model->email,
                'date' => Yii::$app->formatter->asDate($model->created_at),
                'time' => Yii::$app->formatter->asTime($model->created_at),
                'status' => $model::status_list($model->response_status),
                'description' => nl2br($model->description),
                'date_end' => Yii::$app->formatter->asDate($model->date_end),
                'dot' => false,
            ]); ?>
        <?php }?>
    </div>
    <div style="clear: both;"></div>
</div>
<br/>
<table style="width: 100%;border-collapse: collapse;">
    <thead>
    <tr>
        <th style="<?= $thFirst ?>"><?= Yii::t('adm/admoplata','Item') ?></th>
        <th style="<?= $th ?>"><?= Yii::t('adm/admoplata','Quantity') ?></th>
        <th style="<?= $th ?><?= $th ?>"><?= Yii::t('adm/admoplata','Unit Price') ?></th>
        <th style="<?= $th ?>"><?= Yii::t('adm/admoplata','Total Price') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total = 0;
    foreach ($model->items as $item) {
        $sum = $item->price * $item->amount;
        $total += $sum;
        ?>
        <tr>
            <td style="<?= $tdFirst ?>width: 45%;color: #636e7b;">
                <div><?= $item->title ?></div>
                <?php if ($item->description) {?>
                    <p style="font-size: 8pt;"><?= $item->description ?></p>
                <?php }?>
            </td>
            <td style="<?= $td ?>width: 15%;color: #636e7b;"><?= $item->amount ?></td>
            <td style="<?= $td ?>width: 20%;color: #636e7b;"><?= Yii::$app->oplata->price($item->price, $model->currency); ?></td>
            <td style="<?= $td ?>width: 20%;color: #636e7b;"><?= Yii::$app->oplata->price($sum, $model->currency); ?></td>
        </tr>
    <?php }?>
    </tbody>
</table>
<br/>
<div style="float: right; width: 30%;">
    <table style="width: 100%;border-collapse: collapse;">
        <tbody>
        <tr>
            <td style="<?= $tdFirtsRow2 ?>"><?= Yii::t('adm/admoplata','Sub Total:') ?></td>
            <td style="<?= $tdFirtsRow2 ?>"><?= Yii::$app->oplata->price($total, $model->currency); ?></td>
        </tr>
        <tr>
            <td style="<?= $td2 ?>"><?= Yii::t('adm/admoplata','Shipping:') ?></td>
            <td style="<?= $td2 ?>"><?= Yii::$app->oplata->price($model->shipping, $model->currency); ?></td>
        </tr>
        <tr>
            <td style="<?= $td2 ?>"><?= Yii::t('adm/admoplata','TOTAL:') ?></td>
            <td style="<?= $td2 ?>"><?= Yii::$app->oplata->price($total + $model->shipping, $model->currency); ?></td>
        </tr>
        </tbody>
    </table>
</div>