<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\UnsetArrayValue;

$arReturn = [];

$arReturn['TAGLINE_SHOW_MOBILE'] = new UnsetArrayValue();

$arReturn['MOBILE_FILLED'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_FILLED'),
    'TYPE' => 'CHECKBOX'
];
$arReturn['MOBILE_TYPE_SEARCH'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_TYPE_SEARCH'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'page' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_TYPE_SEARCH_PAGE'),
        'popup' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_TYPE_SEARCH_POPUP')
    ],
    'DEFAULT' => 'page'
];

$arReturn['MOBILE_MENU_INFORMATION_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_MENU_INFORMATION_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'view.1' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_MENU_INFORMATION_VIEW_1'),
        'view.2' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_MENU_INFORMATION_VIEW_2')
    ],
];

$arReturn['MOBILE_MENU_BORDER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP2_MENU_BORDER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

return $arReturn;