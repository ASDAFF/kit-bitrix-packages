<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\UnsetArrayValue;

$arReturn = [];

$arReturn['TAGLINE_SHOW_MOBILE'] = new UnsetArrayValue();

$arReturn['MOBILE_FILLED'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP1_FILLED'),
    'TYPE' => 'CHECKBOX'
];
$arReturn['MOBILE_SEARCH_TYPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP1_SEARCH_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'page' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP1_SEARCH_TYPE_PAGE'),
        'popup' => Loc::getMessage('C_HEADER_TEMP1_MOBILE_TEMP1_SEARCH_TYPE_POPUP')
    ],
    'DEFAULT' => 'page'
];

return $arReturn;