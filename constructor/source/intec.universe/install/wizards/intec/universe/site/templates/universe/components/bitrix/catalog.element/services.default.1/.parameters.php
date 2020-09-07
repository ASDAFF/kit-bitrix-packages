<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Json;
use intec\core\helpers\Type;

CBitrixComponent::includeComponentClass('bitrix:catalog.element');

/**
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([
    'SORT' => 'ASC'
], [
    'ACTIVE' => 'Y'
]))->indexBy('ID');

$arIBlock = null;

if (!empty($arCurrentValues['IBLOCK_ID']))
    $arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$arBlocks = include(__DIR__.'/common/blocks.php');

foreach ($arBlocks as $sBlock => &$arBlock) {
    $arBlock['ACTIVE'] = false;
    $arBlock['SORT'] = -1;

    if (!isset($arBlock['SORTABLE']))
        $arBlock['SORTABLE'] = true;
}

unset($arBlock);

if (isset($arCurrentValues['BLOCKS']) && Type::isArray($arCurrentValues['BLOCKS'])) {
    foreach ($arCurrentValues['BLOCKS'] as $sBlock)
        if (isset($arBlocks[$sBlock]))
            $arBlocks[$sBlock]['ACTIVE'] = true;

    unset($sBlock);
}

$arBlocks = Arrays::from($arBlocks);
$arTemplateParameters['BLOCKS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS'),
    'TYPE' => 'LIST',
    'VALUES' => $arBlocks->asArray(function ($sCode, $arBlock) {
        return [
            'key' => $sCode,
            'value' => $arBlock['NAME']
        ];
    }),
    'MULTIPLE' => 'Y',
    'SIZE' => 16,
    'REFRESH' => 'Y'
];

$arBlocksSortable = $arBlocks->asArray(function ($sCode, $arBlock) {
    if (!$arBlock['ACTIVE'] || !$arBlock['SORTABLE'])
        return ['skip' => true];

    return [
        'key' => $sCode,
        'value' => $arBlock['NAME']
    ];
});

if (!empty($arBlocksSortable)) {
    $arTemplateParameters['BLOCKS_ORDER'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ORDER'),
        'TYPE' => 'CUSTOM',
        'JS_FILE' => CatalogElementComponent::getSettingsScript('/bitrix/components/bitrix/catalog.element', 'dragdrop_order'),
        'JS_EVENT' => 'initDraggableOrderControl',
        'JS_DATA' => Json::encode($arBlocksSortable, 320, true),
        'DEFAULT' => 'description.1,result.1,including.1'
    ];
}

unset($arBlocksSortable);

if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]));

    $hProperties = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
        ];
    };

    $hPropertiesFile = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['MULTIPLE'] !== 'Y' && empty($arProperty['USER_TYPE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesString = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] !== 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesElements = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['MULTIPLE'] === 'Y' && empty($arProperty['USER_TYPE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesBoolean = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] !== 'Y' && empty($arProperty['USER_TYPE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    if ($arBlocks['banner']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_BANNER_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BANNER_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_BANNER_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_BANNER_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BANNER_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.slider',
            'template.1',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BANNER_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'PROPERTY_HEADER',
                    'PROPERTY_DESCRIPTION',
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.slider',
            'template.1',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BANNER_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'BUTTONS_BACK_LINK',
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;
                
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['result.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_RESULT_1_PROPERTY_PICTURE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_1_PROPERTY_PICTURE'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesFile),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RESULT_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RESULT_1_PROPERTY_TEXT'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_1_PROPERTY_TEXT'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['icons.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_ICONS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_ICONS_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_ICONS_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_ICONS_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_ICONS_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_ICONS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_ICONS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.13',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_ICONS_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.13',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_ICONS_1_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['categories.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_CATEGORIES_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_CATEGORIES_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_CATEGORIES_1_DESCRIPTION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1_DESCRIPTION'),
            'TYPE' => 'STRING'
        ];

        $arTemplateParameters['BLOCKS_CATEGORIES_1_PROPERTY_DESCRIPTION'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1_PROPERTY_DESCRIPTION'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_CATEGORIES_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.categories',
            'template.10',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_CATEGORIES_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'IBLOCK_TYPE',
                    'IBLOCK_ID',
                    'LINK_MODE',
                    'PROPERTY_LINK',
                    'SORT_BY',
                    'SORT_ORDER'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.categories',
            'template.10',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_CATEGORIES_1_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_CATEGORIES_1').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['more.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_MORE_1_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_MORE_1_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_MORE_1_BUTTON_TEXT_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_MORE_1_PROPERTY_PICTURE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_MORE_1_PROPERTY_PICTURE'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesFile),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_MORE_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_MORE_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_MORE_1_PROPERTY_TEXT'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_MORE_1_PROPERTY_TEXT'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_MORE_1_PROPERTY_BUTTON_TEXT'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_MORE_1_PROPERTY_BUTTON_TEXT'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_MORE_1_PROPERTY_BUTTON_LINK'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_MORE_1_PROPERTY_BUTTON_LINK'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        unset($arBlockIBlock);
    }

    if ($arBlocks['stages.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_STAGES_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAGES_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAGES_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_STAGES_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAGES_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_STAGES_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAGES_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_STAGES_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_STAGES_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_STAGES_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAGES_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_STAGES_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAGES_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['form.1']['ACTIVE']) {
        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.form',
            'template.4',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_FORM_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FORM_1').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'PROPERTY_HEADER',
                    'PROPERTY_DESCRIPTION',
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.form',
            'template.4',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_FORM_1_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FORM_1').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['staff.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_STAFF_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_STAFF_1_DESCRIPTION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_DESCRIPTION'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ];

        $arTemplateParameters['BLOCKS_STAFF_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_STAFF_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_STAFF_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_STAFF_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_STAFF_1_PROPERTY_DESCRIPTION'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_PROPERTY_DESCRIPTION'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_STAFF_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_STAFF_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_STAFF_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['rates.1']['ACTIVE']) {
        $arBlockIBlock = null;

        if (!empty($arCurrentValues['BLOCKS_RATES_1_IBLOCK_ID']))
            $arBlockIBlock = $arIBlocks->get($arCurrentValues['BLOCKS_RATES_1_IBLOCK_ID']);

        $arTemplateParameters['BLOCKS_RATES_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_RATES_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RATES_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_RATES_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_RATES_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RATES_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RATES_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.rates',
            'template.3',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_RATES_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'PROPERTY_LIST',
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.rates',
            'template.3',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_RATES_1_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_1').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['plan.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_PLAN_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PLAN_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PLAN_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_PLAN_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PLAN_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PLAN_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PLAN_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_PLAN_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_PLAN_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PLAN_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PLAN_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PLAN_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PLAN_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['form.2']['ACTIVE']) {
        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.form',
            'template.6',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_FORM_2_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FORM_2').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'PROPERTY_HEADER',
                    'PROPERTY_DESCRIPTION',
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.form',
            'template.6',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_FORM_2_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FORM_2').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['projects.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_PROJECTS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_PROJECTS_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PROJECTS_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_PROJECTS_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_PROJECTS_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PROJECTS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PROJECTS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['brands.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_BRANDS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_BRANDS_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BRANDS_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_BRANDS_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_BRANDS_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BRANDS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BRANDS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BRANDS_1_LIST_PAGE_URL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_LIST_PAGE_URL'),
            'TYPE' => 'STRING'
        ];

        $arTemplateParameters['BLOCKS_BRANDS_1_BUTTON_TEXT'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' =>  Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BRANDS_1_BUTTON_TEXT_DEFAULT')
        ];
    }

    if ($arBlocks['reviews.1']['ACTIVE']) {
        $arBlockIBlock = null;

        if (!empty($arCurrentValues['BLOCKS_REVIEWS_1_IBLOCK_ID']))
            $arBlockIBlock = $arIBlocks->get($arCurrentValues['BLOCKS_REVIEWS_1_IBLOCK_ID']);

        $arTemplateParameters['BLOCKS_REVIEWS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_REVIEWS_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_REVIEWS_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_REVIEWS_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_REVIEWS_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arBlockIBlock)) {
            $arBlockProperties = Arrays::fromDBResult(CIBlockProperty::GetList([
                'SORT' => 'ASC'
            ], [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arBlockIBlock['ID']
            ]));

            $arTemplateParameters['BLOCKS_REVIEWS_1_PROPERTY_POSITION'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_PROPERTY_POSITION'),
                'TYPE' => 'LIST',
                'VALUES' => $arBlockProperties->asArray($hPropertiesString),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            unset($arBlockProperties);
        }

        $arTemplateParameters['BLOCKS_REVIEWS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_REVIEWS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_REVIEWS_1_PAGE'] = [
            'PARENT' => 'URL_TEMPLATES',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_PAGE'),
            'TYPE' => 'STRING'
        ];

        $arTemplateParameters['BLOCKS_REVIEWS_1_BUTTON_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_BUTTON_SHOW'),
            'TYPE' => 'CHECKBOX'
        ];

        $arTemplateParameters['BLOCKS_REVIEWS_1_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_BUTTON_TEXT_DEFAULT')
        ];

        unset($arBlockIBlock);
    }

    if ($arBlocks['services.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_SERVICES_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_SERVICES_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_1_DESCRIPTION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_DESCRIPTION'),
            'TYPE' => 'STRING'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_1_PROPERTY_DESCRIPTION'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_PROPERTY_DESCRIPTION'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.categories',
            'template.14',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_SERVICES_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'IBLOCK_TYPE',
                    'IBLOCK_ID',
                    'LINK_MODE',
                    'PROPERTY_LINK',
                    'SORT_BY',
                    'SORT_ORDER'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.categories',
            'template.14',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_SERVICES_1_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['videos.1']['ACTIVE']) {
        $arBlockIBlock = null;

        if (!empty($arCurrentValues['BLOCKS_VIDEOS_1_IBLOCK_ID']))
            $arBlockIBlock = $arIBlocks->get($arCurrentValues['BLOCKS_VIDEOS_1_IBLOCK_ID']);

        $arTemplateParameters['BLOCKS_VIDEOS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_VIDEOS_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_VIDEOS_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_VIDEOS_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_VIDEOS_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arBlockIBlock)) {
            $arBlockProperties = Arrays::fromDBResult(CIBlockProperty::GetList([
                'SORT' => 'ASC'
            ], [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arBlockIBlock['ID']
            ]));

            $arTemplateParameters['BLOCKS_VIDEOS_1_PROPERTY_LINK'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_PROPERTY_LINK'),
                'TYPE' => 'LIST',
                'VALUES' => $arBlockProperties->asArray($hPropertiesString),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            unset($arBlockProperties);
        }

        $arTemplateParameters['BLOCKS_VIDEOS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_VIDEOS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        unset($arBlockIBlock);
    }

    if ($arBlocks['faq.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_FAQ_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FAQ_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FAQ_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_FAQ_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FAQ_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_FAQ_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FAQ_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_FAQ_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_FAQ_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_FAQ_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FAQ_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_FAQ_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FAQ_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['services.2']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_SERVICES_2_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_SERVICES_2_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_2_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_SERVICES_2_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_SERVICES_2_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_2_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_2_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.services',
            'template.14',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_SERVICES_2_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.services',
            'template.14',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_SERVICES_2_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_2').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['services.3']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_SERVICES_3_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_SERVICES_3_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_3_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_SERVICES_3_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_SERVICES_3_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_3_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_3_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.services',
            'template.16',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_SERVICES_3_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.services',
            'template.16',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_SERVICES_3_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_3').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['result.2']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_RESULT_2_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_2_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_2_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_RESULT_2_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_2_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RESULT_2_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_2_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_RESULT_2_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_RESULT_2_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RESULT_2_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_2_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RESULT_2_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RESULT_2_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['benefits.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_BENEFITS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_1_DESCRIPTION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_DESCRIPTION'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_BENEFITS_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_BENEFITS_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_1_PROPERTY_DESCRIPTION'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_PROPERTY_DESCRIPTION'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    if ($arBlocks['benefits.2']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_BENEFITS_2_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_2_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_2_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_BENEFITS_2_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_BENEFITS_2_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_2_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_2_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.16',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BENEFITS_2_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.16',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BENEFITS_2_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_2').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['benefits.3']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_BENEFITS_3_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_3_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_3_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_BENEFITS_3_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_BENEFITS_3_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_3_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_3_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.7',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BENEFITS_3_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.7',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BENEFITS_3_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_3').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['benefits.4']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_BENEFITS_4_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_4_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_4_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_BENEFITS_4_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_BENEFITS_4_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_4_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BENEFITS_4_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.11',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BENEFITS_4_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.11',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_BENEFITS_4_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BENEFITS_4').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['rates.2']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_RATES_2_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_RATES_2_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RATES_2_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_RATES_2_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_RATES_2_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RATES_2_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_RATES_2_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.rates',
            'template.2',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_RATES_2_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'PROPERTY_LIST',
                    'SORT_BY',
                    'ORDER_BY'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.rates',
            'template.2',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_RATES_2_',
            function ($sKey, &$arParameter) {
                if (ArrayHelper::isIn($sKey, [
                    'SETTINGS_USE',
                    'LAZYLOAD_USE'
                ])) return false;

                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_RATES_2').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }
}