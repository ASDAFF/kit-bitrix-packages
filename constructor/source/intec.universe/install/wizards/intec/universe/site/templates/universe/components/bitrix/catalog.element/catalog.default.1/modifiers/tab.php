<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arResult['TAB'] = [
    'USE' => true,
    'VALUE' => null,
    'META' => [
        'TITLE' => null,
        'BROWSER_TITLE' => null,
        'CHAIN' => null,
        'KEYWORDS' => null,
        'DESCRIPTION' => null
    ],
    'URL' => [
        'TEMPLATE' => null,
        'VALUE' => null
    ]
];

$oTemplate = new \Bitrix\Iblock\Template\Entity\Element($arResult['ID']);

if (!empty($arParams['TAB']))
    $arResult['TAB']['VALUE'] = StringHelper::toUpperCase($arParams['TAB']);

if (!empty($arParams['TAB_URL'])) {
    $arResult['TAB']['URL']['TEMPLATE'] = CIBlock::ReplaceDetailUrl($arParams['TAB_URL'], $arResult, true, 'E');

    if (!empty($arResult['TAB']['VALUE'])) {
        $arResult['TAB']['URL']['VALUE'] = StringHelper::replaceMacros($arResult['TAB']['URL']['TEMPLATE'], [
            'TAB' => $arResult['TAB']['VALUE']
        ]);
    } else {
        $arResult['TAB']['URL']['VALUE'] = $arResult['DETAIL_PAGE_URL'];
    }
} else {
    $arResult['TAB']['USE'] = false;
}

if ($arResult['TAB']['USE']) {
    foreach ($arResult['TAB']['META'] as $sKey => $sValue) {
        $sValue = ArrayHelper::getValue($arResult, [
            'PROPERTIES',
            $arParams['PROPERTY_TAB_META_' . $sKey],
            'VALUE'
        ]);

        if (empty($sValue))
            $sValue = ArrayHelper::getValue($arResult, [
                'PROPERTIES',
                $arParams['PROPERTY_TAB_META_' . $sKey],
                'DEFAULT_VALUE'
            ]);

        if (!empty($sValue))
            $arResult['TAB']['META'][$sKey] = \Bitrix\Iblock\Template\Engine::process($oTemplate, $sValue);
    }
}

unset($oTemplate, $sKey, $sValue);

$this->getComponent()->setResultCacheKeys(['TAB']);