<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arIBlockTypes = CIBlockParameters::GetIBlockTypes();

    $arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], []))->indexBy('ID');

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyLink = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyAll = function ($sKey, $arProperty) {
        return [
            'key' => $arProperty['CODE'],
            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
        ];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyList = $arProperties->asArray($hPropertyList);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyLink = $arProperties->asArray($hPropertyLink);
    $arPropertyAll = $arProperties->asArray($hPropertyAll);
}

$arTemplateParameters = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_VIDEO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_PROPERTY_VIDEO'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_VIDEO'])) {
        if (!empty($arCurrentValues['VIDEO_IBLOCK_TYPE']))
            $arIBlocksServices = $arIBlocks->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
                if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['VIDEO_IBLOCK_TYPE']) {
                    return [
                        'key' => $arProperty['ID'],
                        'value' => '[' . $arProperty['ID'] . '] ' . $arProperty['NAME']
                    ];
                }

                return ['skip' => true];
            });
        else
            $arIBlocksServices = $arIBlocks->asArray(function ($sKey, $arProperty) {
                return [
                    'key' => $arProperty['ID'],
                    'value' => '[' . $arProperty['ID'] . '] ' . $arProperty['NAME']
                ];
            });

        $arTemplateParameters['VIDEO_IBLOCK_TYPE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_VIDEO_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['VIDEO_IBLOCK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_VIDEO_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksServices,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arPropertiesVideos = Arrays::fromDBResult(CIBlockProperty::GetList([], [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $arCurrentValues['VIDEO_IBLOCK_ID']
        ]))->indexBy('ID');

        $hVideosPropertyText = function ($sKey, $arProperty) {
            if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N' && !$arProperty['USER_TYPE'])
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                ];

            return ['skip' => true];
        };

        $arVideosPropertyText = $arPropertiesVideos->asArray($hVideosPropertyText);

        $arTemplateParameters['VIDEO_PROPERTY_LINK'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_VIDEO_PROPERTY_LINK'),
            'TYPE' => 'LIST',
            'VALUES' => $arVideosPropertyText,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['LIST_VIDEO_SHOW'] = [
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_LIST_VIDEO_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];

        $arTemplateParameters['DETAIL_VIDEO_SHOW'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_DETAIL_VIDEO_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_DOCUMENT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_PROPERTY_DOCUMENT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_DOCUMENT'])) {
        $arTemplateParameters['LIST_DOCUMENT_SHOW'] = [
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_LIST_DOCUMENT_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
        $arTemplateParameters['DETAIL_DOCUMENT_SHOW'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_DETAIL_DOCUMENT_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['PROPERTY_SERVICES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_PROPERTY_SERVICES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_CASES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_PROPERTY_CASES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_CASES'])) {
        $arTemplateParameters['LIST_CASE_SHOW'] = [
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_LIST_CASE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];

        $arTemplateParameters['DETAIL_CASES_SHOW'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_DETAIL_CASES_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];

        $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
            'intec.universe:main.categories',
            'template.14',
            $siteTemplate,
            $arCurrentValues,
            'CASES_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_NEWS_REVIEWS_DETAIL_CASE').' '.$arParameter['NAME'];

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
    }

    $arTemplateParameters['PROPERTY_PERSON_NAME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_PROPERTY_PERSON_NAME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_PERSON_POSITION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_PROPERTY_PERSON_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_SITE_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_PROPERTY_SITE_URL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['DETAIL_TITLE_SHOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_DETAIL_TITLE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_SERVICES'])) {
    $arTemplateParameters['LIST_SERVICES_SHOW'] = [
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_LIST_SERVICES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['DETAIL_SERVICES_SHOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_DETAIL_SERVICES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        'intec.universe:main.categories',
        'template.14',
        $siteTemplate,
        $arCurrentValues,
        'SERVICES_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_REVIEWS_DETAIL_SERVICES').' '.$arParameter['NAME'];

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
}

$arTemplateParameters['FORM_SHOW'] = [
    'PARENT' => 'LIST_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_REVIEWS_FORM_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FORM_SHOW'] === 'Y') {
    /**
     * @var array $arForms - список форм
     * @var array $arFormFields список полей выбранной формы
     */

    if (Loader::includeModule('form'))
        include(__DIR__.'/parameters/base.php');
    elseif (Loader::includeModule('intec.startshop'))
        include(__DIR__.'/parameters/lite.php');
    else
        return;

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate)
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);

    $arTemplateParameters['FORM_ID'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_REVIEWS_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['FORM_ID'])) {
        $arTemplateParameters['FORM_TEMPLATE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'ADDITIONAL_VALUES' => 'Y',
            'DEFAULT' => '.default'
        ];
        $arTemplateParameters['FORM_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_NEWS_REVIEWS_FORM_TITLE_DEFAULT')
        ];
        $arTemplateParameters['FORM_CONSENT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_FORM_CONSENT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#SITE_DIR#company/consent/'
        ];
        $arTemplateParameters['FORM_BUTTON'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_FORM_BUTTON'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_NEWS_REVIEWS_FORM_BUTTON_DEFAULT')
        ];
        $arTemplateParameters['FORM_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_REVIEWS_FORM_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'top' => Loc::getMessage('C_NEWS_REVIEWS_FORM_POSITION_TOP'),
                'bottom' => Loc::getMessage('C_NEWS_REVIEWS_FORM_POSITION_BOTTOM'),
            ],
            'DEFAULT' => 'top'
        ];
    }
}