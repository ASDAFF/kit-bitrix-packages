<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['TITLE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_FORM_TEMPLATE_1_TITLE_DEFAULT')
];
$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_FORM_TEMPLATE_1_DESCRIPTION_TEXT_DEFAULT')
    ];
}

if (!empty($arCurrentValues['ID'])) {
    $arTemplateParameters['BUTTON_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BUTTON_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_FORM_TEMPLATE_1_BUTTON_TEXT_DEFAULT')
    ];
}

$arTemplateParameters['THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'light' => Loc::getMessage('C_FORM_TEMPLATE_1_THEME_LIGHT'),
        'dark' => Loc::getMessage('C_FORM_TEMPLATE_1_THEME_DARK')
    ],
    'DEFAULT' => 'dark'
];
$arTemplateParameters['VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_FORM_TEMPLATE_1_VIEW_LEFT'),
        'right' => Loc::getMessage('C_FORM_TEMPLATE_1_VIEW_RIGHT'),
        'vertical' => Loc::getMessage('C_FORM_TEMPLATE_1_VIEW_VERTICAL'),
    ],
    'DEFAULT' => 'left',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['VIEW'] === 'vertical') {
    $arTemplateParameters['ALIGN_HORIZONTAL'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_ALIGN_HORIZONTAL'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_FORM_TEMPLATE_1_ALIGN_HORIZONTAL_LEFT'),
            'center' => Loc::getMessage('C_FORM_TEMPLATE_1_ALIGN_HORIZONTAL_CENTER'),
            'right' => Loc::getMessage('C_FORM_TEMPLATE_1_ALIGN_HORIZONTAL_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

$arTemplateParameters['BACKGROUND_COLOR'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_COLOR'),
    'TYPE' => 'STRING',
    'DEFAULT' => '#f4f4f4'
];
$arTemplateParameters['BACKGROUND_IMAGE_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BACKGROUND_IMAGE_USE'] === 'Y') {
    $arTemplateParameters['BACKGROUND_IMAGE_PATH'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_PATH'),
        'TYPE' => 'STRING',
        'DEFAULT' => '#TEMPLATE_PATH#images/bg.png'
    ];
    $arTemplateParameters['BACKGROUND_IMAGE_HORIZONTAL'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_HORIZONTAL'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_HORIZONTAL_LEFT'),
            'center' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_HORIZONTAL_CENTER'),
            'right' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_HORIZONTAL_RIGHT'),
        ],
        'DEFAULT' => 'center'
    ];
    $arTemplateParameters['BACKGROUND_IMAGE_VERTICAL'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_VERTICAL'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'top' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_VERTICAL_TOP'),
            'center' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_VERTICAL_CENTER'),
            'bottom' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_VERTICAL_BOTTOM'),
        ],
        'DEFAULT' => 'center'
    ];
    $arTemplateParameters['BACKGROUND_IMAGE_SIZE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_SIZE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'cover' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_SIZE_COVER'),
            'contain' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_SIZE_CONTAIN')
        ],
        'DEFAULT' => 'cover',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BACKGROUND_IMAGE_SIZE'] === 'contain') {
        $arTemplateParameters['BACKGROUND_IMAGE_REPEAT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_REPEAT'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'no-repeat' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_REPEAT_NO'),
                'repeat' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_REPEAT_YES'),
                'repeat-x' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_REPEAT_X'),
                'repeat-y' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_REPEAT_Y'),
            ],
            'DEFAULT' => 'no-repeat'
        ];
    }

    $arTemplateParameters['BACKGROUND_IMAGE_PARALLAX_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_PARALLAX_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BACKGROUND_IMAGE_PARALLAX_USE'] === 'Y') {
        $arTemplateParameters['BACKGROUND_IMAGE_PARALLAX_RATIO'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_FORM_TEMPLATE_1_BACKGROUND_IMAGE_PARALLAX_RATIO'),
            'TYPE' => 'STRING',
            'DEFAULT' => '20'
        ];
    }
}