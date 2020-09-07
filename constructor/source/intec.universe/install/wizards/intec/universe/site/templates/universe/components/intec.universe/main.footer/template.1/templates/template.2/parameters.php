<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\UnsetArrayValue;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$arReturn = [];
$arReturn['ICONS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_2_ICONS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ALFABANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_2_ICONS_ALFABANK'),
        'SBERBANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_2_ICONS_SBERBANK'),
        'QIWI' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_2_ICONS_QIWI'),
        'YANDEXMONEY' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_2_ICONS_YANDEXMONEY'),
        'VISA' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_2_ICONS_VISA'),
        'MASTERCARD' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_2_ICONS_MASTERCARD')
    ],
    'MULTIPLE' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
];

$arReturn['ADDRESS_SHOW'] = new UnsetArrayValue();
$arReturn['ADDRESS_VALUE'] = new UnsetArrayValue();
$arReturn['EMAIL_SHOW'] = new UnsetArrayValue();
$arReturn['EMAIL_VALUE'] = new UnsetArrayValue();

return $arReturn;