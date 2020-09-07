<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>
{{#NOT_AVAILABLE}}
    <?= Html::tag('div', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_UNAVAILABLE'), [
        'class' => [
            'basket-alert',
            'intec-ui' => [
                '',
                'control-alert',
                'scheme-orange'
            ]
        ]
    ]) ?>
{{/NOT_AVAILABLE}}