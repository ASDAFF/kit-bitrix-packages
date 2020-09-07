<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

if (!Loader::includeModule('intec.core'))
    return;

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

$arTemplateParameters = [];
$arTemplateParameters['BORDER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_BORDER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

$arTemplateParameters['CITY_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_CITY_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['CITY_SHOW'] === 'Y') {
    $arTemplateParameters['CITY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_CITY'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['ADDRESS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_ADDRESS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ADDRESS_SHOW'] === 'Y') {
    $arTemplateParameters['ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_ADDRESS'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['PHONES_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_PHONES_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PHONES_SHOW'] === 'Y') {
    $arTemplateParameters['PHONES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_PHONES'),
        'TYPE' => 'STRING',
        'MULTIPLE' => 'Y',
    ];
}

$arTemplateParameters['LOGOTYPE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_LOGOTYPE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LOGOTYPE_SHOW'] == 'Y') {
    $arTemplateParameters['LOGOTYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_LOGOTYPE'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['LOGOTYPE_LINK'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_LOGOTYPE_LINK'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['SOCIAL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_SOCIAL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SOCIAL_SHOW'] == 'Y') {
    $arTemplateParameters['SOCIAL_VK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_SOCIAL_VK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_INSTAGRAM'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_SOCIAL_INSTAGRAM'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_FACEBOOK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_SOCIAL_FACEBOOK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_TWITTER'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_SOCIAL_TWITTER'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['AUTHORIZATION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_AUTHORIZATION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['AUTHORIZATION_SHOW'] == 'Y') {
    $arTemplateParameters['PROFILE_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_PROFILE_URL'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['ORDER_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_ORDER_URL'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['DELAY_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_DELAY_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['BASKET_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_BASKET_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BASKET_SHOW'] == 'Y') {
        $arTemplateParameters['BASKET_URL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MENU_MOBILE_2_BASKET_URL'),
            'TYPE' => 'STRING'
        ];
    }

    $arTemplateParameters['COMPARE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_COMPARE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['COMPARE_SHOW'] == 'Y') {
        $arTemplateParameters['COMPARE_URL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MENU_MOBILE_2_COMPARE_URL'),
            'TYPE' => 'STRING'
        ];

        $arTemplateParameters['COMPARE_CODE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MENU_MOBILE_2_COMPARE_CODE'),
            'TYPE' => 'STRING'
        ];

        if (Loader::includeModule('iblock')) {
            $arIBlocksTypes = CIBlockParameters::GetIBlockTypes();

            $arTemplateParameters['COMPARE_IBLOCK_TYPE'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MENU_MOBILE_2_COMPARE_IBLOCK_TYPE'),
                'TYPE' => 'LIST',
                'VALUES' => $arIBlocksTypes,
                'ADDITIONAL_VALUES' => 'Y',
                'REFRESH' => 'Y'
            ];

            $arIBlocks = [];
            $rsIBlocks = CIBlock::GetList([], [
                'ACTIVE' => 'Y',
                'TYPE' => $arCurrentValues['COMPARE_IBLOCK_TYPE']
            ]);

            while ($arIBlock = $rsIBlocks->GetNext())
                $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

            $arTemplateParameters['COMPARE_IBLOCK_ID'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MENU_MOBILE_2_COMPARE_IBLOCK_ID'),
                'TYPE' => 'LIST',
                'VALUES' => $arIBlocks,
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }
}

$arTemplateParameters['SEARCH_SHOW'] = [
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_SEARCH_SHOW'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'bitrix:search.title',
    [
        'input.1',
        'popup.1'
    ],
    $siteTemplate,
    $arCurrentValues,
    'SEARCH_',
    function ($sKey, &$arParameter) {
        $arParameter['NAME'] = Loc::getMessage('C_MENU_MOBILE_2_SEARCH').' '.$arParameter['NAME'];

        if (StringHelper::startsWith($sKey, 'CACHE'))
            return false;

        if (StringHelper::startsWith($sKey, 'COMPOSITE'))
            return false;

        if ($sKey == 'PAGE')
            return false;

        return true;
    }
));

$arTemplateParameters['SEARCH_MODE'] = [
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_SEARCH_MODE'),
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'VALUES' => [
        'site' => Loc::getMessage('C_MENU_MOBILE_2_SEARCH_MODE_SITE'),
        'catalog' => Loc::getMessage('C_MENU_MOBILE_2_SEARCH_MODE_CATALOG')
    ]
];

$arTemplateParameters['SEARCH_URL'] = [
    'NAME'  => Loc::getMessage('C_MENU_MOBILE_2_SEARCH_URL'),
    'PARENT' => 'URL_TEMPLATES',
    'TYPE' => 'STRING'
];

if (Loader::includeModule('intec.regionality')) {
    $arTemplateParameters['REGIONALITY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_2_REGIONALITY_USE'),
        'TYPE' => 'CHECKBOX'
    ];
}

$arTemplateParameters['INFORMATION_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_2_INFORMATION_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'view.1' => Loc::getMessage('C_MENU_MOBILE_2_INFORMATION_VIEW_1'),
        'view.2' => Loc::getMessage('C_MENU_MOBILE_2_INFORMATION_VIEW_2')
    ],
];