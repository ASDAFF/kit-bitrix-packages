<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$arResult['PROPERTIES'] = [];

$arFields = [
    'DISABLED' => [ /** Коды полей, которые недолжны попасть в список */
        'NAME',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE'
    ],
    'PRODUCT' => [], /** Поля товара */
    'OFFER' => [] /** Поля торгового предложения */
];

$arProperties = [
    'PRODUCT' => [], /** Свойства товара */
    'OFFER' => [] /** Свойства торгового предложения */
];

/** Отображаемые поля элементов */
if (!empty($arResult['SHOW_FIELDS']))
    foreach ($arResult['SHOW_FIELDS'] as $sCode) {
        if (ArrayHelper::isIn($sCode, $arFields['DISABLED']))
            continue;

        $arField = [
            'CODE' => $sCode,
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_PRODUCT_FIELDS_'.$sCode),
            'ENTITY' => 'product',
            'TYPE' => 'field',
            'ACTION' => StringHelper::replaceMacros($arResult['~DELETE_FEATURE_FIELD_TEMPLATE'], [
                'CODE' => $sCode
            ]),
            'HIDDEN' => false,
            'SORT' => ArrayHelper::getValue($arResult, ['FIELDS_SORT', $sCode])
        ];

        $arField['SORT'] = Type::toInteger($arField['SORT']);
        $arFields['PRODUCT'][$sCode] = $arField;
    }

/** Скрытые поля элементов */
if (!empty($arResult['DELETED_FIELDS']))
    foreach ($arResult['DELETED_FIELDS'] as $sCode) {
        if (ArrayHelper::isIn($sCode, $arFields['DISABLED']))
            continue;

        $arField = [
            'CODE' => $sCode,
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_PRODUCT_FIELDS_'.$sCode),
            'ENTITY' => 'product',
            'TYPE' => 'field',
            'ACTION' => StringHelper::replaceMacros($arResult['~ADD_FEATURE_FIELD_TEMPLATE'], [
                'CODE' => $sCode
            ]),
            'HIDDEN' => true,
            'SORT' => ArrayHelper::getValue($arResult, ['FIELDS_SORT', $sCode])
        ];

        $arField['SORT'] = Type::toInteger($arField['SORT']);
        $arFields['PRODUCT'][$sCode] = $arField;
    }

/** Отображаемые поля торговых предложений */
if (!empty($arResult['SHOW_OFFER_FIELDS']))
    foreach ($arResult['SHOW_OFFER_FIELDS'] as $sCode) {
        if (ArrayHelper::isIn($sCode, $arFields['DISABLED']))
            continue;

        $arField = [
            'CODE' => $sCode,
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_OFFER_FIELDS_'.$sCode),
            'ENTITY' => 'offer',
            'TYPE' => 'field',
            'ACTION' => StringHelper::replaceMacros($arResult['~DELETE_FEATURE_OF_FIELD_TEMPLATE'], [
                'CODE' => $sCode
            ]),
            'HIDDEN' => false,
            'SORT' => ArrayHelper::getValue($arResult, ['FIELDS_SORT', $sCode])
        ];

        $arField['SORT'] = Type::toInteger($arField['SORT']);
        $arFields['OFFER'][$sCode] = $arField;
    }

/** Скрытые поля торговых предложений */
if (!empty($arResult['DELETED_OFFER_FIELDS']))
    foreach ($arResult['DELETED_OFFER_FIELDS'] as $sCode) {
        if (ArrayHelper::isIn($sCode, $arFields['DISABLED']))
            continue;

        $arField = [
            'CODE' => $sCode,
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_COMPARE_OFFER_FIELDS_'.$sCode),
            'ENTITY' => 'offer',
            'TYPE' => 'field',
            'ACTION' => StringHelper::replaceMacros($arResult['~ADD_FEATURE_OF_FIELD_TEMPLATE'], [
                'CODE' => $sCode
            ]),
            'HIDDEN' => true,
            'SORT' => ArrayHelper::getValue($arResult, ['FIELDS_SORT', $sCode])
        ];

        $arField['SORT'] = Type::toInteger($arField['SORT']);
        $arFields['OFFER'][$sCode] = $arField;
    }

unset(
    $sCode,
    $arResult['SHOW_FIELDS'],
    $arResult['SHOW_OFFER_FIELDS'],
    $arResult['EMPTY_FIELDS'],
    $arResult['EMPTY_OFFER_FIELDS'],
    $arResult['DELETED_FIELDS'],
    $arResult['DELETED_OFFER_FIELDS'],
    $arResult['FIELDS_SORT']
);

/** Отображаемые свойства элементов */
if (!empty($arResult['SHOW_PROPERTIES']))
    foreach ($arResult['SHOW_PROPERTIES'] as $sCode => $arProperty) {
        $arProperty['ENTITY'] = 'product';
        $arProperty['TYPE'] = 'property';
        $arProperty['ACTION'] = StringHelper::replaceMacros($arResult['~DELETE_FEATURE_PROPERTY_TEMPLATE'], [
            'CODE' => $sCode
        ]);

        $arProperty['HIDDEN'] = false;
        $arProperties['PRODUCT'][$sCode] = $arProperty;
    }

/** Скрытые свойства элементов */
if (!empty($arResult['DELETED_PROPERTIES']))
    foreach ($arResult['DELETED_PROPERTIES'] as $sCode => $arProperty) {
        $arProperty['ENTITY'] = 'product';
        $arProperty['TYPE'] = 'property';
        $arProperty['ACTION'] = StringHelper::replaceMacros($arResult['~ADD_FEATURE_PROPERTY_TEMPLATE'], [
            'CODE' => $sCode
        ]);

        $arProperty['HIDDEN'] = true;
        $arProperties['PRODUCT'][$sCode] = $arProperty;
    }

/** Отображаемые свойства торговых предложений */
if (!empty($arResult['SHOW_OFFER_PROPERTIES']))
    foreach ($arResult['SHOW_OFFER_PROPERTIES'] as $sCode => $arProperty) {
        $arProperty['ENTITY'] = 'offer';
        $arProperty['TYPE'] = 'property';
        $arProperty['ACTION'] = StringHelper::replaceMacros($arResult['~DELETE_FEATURE_OF_PROPERTY_TEMPLATE'], [
            'CODE' => $sCode
        ]);

        $arProperty['HIDDEN'] = false;
        $arProperties['OFFER'][$sCode] = $arProperty;
    }

/** Скрытые свойства торговых предложений */
if (!empty($arResult['DELETED_OFFER_PROPERTIES']))
    foreach ($arResult['DELETED_OFFER_PROPERTIES'] as $sCode => $arProperty) {
        $arProperty['ENTITY'] = 'offer';
        $arProperty['TYPE'] = 'property';
        $arProperty['ACTION'] = StringHelper::replaceMacros($arResult['~ADD_FEATURE_OF_PROPERTY_TEMPLATE'], [
            'CODE' => $sCode
        ]);

        $arProperty['HIDDEN'] = true;
        $arProperties['OFFER'][$sCode] = $arProperty;
    }

unset(
    $sCode,
    $arResult['SHOW_PROPERTIES'],
    $arResult['SHOW_OFFER_PROPERTIES'],
    $arResult['EMPTY_PROPERTIES'],
    $arResult['EMPTY_OFFER_PROPERTIES'],
    $arResult['DELETED_PROPERTIES'],
    $arResult['DELETED_OFFER_PROPERTIES']
);

$arFields['PRODUCT'] = Arrays::from($arFields['PRODUCT'])->sortBy('SORT', SORT_ASC)->asArray();
$arFields['OFFER'] = Arrays::from($arFields['OFFER'])->sortBy('SORT', SORT_ASC)->asArray();
$arProperties['PRODUCT'] = Arrays::from($arProperties['PRODUCT'])->sortBy('SORT', SORT_ASC)->asArray();
$arProperties['OFFER'] = Arrays::from($arProperties['OFFER'])->sortBy('SORT', SORT_ASC)->asArray();

foreach ($arFields['PRODUCT'] as $arField)
    $arResult['PROPERTIES'][] = $arField;

foreach ($arFields['OFFER'] as $arField)
    $arResult['PROPERTIES'][] = $arField;

foreach ($arProperties['PRODUCT'] as $arProperty)
    $arResult['PROPERTIES'][] = $arProperty;

foreach ($arProperties['OFFER'] as $arProperty)
    $arResult['PROPERTIES'][] = $arProperty;

foreach ($arResult['PROPERTIES'] as &$arProperty) {
    $arProperty['DIFFERENT'] = $arProperty['HIDDEN'];

    if ($arProperty['DIFFERENT'])
        continue;

    $sValuePrevious = null;

    foreach ($arResult['ITEMS'] as &$arItem) {
        $sCode = $arProperty['CODE'];

        if (empty($sCode))
            if (isset($arProperty['ID'])) {
                $sCode = $arProperty['ID'];
            } else {
                return;
            }

        $sValue = null;

        if ($arProperty['ENTITY'] === 'product') {
            if ($arProperty['TYPE'] === 'field') {
                $sValue = $arItem['FIELDS'][$sCode];
            } else if ($arProperty['TYPE'] === 'property') {
                $sValue = $arItem['DISPLAY_PROPERTIES'][$sCode]['DISPLAY_VALUE'];
            }
        } else if ($arProperty['ENTITY'] === 'offer') {
            if ($arProperty['TYPE'] === 'field') {
                $sValue = $arItem['OFFER_FIELDS'][$sCode];
            } else if ($arProperty['TYPE'] === 'property') {
                $sValue = $arItem['OFFER_DISPLAY_PROPERTIES'][$sCode]['DISPLAY_VALUE'];
            }
        }

        if (Type::isArray($sValue))
            $sValue = implode(', ', $sValue);

        $sValue = Type::toString($sValue);

        if ($sValuePrevious === null) {
            $sValuePrevious = $sValue;
        } else if ($sValue !== $sValuePrevious) {
            $arProperty['DIFFERENT'] = true;
            break;
        }
    }
}

unset(
    $arField,
    $arFields,
    $arProperty,
    $arProperties,
    $arItem,
    $sCode,
    $sValue,
    $sValuePrevious
);