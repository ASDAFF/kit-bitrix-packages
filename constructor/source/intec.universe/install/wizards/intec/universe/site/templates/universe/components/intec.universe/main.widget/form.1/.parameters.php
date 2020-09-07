<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arForms = [];
$bBase = false;

if (Loader::includeModule('form')) {
    include('parameters/base.php');
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    include('parameters/lite.php');
} else
    return;

foreach ($rsTemplates as $arTemplate) {
    $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
}

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** BASE */
$arTemplateParameters['WEB_FORM_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

/** VISUAL */
$arTemplateParameters['WEB_FORM_TITLE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_TITLE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($bBase) {
    $arTemplateParameters['WEB_FORM_DESCRIPTION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['WEB_FORM_BACKGROUND'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_BACKGROUND'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'theme' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_BACKGROUND_THEME'),
        'custom' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_BACKGROUND_CUSTOM')
    ],
    'DEFAULT' => 'theme',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['WEB_FORM_BACKGROUND'] == 'custom') {
    $arTemplateParameters['WEB_FORM_BACKGROUND_CUSTOM'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_BACKGROUND_CUSTOM'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['WEB_FORM_BACKGROUND_OPACITY'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_BACKGROUND_OPACITY'),
    'TYPE' => 'STRING'
];
$arTemplateParameters['WEB_FORM_TEXT_COLOR'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_TEXT_COLOR'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'light' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_TEXT_COLOR_LIGHT'),
        'dark' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_TEXT_COLOR_DARK')
    ],
    'DEFAULT' => 'light'
];
$arTemplateParameters['WEB_FORM_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_POSITION_LEFT'),
        'right' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_POSITION_RIGHT'),
        'center' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_POSITION_CENTER')
    ],
    'DEFAULT' => 'right',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['WEB_FORM_POSITION'] != 'center') {
    $arTemplateParameters['WEB_FORM_ADDITIONAL_PICTURE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['WEB_FORM_ADDITIONAL_PICTURE_SHOW'] == 'Y') {
        $arTemplateParameters['WEB_FORM_ADDITIONAL_PICTURE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#TEMPLATE_PATH#/images/picture.png'
        ];
        $arTemplateParameters['WEB_FORM_ADDITIONAL_PICTURE_HORIZONTAL'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_HORIZONTAL'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_HORIZONTAL_LEFT'),
                'center' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_HORIZONTAL_CENTER'),
                'right' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_HORIZONTAL_RIGHT')
            ],
            'DEFAULT' => 'center'
        ];
        $arTemplateParameters['WEB_FORM_ADDITIONAL_PICTURE_VERTICAL'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_VERTICAL'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'top' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_VERTICAL_TOP'),
                'center' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_VERTICAL_CENTER'),
                'bottom' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_VERTICAL_BOTTOM')
            ],
            'DEFAULT' => 'center'
        ];
        $arTemplateParameters['WEB_FORM_ADDITIONAL_PICTURE_SIZE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_SIZE'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'cover' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_SIZE_COVER'),
                'contain' => Loc::getMessage('C_WIDGET_FORM1_WEB_FORM_ADDITIONAL_PICTURE_SIZE_CONTAIN')
            ],
            'DEFAULT' => 'contain'
        ];
    }
}

$arTemplateParameters['BLOCK_BACKGROUND'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_BLOCK_BACKGROUND'),
    'TYPE' => 'STRING',
    'DEFAULT' => '#TEMPLATE_PATH#/images/bg.jpg'
];

$arTemplateParameters['BLOCK_BACKGROUND_PARALLAX_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM1_BLOCK_BACKGROUND_PARALLAX_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BLOCK_BACKGROUND_PARALLAX_USE'] == 'Y') {
    $arTemplateParameters['BLOCK_BACKGROUND_PARALLAX_RATIO'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM1_BLOCK_BACKGROUND_PARALLAX_RATIO'),
        'TYPE' => 'STRING',
        'DEFAULT' => 10
    ];
}