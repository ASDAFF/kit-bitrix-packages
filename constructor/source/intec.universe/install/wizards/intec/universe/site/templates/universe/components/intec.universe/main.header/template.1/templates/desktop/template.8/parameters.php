<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\UnsetArrayValue;
use Bitrix\Main\Localization\Loc;

$arReturn = [];

$sSite = false;

$arReturn['ADDRESS_SHOW'] = new UnsetArrayValue();
$arReturn['AUTHORIZATION_SHOW'] = new UnsetArrayValue();
$arReturn['EMAIL_SHOW'] = new UnsetArrayValue();
$arReturn['TAGLINE_SHOW'] = new UnsetArrayValue();
$arReturn['BASKET_SHOW'] = new UnsetArrayValue();
$arReturn['DELAY_SHOW'] = new UnsetArrayValue();
$arReturn['COMPARE_SHOW'] = new UnsetArrayValue();
$arReturn['SEARCH_SHOW'] = new UnsetArrayValue();
$arReturn['SEARCH_MODE'] = new UnsetArrayValue();

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$arMenuTypes = GetMenuTypes($sSite);

$arReturn['MENU_INFO_SHOW'] = [
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_SHOW'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['MENU_INFO_SHOW'] == 'Y') {
    $arReturn['MENU_INFO_ROOT'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_ROOT'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'DEFAULT ' => 'left',
        'VALUES' => $arMenuTypes,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arReturn['MENU_INFO_CHILD'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_CHILD'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'DEFAULT ' => 'left',
        'VALUES' => $arMenuTypes,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arReturn['MENU_INFO_LEVEL'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_LEVEL'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'DEFAULT ' => 1,
        'VALUES' => [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
        ],
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

return $arReturn;