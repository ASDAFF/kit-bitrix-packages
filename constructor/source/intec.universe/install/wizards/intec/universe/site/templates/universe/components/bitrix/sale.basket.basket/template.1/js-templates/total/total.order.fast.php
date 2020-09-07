<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var string $sTemplateId
 */

if ($arParams['ORDER_FAST_USE'] !== 'Y' && empty($arParams['ORDER_FAST_TEMPLATE']))
    return;

$sPrefix = 'ORDER_FAST_';
$iLength = StringHelper::length($sPrefix);

$arParameters = [
    'TEMPLATE' => $arParams['ORDER_FAST_TEMPLATE'],
    'PARAMETERS' => []
];

foreach ($arParams as $key => $sParameter) {
    if (!StringHelper::startsWith($key, $sPrefix))
        continue;

    $key = StringHelper::cut($key, $iLength);
    $arParameters['PARAMETERS'][$key] = $sParameter;
}

unset($key, $sParameter);

?>
<?php if (!empty($arParameters['PARAMETERS'])) { ?>
    <?= Html::tag('div', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_ORDER_FAST'), [
        'class' => [
            'basket-order-button',
            'intec-ui' => [
                '',
                'control-button',
                'size-3',
                'mod-round-4',
                'mod-block',
                'mod-transparent',
                'scheme-current'
            ]
        ],
        'onclick' => 'universe.components.show('.JavaScript::toObject([
            'component' => 'intec.universe:sale.order.fast',
            'template' => $arParameters['TEMPLATE'],
            'parameters' => $arParameters['PARAMETERS'],
            'settings' => [
                'parameters' => [
                    'width' => null
                ]
            ]
        ]).')'
    ]) ?>
<?php } ?>