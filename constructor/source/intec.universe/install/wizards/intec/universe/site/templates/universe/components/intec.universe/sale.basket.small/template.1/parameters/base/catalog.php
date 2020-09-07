<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */


$arTemplateParameters['DELAYED_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_DELAYED_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
);