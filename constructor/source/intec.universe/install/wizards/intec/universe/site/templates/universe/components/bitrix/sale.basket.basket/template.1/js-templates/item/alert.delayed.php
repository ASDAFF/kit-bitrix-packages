<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>
{{#DELAYED}}
    <?= Html::beginTag('div', [
        'class' => [
            'basket-alert',
            'intec-ui' => [
                '',
                'control-alert',
                'scheme-blue'
            ]
        ]
    ]) ?>
        <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_DELAYED_MESSAGE')) ?>
        <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_DELAYED_REMOVE'), [
            'class' => 'basket-alert-interactive',
            'data-entity' => 'basket-item-remove-delayed',
        ]) ?>
    <?= Html::endTag('div') ?>
{{/DELAYED}}