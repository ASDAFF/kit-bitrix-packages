<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'));

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyString = function ($sKey, $arProperty) {
        if (($arProperty['PROPERTY_TYPE'] === 'S' || $arProperty['PROPERTY_TYPE'] === 'N') && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyList = $arProperties->asArray($hPropertyList);
    $arPropertyString = $arProperties->asArray($hPropertyString);

    $arTemplateParameters['PROPERTY_CATEGORY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_PROPERTY_CATEGORY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PRICE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_PROPERTY_PRICE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyString,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_CATEGORY'])) {
    $arTemplateParameters['CATEGORY_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_CATEGORY_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_PRICE'])) {
    $arTemplateParameters['PRICE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_PRICE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['ORDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ORDER_USE'] === 'Y') {
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

    $arTemplateParameters['ORDER_FORM_ID'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['ORDER_FORM_ID'])) {
        $arTemplateParameters['ORDER_FORM_TEMPLATE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'ADDITIONAL_VALUES' => 'Y',
            'DEFAULT' => '.default'
        ];
        $arTemplateParameters['ORDER_FORM_FIELD'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_FORM_FIELD'),
            'TYPE' => 'LIST',
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['ORDER_FORM_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_FORM_TITLE_DEFAULT')
        ];
        $arTemplateParameters['ORDER_FORM_CONSENT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_FORM_CONSENT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#SITE_DIR#company/consent/'
        ];
        $arTemplateParameters['ORDER_BUTTON'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_BUTTON'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_ORDER_BUTTON_DEFAULT')
        ];
    }
}

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] == 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y')
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_16_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
}