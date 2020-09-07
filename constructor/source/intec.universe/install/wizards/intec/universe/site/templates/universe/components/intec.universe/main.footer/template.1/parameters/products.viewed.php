<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
    Component::getParameters(
        'bitrix:catalog.products.viewed',
        'tile.1',
        $siteTemplate,
        $arCurrentValues,
        'PRODUCTS_VIEWED_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_FOOTER_TEMPLATE_1_PRODUCTS_VIEWED').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'IBLOCK_MODE',
                'IBLOCK_TYPE',
                'IBLOCK_ID',
                'PRICE_CODE'
            ])) return true;

            if (StringHelper::startsWith($sKey, 'SHOW_PRODUCTS_'))
                return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ),
    Component::getParameters(
    'bitrix:catalog.products.viewed',
        'tile.1',
        $siteTemplate,
        $arCurrentValues,
        'PRODUCTS_VIEWED_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_FOOTER_TEMPLATE_1_PRODUCTS_VIEWED').'. '.$arParameter['NAME'];

            return true;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    )
);