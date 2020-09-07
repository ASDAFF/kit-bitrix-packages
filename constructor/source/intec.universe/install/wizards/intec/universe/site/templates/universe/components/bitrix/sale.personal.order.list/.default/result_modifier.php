<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\net\Url;

/**
 * @var array $arParams
 * @var array $arResult
 */

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('intec.core'))
    return;

$oRequest = Core::$app->request;
$arResult['FILTER'] = [
    'VALUE' => 'current',
    'URL' => [
        'CURRENT' => null,
        'COMPLETED' => null,
        'CANCELED' => null
    ]
];

$oUrl = new Url($oRequest->getUrl());
$oUrl->getQuery()
    ->removeAt('filter_history')
    ->removeAt('show_canceled');

$arResult['FILTER']['URL']['CURRENT'] = $oUrl->build();
$oUrl->getQuery()->set('filter_history', 'Y');
$arResult['FILTER']['URL']['COMPLETED'] = $oUrl->build();
$oUrl->getQuery()->set('show_canceled', 'Y');
$arResult['FILTER']['URL']['CANCELED'] = $oUrl->build();

if ($oRequest->get('filter_history') === 'Y') {
    $arResult['FILTER']['VALUE'] = 'completed';

    if ($oRequest->get('show_canceled') === 'Y')
        $arResult['FILTER']['VALUE'] = 'canceled';
}

// we dont trust input params, so validation is required
$legalColors = array(
    'green' => true,
    'yellow' => true,
    'red' => true,
    'gray' => true
);
// default colors in case parameters unset
$defaultColors = array(
    'N' => 'green',
    'P' => 'yellow',
    'F' => 'gray',
    'PSEUDO_CANCELLED' => 'red'
);

foreach ($arParams as $key => $val)
    if(strpos($key, "STATUS_COLOR_") !== false && !$legalColors[$val])
        unset($arParams[$key]);

// to make orders follow in right status order
if(is_array($arResult['INFO']) && !empty($arResult['INFO']))
{
    foreach($arResult['INFO']['STATUS'] as $id => $stat)
    {
        $arResult['INFO']['STATUS'][$id]["COLOR"] = $arParams['STATUS_COLOR_'.$id] ? $arParams['STATUS_COLOR_'.$id] : (isset($defaultColors[$id]) ? $defaultColors[$id] : 'gray');
        $arResult["ORDER_BY_STATUS"][$id] = array();
    }
}
$arResult["ORDER_BY_STATUS"]["PSEUDO_CANCELLED"] = array();

$arResult["INFO"]["STATUS"]["PSEUDO_CANCELLED"] = array(
    "NAME" => Loc::getMessage('C_SALE_PERSONAL_ORDER_LIST_STATUSES_PSEUDO_CANCELLED'),
    "COLOR" => $arParams['STATUS_COLOR_PSEUDO_CANCELLED'] ? $arParams['STATUS_COLOR_PSEUDO_CANCELLED'] : (isset($defaultColors['PSEUDO_CANCELLED']) ? $defaultColors['PSEUDO_CANCELLED'] : 'gray')
);

if(is_array($arResult["ORDERS"]) && !empty($arResult["ORDERS"]))
{
    foreach ($arResult["ORDERS"] as $order)
    {
        $order['HAS_DELIVERY'] = intval($order["ORDER"]["DELIVERY_ID"]) || strpos($order["ORDER"]["DELIVERY_ID"], ":") !== false;

        $stat = $order['ORDER']['CANCELED'] == 'Y' ? 'PSEUDO_CANCELLED' : $order["ORDER"]["STATUS_ID"];
        $color = $arParams['STATUS_COLOR_'.$stat];
        $order['STATUS_COLOR_CLASS'] = empty($color) ? 'gray' : $color;

        $arResult["ORDER_BY_STATUS"][$stat][] = $order;
    }
}