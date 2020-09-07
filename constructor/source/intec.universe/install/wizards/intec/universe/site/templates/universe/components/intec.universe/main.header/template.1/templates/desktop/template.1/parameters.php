<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

$arReturn = [];

$sSite = false;

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$arMenuTypes = GetMenuTypes($sSite);

if ($arCurrentValues['PHONES_SHOW'] == 'Y') {

}

$arReturn['PHONES_POSITION'] = [
    'PARENT' => 'VISUAL',
    'TYPE' => 'LIST',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_PHONES_POSITION'),
    'VALUES' => [
        'top' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_PHONES_POSITION_TOP'),
        'bottom' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_PHONES_POSITION_BOTTOM')
    ]
];

if ($arCurrentValues['MENU_MAIN_SHOW'] == 'Y') {
    $arReturn['MENU_MAIN_POSITION'] = [
        'PARENT' => 'VISUAL',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_MAIN_POSITION'),
        'VALUES' => [
            'top' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_MAIN_POSITION_TOP'),
            'bottom' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_MAIN_POSITION_BOTTOM')
        ],
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['MENU_MAIN_POSITION'] == 'bottom') {
        $arReturn['MENU_MAIN_TRANSPARENT'] = [
            'PARENT' => 'VISUAL',
            'TYPE' => 'CHECKBOX',
            'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_MAIN_TRANSPARENT')
        ];
    }
}

$arReturn['MENU_INFO_SHOW'] = [
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_SHOW'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['MENU_INFO_SHOW'] == 'Y') {
    $arReturn['MENU_INFO_ROOT'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_ROOT'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'DEFAULT ' => 'left',
        'VALUES' => $arMenuTypes,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arReturn['MENU_INFO_CHILD'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_CHILD'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'DEFAULT ' => 'left',
        'VALUES' => $arMenuTypes,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arReturn['MENU_INFO_LEVEL'] = [
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_MENU_INFO_LEVEL'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'DEFAULT ' => 1,
        'VALUES' => [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
        ],
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

if ($arCurrentValues['SOCIAL_SHOW'] == 'Y') {
    $arReturn['SOCIAL_POSITION'] = [
        'PARENT' => 'VISUAL',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_SOCIAL_POSITION'),
        'VALUES' => [
            'left' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_SOCIAL_POSITION_LEFT'),
            'center' => Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_SOCIAL_POSITION_CENTER')
        ]
    ];
}

return $arReturn;