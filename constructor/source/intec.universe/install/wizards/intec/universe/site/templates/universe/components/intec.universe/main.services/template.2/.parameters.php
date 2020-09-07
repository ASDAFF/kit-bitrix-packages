<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

/** Получение списка форм и шаблонов для них */
$arForms = [];
$arFormFields = [];
$rsTemplates = [];
$arTemplates = [];

if ($arCurrentValues['BUTTON_SHOW'] === 'Y' && $arCurrentValues['BUTTON_TYPE'] === 'order') {
    if (Loader::includeModule('form'))
        include('parameters/base.php');
    elseif (Loader::includeModule('intec.startshop'))
        include('parameters/lite.php');
    else
        return;

    foreach ($rsTemplates as $arTemplate)
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'] . (!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
}

/** Параметры шаблона */
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyNumber = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'N' || $arProperty['PROPERTY_TYPE'] === 'S')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyNumber);

    $arTemplateParameters['PROPERTY_PRICE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_PROPERTY_PRICE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['TEMPLATE_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_TEMPLATE_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'mosaic' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_MOSAIC'),
        'blocks' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BLOCKS')
    ],
    'DEFAULT' => 'mosaic',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TEMPLATE_VIEW'] === 'blocks') {
    $arTemplateParameters['COLUMNS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_COLUMNS'),
        'TYPE' => 'LIST',
        'VALUES' => [
            2 => '2',
            3 => '3'
        ],
        'DEFAULT' => 3
    ];
}

if (!empty($arCurrentValues['PROPERTY_PRICE'])) {
    $arTemplateParameters['PRICE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_PRICE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['BUTTON_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BUTTON_SHOW'] === 'Y') {
    $arTemplateParameters['BUTTON_TYPE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'order' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_TYPE_ORDER'),
            'detail' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_TYPE_DETAIL')
        ],
        'DEFAULT' => 'detail',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BUTTON_TYPE'] === 'order') {
        $arTemplateParameters['BUTTON_FORM_ID'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_FORM_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arForms,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['BUTTON_FORM_ID'])) {
            $arTemplateParameters['FORM_FIELD'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FORM_FIELD'),
                'TYPE' => 'LIST',
                'VALUES' => $arFormFields
            ];
        }

        $arTemplateParameters['FORM_TEMPLATE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'DEFAULT' => '.default'
        ];
        $arTemplateParameters['FORM_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FORM_TITLE_DEFAULT')
        ];
        $arTemplateParameters['CONSENT_URL'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_CONSENT_URL'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#SITE_DIR#company/consent/'
        ];
    }

    $arTemplateParameters['BUTTON_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_TEXT_DEFAULT_DETAIL')
    ];

    if ($arCurrentValues['BUTTON_TYPE'] === 'order') {
        $arTemplateParameters['BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_BUTTON_TEXT_DEFAULT_ORDER')
        ];
    }
}

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] === 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_2_FOOTER_BUTTON_DEFAULT')
        ];
    }
}