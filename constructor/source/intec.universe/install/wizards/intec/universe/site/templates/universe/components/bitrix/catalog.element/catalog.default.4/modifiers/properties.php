<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\helpers\FileHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arData
 */

$arData = [
    'MARKS' => [],
    'ARTICLE' => [],
    'BRAND' => [],
    'GALLERY' => [],
    'PROPERTIES' => [],
    'DOCUMENTS' => [],
    'ADDITIONAL' => []
];

/** Метка "Новинка" */
if (!empty($arParams['PROPERTY_MARKS_NEW'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_MARKS_NEW']
    ]);

    if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
        $arData['MARKS']['NEW'] = !empty($arProperty['VALUE']);

    unset($arProperty);
}

/** Метка "Хит" */
if (!empty($arParams['PROPERTY_MARKS_HIT'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_MARKS_HIT']
    ]);

    if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
        $arData['MARKS']['HIT'] = !empty($arProperty['VALUE']);

    unset($arProperty);
}

/** Метка "Рекомендуем" */
if (!empty($arParams['PROPERTY_MARKS_RECOMMEND'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_MARKS_RECOMMEND']
    ]);

    if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
        $arData['MARKS']['RECOMMEND'] = !empty($arProperty['VALUE']);

    unset($arProperty);
}

/** Артикул */
if (!empty($arParams['PROPERTY_ARTICLE'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_ARTICLE']
    ]);

    if (!empty($arProperty['VALUE']) && $arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N') {
        $arProperty = CIBlockFormatProperties::GetDisplayValue(
            $arResult,
            $arProperty,
            false
        );

        if (!empty($arProperty['DISPLAY_VALUE']))
            $arData['ARTICLE'] = [
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arProperty['DISPLAY_VALUE']
            ];
    }

    unset($arProperty);
}

/** Бренд */
if (!empty($arParams['PROPERTY_BRAND'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BRAND']
    ]);

    if (!empty($arProperty['VALUE']) && $arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['MULTIPLE'] === 'N') {
        $arProperty = CIBlockElement::GetByID($arProperty['VALUE'])->GetNext();

        if (!empty($arProperty)) {
            if (!empty($arProperty['PREVIEW_PICTURE']) || !empty($arProperty['DETAIL_PICTURE'])) {
                $arImage = null;

                if (!empty($arProperty['PREVIEW_PICTURE']))
                    $arImage = $arProperty['PREVIEW_PICTURE'];
                else
                    $arImage = $arProperty['DETAIL_PICTURE'];

                $arImage = CFile::GetFileArray($arImage);

                if (!empty($arImage))
                    $arData['BRAND'] = [
                        'NAME' => $arProperty['NAME'],
                        'URL' => $arProperty['DETAIL_PAGE_URL'],
                        'PICTURE' => $arImage
                    ];

                unset($arImage);
            }
        }
    }

    unset($arProperty);
}

/** Сокращенные характеристики */
if (!empty($arResult['DISPLAY_PROPERTIES'])) {
    $iCount = Type::toInteger($arParams['PROPERTIES_PREVIEW_COUNT']);

    if ($iCount > 0) {
        $arDisplayProperties = ArrayHelper::slice($arResult['DISPLAY_PROPERTIES'], 0, $iCount);

        foreach ($arDisplayProperties as $arProperty)
            $arData['PROPERTIES'][] = [
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arProperty['DISPLAY_VALUE']
            ];

        unset($arDisplayProperties, $arProperty);
    }

    unset($iCount);
}

/** Документы */
if (!empty($arParams['PROPERTY_DOCUMENTS'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_DOCUMENTS']
    ]);

    if (!empty($arProperty['VALUE']) && Type::isArray($arProperty['VALUE'])) {
        $arProperty = Arrays::fromDBResult(CFile::GetList(['SORT' => 'ASC'], [
            '@ID' => implode(',', $arProperty['VALUE'])
        ]))->indexBy('ID');

        if (!$arProperty->isEmpty()) {
            $arProperty = $arProperty->asArray(function ($id, $arFile) {
                $arFile['SRC'] = CFile::GetFileSRC($arFile);

                return [
                    'key' => $id,
                    'value' => [
                        'NAME' => FileHelper::getFileNameWithoutExtension($arFile['ORIGINAL_NAME']),
                        'TYPE' => FileHelper::getFileExtension($arFile['SRC']),
                        'SIZE' => CFile::FormatSize($arFile['FILE_SIZE']),
                        'SRC' => $arFile['SRC']
                    ]
                ];
            });

            $arData['DOCUMENTS'] = $arProperty;
        }
    }

    unset($arProperty);
}

/** Доп. товары */
if (!empty($arParams['PROPERTY_ADDITIONAL'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_ADDITIONAL']
    ]);

    if (!empty($arProperty['VALUE']) && Type::isArray($arProperty['VALUE'])) {
        foreach ($arProperty['VALUE'] as $arValue) {
            if (Type::isNumeric($arValue))
                $arData['ADDITIONAL'][] = $arValue;
        }

        unset($arValue);
    }

    unset($arProperty);
}

$arResult['DATA'] = $arData;

unset($arData);