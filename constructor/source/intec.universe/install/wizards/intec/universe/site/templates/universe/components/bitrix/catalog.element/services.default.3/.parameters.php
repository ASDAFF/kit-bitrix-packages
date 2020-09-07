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

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

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

        if ($arProperty['PROPERTY_TYPE'] === 'F' && empty($arProperty['USER_TYPE']))
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

    $hPropertiesNumber = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'N' && $arProperty['MULTIPLE'] !== 'Y')
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

    $hPropertiesList = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && empty($arProperty['USER_TYPE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arTemplateParameters['PROPERTY_PRICE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_PROPERTY_PRICE'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertiesNumber),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_CURRENCY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_PROPERTY_CURRENCY'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertiesList),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['CURRENCY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_CURRENCY'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['PROPERTY_PRICE_FORMAT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_PROPERTY_PRICE_FORMAT'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertiesString),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PRICE_FORMAT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_PRICE_FORMAT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '#VALUE# #CURRENCY#'
    ];

    $arTemplateParameters['DESCRIPTION_HEADER'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_DESCRIPTION_HEADER'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_DESCRIPTION_HEADER_DEFAULT')
    ];

    $arTemplateParameters['DESCRIPTION_PROPERTY_HEADER'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_DESCRIPTION_PROPERTY_HEADER'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertiesString),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    if ($arBlocks['banner']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_BANNER_WIDE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_WIDE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BANNER_HEIGHT'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_HEIGHT'),
            'TYPE' => 'LIST',
            'VALUES' => [
                '350px' => '350px',
                '400px' => '400px',
                '450px' => '450px',
                '500px' => '500px'
            ],
            'DEFAULT' => '400px',
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BANNER_SPLIT'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_SPLIT'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_BANNER_PRICE_TITLE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_PRICE_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_PRICE_TITLE_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_BANNER_TEXT_SHOW'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_TEXT_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['BLOCKS_BANNER_TEXT_SHOW'] === 'Y' && $arCurrentValues['BLOCKS_BANNER_SPLIT'] === 'Y') {
            $arTemplateParameters['BLOCKS_BANNER_TEXT_HEADER_SHOW'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_TEXT_HEADER_SHOW'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ];

            $arTemplateParameters['BLOCKS_BANNER_TEXT_POSITION'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_TEXT_POSITION'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    'inside' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_TEXT_POSITION_INSIDE'),
                    'outside' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_TEXT_POSITION_OUTSIDE')
                ],
                'DEFAULT' => 'inside'
            ];
        }

        $arTemplateParameters['BLOCKS_BANNER_PROPERTY_ORDER_BUTTON_SHOW'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_PROPERTY_ORDER_BUTTON_SHOW'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesBoolean),
            'REFRESH' => 'Y',
            'ADDITIONAL_VALUES' => 'Y'
        ];

        if (!empty($arCurrentValues['BLOCKS_BANNER_PROPERTY_ORDER_BUTTON_SHOW'])) {
            if (Loader::includeModule('form')) {
                $rsForms = CForm::GetList(
                    $by = 'sort',
                    $order = 'asc',
                    [],
                    $filtered = false
                );

                while ($arForm = $rsForms->Fetch())
                    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];

                if (!empty($arCurrentValues['BLOCKS_BANNER_ORDER_FORM_ID'])) {
                    $arFields = [];
                    $rsFields = CFormField::GetList(
                        $arCurrentValues['BLOCKS_BANNER_ORDER_FORM_ID'],
                        'N',
                        $by = null,
                        $asc = null,
                        [
                            'ACTIVE' => 'Y'
                        ],
                        $filtered = false
                    );

                    while ($arField = $rsFields->GetNext()) {
                        $rsAnswers = CFormAnswer::GetList(
                            $arField['ID'],
                            $sort = '',
                            $order = '',
                            [],
                            $filtered = false
                        );

                        while ($arAnswer = $rsAnswers->GetNext()) {
                            $sType = $arAnswer['FIELD_TYPE'];

                            if (empty($sType))
                                continue;

                            $sId = 'form_' . $sType . '_' . $arAnswer['ID'];
                            $arFields[$sId] = '[' . $arAnswer['ID'] . '] ' . $arField['TITLE'];
                        }
                    }

                    unset($arField);
                }

                $rsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new', $siteTemplate);

            } else if (Loader::includeModule('intec.startshop')) {
                $rsForms = CStartShopForm::GetList();

                while ($arForm = $rsForms->Fetch())
                    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

                if (!empty($arCurrentValues['BLOCKS_BANNER_ORDER_FORM_ID']))
                    $arFields = Arrays::fromDBResult(CStartShopFormProperty::GetList([], [
                        'FORM' => $arCurrentValues['BLOCKS_BANNER_ORDER_FORM_ID']
                    ]))->asArray(function ($iIndex, $arField) {
                        return [
                            'key' => $arField['ID'],
                            'value' => '['.$arField['ID'].'] '.$arField['LANG'][LANGUAGE_ID]['NAME']
                        ];
                    });

                $rsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new', $siteTemplate);
            } else {
                return;
            }

            foreach ($rsTemplates as $arTemplate) {
                $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
            }

            $arTemplateParameters['BLOCKS_BANNER_ORDER_BUTTON_TEXT'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_ORDER_BUTTON_TEXT'),
                'TYPE' => 'STRING',
                'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_ORDER_BUTTON_TEXT_DEFAULT')
            ];

            $arTemplateParameters['BLOCKS_BANNER_ORDER_FORM_ID'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_ORDER_FORM_ID'),
                'TYPE' => 'LIST',
                'VALUES' => $arForms,
                'REFRESH' => 'Y'
            ];

            $arTemplateParameters['BLOCKS_BANNER_ORDER_FORM_TEMPLATE'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_ORDER_FORM_TEMPLATE'),
                'TYPE' => 'LIST',
                'VALUES' => $arTemplates,
                'DEFAULT' => '.default'
            ];

            $arTemplateParameters['BLOCKS_BANNER_ORDER_FORM_SERVICE'] = [
                'PARENT' => 'BASE',
                'TYPE' => 'LIST',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_ORDER_FORM_SERVICE'),
                'VALUES' => $arFields,
                'ADDITIONAL_VALUES' => 'Y'
            ];

            unset($arFields);

            $arTemplateParameters['BLOCKS_BANNER_ORDER_FORM_CONSENT'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_BANNER_ORDER_FORM_CONSENT'),
                'TYPE' => 'STRING'
            ];
        }
    }

    if ($arBlocks['description.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_DESCRIPTION_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_DESCRIPTION_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
        ];
    }

    if ($arBlocks['icons.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_ICONS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_ICONS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_ICONS_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
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

        $arTemplateParameters['BLOCKS_ICONS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.advantages',
            'template.16',
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
            'template.16',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_ICONS_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_ICONS_1').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['form.1']['ACTIVE']) {
        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.widget',
            'form.5',
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
            'intec.universe:main.widget',
            'form.5',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_FORM_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_FORM_1').' '.$arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        ));
    }

    if ($arBlocks['gallery.1']['ACTIVE']) {
        $arBlockIBlock = null;

        if (!empty($arCurrentValues['BLOCKS_GALLERY_1_IBLOCK_ID']))
            $arBlockIBlock = $arIBlocks->get($arCurrentValues['BLOCKS_GALLERY_1_IBLOCK_ID']);

        $arTemplateParameters['BLOCKS_GALLERY_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_GALLERY_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_GALLERY_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_GALLERY_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_GALLERY_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_GALLERY_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_GALLERY_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
        ];

        $arTemplateParameters['BLOCKS_GALLERY_1_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_GALLERY_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_GALLERY_1_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_GALLERY_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) use (&$arCurrentValues) {
                if (!empty($arCurrentValues['BLOCKS_GALLERY_1_IBLOCK_TYPE']))
                    if ($arIBlock['IBLOCK_TYPE_ID'] != $arCurrentValues['BLOCKS_GALLERY_1_IBLOCK_TYPE'])
                        return ['skip' => true];

                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_GALLERY_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_GALLERY_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        unset($arBlockIBlock);
    }

    if ($arBlocks['properties.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_PROPERTIES_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROPERTIES_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROPERTIES_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_PROPERTIES_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROPERTIES_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PROPERTIES_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROPERTIES_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
        ];
    }

    if ($arBlocks['documents.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_DOCUMENTS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_DOCUMENTS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_DOCUMENTS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_DOCUMENTS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_DOCUMENTS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_DOCUMENTS_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_DOCUMENTS_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
        ];

        $arTemplateParameters['BLOCKS_DOCUMENTS_1_PROPERTY_FILES'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_DOCUMENTS_1_PROPERTY_FILES'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesFile),
            'ADDITIONAL_VALUES' => 'Y'
        ];
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

        $arTemplateParameters['BLOCKS_VIDEOS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_VIDEOS_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
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

        $arTemplateParameters['BLOCKS_VIDEOS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_VIDEOS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        unset($arBlockIBlock);
    }

    if ($arBlocks['projects.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_PROJECTS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_PROJECTS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PROJECTS_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
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

        $arTemplateParameters['BLOCKS_PROJECTS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PROJECTS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
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

        $arTemplateParameters['BLOCKS_REVIEWS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_REVIEWS_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
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

        $arTemplateParameters['BLOCKS_REVIEWS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_REVIEWS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
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

        $arTemplateParameters['BLOCKS_SERVICES_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
        ];

        $arTemplateParameters['BLOCKS_SERVICES_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:iblock.elements',
            'tiles.landing.3',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_SERVICES_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_SERVICES_1').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'SORT_BY',
                    'SORT_ORDER'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));
    }

    if ($arBlocks['products.1']['ACTIVE']) {
        $arTemplateParameters['BLOCKS_PRODUCTS_1_HEADER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PRODUCTS_1_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PRODUCTS_1_HEADER_DEFAULT')
        ];

        $arTemplateParameters['BLOCKS_PRODUCTS_1_PROPERTY_HEADER'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PRODUCTS_1_PROPERTY_HEADER'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesString),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['BLOCKS_PRODUCTS_1_HEADER_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PRODUCTS_1_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_LEFT'),
                'center' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_HEADER_POSITION_CENTER')
            ],
            'DEFAULT' => 'left'
        ];

        $arTemplateParameters['BLOCKS_PRODUCTS_1_PROPERTY_ELEMENTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PRODUCTS_1_PROPERTY_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperties->asArray($hPropertiesElements),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'bitrix:catalog.section',
            'products.small.1',
            $siteTemplate,
            $arCurrentValues,
            'BLOCKS_PRODUCTS_1_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_BLOCKS_PRODUCTS_1').' '.$arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'IBLOCK_TYPE',
                    'IBLOCK_ID',
                    'PRICE_CODE'

                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ));
    }
}