<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<?php return function ($sUrl, $bUrlBlank, $sText) { ?>
    <?= Html::tag('a', $sText, [
        'href' => $sUrl,
        'class' => [
            'widget-item-button',
            'intec-cl-background' => [
                '',
                'light-hover'
            ]
        ],
        'target' => $bUrlBlank ? '_blank' : null
    ]) ?>
<?php } ?>
