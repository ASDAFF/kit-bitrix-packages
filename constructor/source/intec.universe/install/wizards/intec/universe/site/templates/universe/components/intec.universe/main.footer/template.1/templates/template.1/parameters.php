<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$arReturn = [];
$arReturn['ICONS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ALFABANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_ALFABANK'),
        'SBERBANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_SBERBANK'),
        'QIWI' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_QIWI'),
        'YANDEXMONEY' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_YANDEXMONEY'),
        'VISA' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_VISA'),
        'MASTERCARD' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_MASTERCARD')
    ],
    'MULTIPLE' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
];

return $arReturn;