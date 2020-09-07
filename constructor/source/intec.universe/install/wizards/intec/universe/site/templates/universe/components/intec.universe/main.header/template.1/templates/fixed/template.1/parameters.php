<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\UnsetArrayValue;

$arReturn = [];

$arReturn['ADDRESS_SHOW_FIXED'] = new UnsetArrayValue();
$arReturn['PHONES_SHOW_FIXED'] = new UnsetArrayValue();
$arReturn['EMAIL_SHOW_FIXED'] = new UnsetArrayValue();
$arReturn['TAGLINE_SHOW_FIXED'] = new UnsetArrayValue();

$arReturn['FIXED_MENU_POPUP_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_FIXED_TEMP1_MENU_POPUP_SHOW'),
    'TYPE' => 'CHECKBOX'
];

return $arReturn;