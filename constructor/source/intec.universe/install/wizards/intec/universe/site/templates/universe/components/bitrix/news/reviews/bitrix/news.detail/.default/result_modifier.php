<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'PROPERTY_PERSON_NAME' => null,
    'PROPERTY_PERSON_POSITION' => null,
    'PROPERTY_SITE_URL' => null,
    'PROPERTY_DOCUMENT' => null,
    'PROPERTY_SERVICES' => null,
    'PROPERTY_CASES' => null,
    'PROPERTY_VIDEO' => null,
    'TITLE_SHOW' => 'N',
    'DOCUMENT_SHOW' => 'N',
    'SERVICES_SHOW' => 'N',
    'CASES_SHOW' => 'N',
    'VIDEO_SHOW' => 'N',
    'VIDEO_IBLOCK_TYPE' => null,
    'VIDEO_IBLOCK_ID' => null,
    'VIDEO_PROPERTY_LINK' => null,
], $arParams);

$arVisual = [
    'VIDEO' => [
        'SHOW' => $arParams['VIDEO_SHOW'] === 'Y'
    ],
    'DOCUMENT' => [
        'SHOW' => $arParams['DOCUMENT_SHOW'] === 'Y'
    ],
    'SERVICES' => [
        'SHOW' => $arParams['SERVICES_SHOW'] === 'Y'
    ],
    'CASES' => [
        'SHOW' => $arParams['CASES_SHOW'] === 'Y'
    ],
    'TITLE' => [
        'SHOW' => $arParams['TITLE_SHOW'] === 'Y'
    ]
];

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$hSetProperty = function (&$arItem, $property = null, $setName = false, $element = false) {
    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property
        ]);

        if (!empty($arProperty['VALUE'])) {
            if (Type::isArray($arProperty['VALUE'])) {
                if (!$element) {
                    if (ArrayHelper::keyExists('TEXT', $arProperty['VALUE']))
                        $sText = $arProperty['TEXT'];
                    else
                        $sText = ArrayHelper::getFirstValue($arProperty['VALUE']);
                } else {
                    $sText = $arProperty['VALUE'];
                }
            } else {
                $sText = $arProperty['VALUE'];
            }

            return [
                'SHOW' => true,
                'VALUE' => $sText
            ];
        } else if ($setName)
            return [
                'SHOW' => true,
                'VALUE' => $arItem['NAME']
            ];
    }

    return ['SHOW' => false];
};

$arResult['DATA'] = [
    'VIDEO' => $hSetProperty($arResult, $arParams['PROPERTY_VIDEO']),
    'DOCUMENT' => [
        'SHOW' => false,
        'VALUE' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['PROPERTY_DOCUMENT'], 'VALUE'])
    ],
    'PERSON' => [
        'NAME' => $hSetProperty($arResult, $arParams['PROPERTY_PERSON_NAME']),
        'POSITION' => $hSetProperty($arResult, $arParams['PROPERTY_PERSON_POSITION']),
        'SITE_URL' =>  $hSetProperty($arResult, $arParams['PROPERTY_SITE_URL']),
    ],
    'SERVICES' => $hSetProperty($arResult, $arParams['PROPERTY_SERVICES'], false, true),
    'CASES' => $hSetProperty($arResult, $arParams['PROPERTY_CASES'], false, true)
];

if (!empty($arResult['DATA']['DOCUMENT']['VALUE'])) {
    $arResult['DATA']['DOCUMENT']['VALUE'] = CFile::GetFileArray($arResult['DATA']['DOCUMENT']['VALUE']);

    if (!empty($arResult['DATA']['DOCUMENT']['VALUE']))
        $arResult['DATA']['DOCUMENT']['SHOW'] = true;
}