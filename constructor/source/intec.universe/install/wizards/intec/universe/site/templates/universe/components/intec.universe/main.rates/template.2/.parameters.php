<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] !== 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyList = $arProperties->asArray($hPropertyList);

    $arTemplateParameters['PROPERTY_PRICE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_PROPERTY_PRICE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_CURRENCY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_PROPERTY_CURRENCY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        3 => '3',
        4 => '4'
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['TABS_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_TABS_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TABS_USE'] === 'Y') {
    $arTemplateParameters['TABS_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_TABS_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}
$arTemplateParameters['TABS_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_TABS_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        1 => '1',
        2 => '2'
    ],
    'DEFAULT' => 1
];

$arTemplateParameters['PROPERTIES_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_PROPERTIES_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_PRICE'])) {
    $arTemplateParameters['PRICE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_PRICE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['ORDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_USE'),
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
        'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['ORDER_FORM_ID'])) {
        $arTemplateParameters['ORDER_FORM_TEMPLATE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'ADDITIONAL_VALUES' => 'Y',
            'DEFAULT' => '.default'
        ];
        $arTemplateParameters['ORDER_FORM_FIELD'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_FORM_FIELD'),
            'TYPE' => 'LIST',
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['ORDER_FORM_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_FORM_TITLE_DEFAULT')
        ];
        $arTemplateParameters['ORDER_FORM_CONSENT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_FORM_CONSENT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#SITE_DIR#company/consent/'
        ];
        $arTemplateParameters['ORDER_BUTTON'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_BUTTON'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_ORDER_BUTTON_DEFAULT')
        ];
    }
}