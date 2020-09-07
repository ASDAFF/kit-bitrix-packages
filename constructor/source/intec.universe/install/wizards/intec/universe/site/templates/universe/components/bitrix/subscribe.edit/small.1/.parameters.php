<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('subscribe'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arRubricsFilter = ['VISIBLE' => 'Y'];

if ($arCurrentValues['SHOW_HIDDEN'] === 'Y')
    $arRubricsFilter = [];

$arRubrics = Arrays::fromDBResult(
    CRubric::GetList(['SORT' => 'ASC'], $arRubricsFilter)
)->indexBy('ID');

$arTemplateParameters = [];

$arTemplateParameters['SHOW_HIDDEN'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_RUBRICSHIDDEN_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];
$arTemplateParameters['RUBRICS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_RUBRICS'),
    'TYPE' => 'LIST',
    'VALUES' => $arRubrics->asArray(function ($id, $value) {
        if ($value['VISIBLE'] === 'N')
            $value['NAME'] = $value['NAME'].' '.Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_RUBRICS_NAME_HIDDEN');

        return [
            'key' => $value['ID'],
            'value' => '['.$value['ID'].'] '.$value['NAME']
        ];
    }),
    'MULTIPLE' => 'Y',
    'SIZE' => 5,
    'ADDITIONAL_VALUES' => 'Y'
];
$arTemplateParameters['RUBRICS_HIDDEN_USE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_RUBRICS_HIDDEN_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['RUBRICS_HIDDEN_USE'] === 'Y') {
    $arTemplateParameters['RUBRICS_HIDDEN'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_RUBRICS_HIDDEN'),
        'TYPE' => 'LIST',
        'VALUES' => $arRubrics->asArray(function ($id, $value) {
            if ($value['VISIBLE'] === 'N')
                $value['NAME'] = $value['NAME'].' '.Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_RUBRICS_NAME_HIDDEN');

            return [
                'key' => $value['ID'],
                'value' => '[' . $value['ID'].'] '.$value['NAME']
            ];
        }),
        'MULTIPLE' => 'Y',
        'SIZE' => 5,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
    $arTemplateParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_HEADER_POSITION_LEFT'),
            'center' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_HEADER_POSITION_CENTER'),
            'right' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_HEADER_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arTemplateParameters['HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_HEADER_TEXT_DEFAULT')
    ];
}

$arTemplateParameters['SHOW_AUTH_LINKS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_SHOW_AUTH_LINKS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SHOW_AUTH_LINKS'] === 'Y') {
    $arTemplateParameters['AUTH_URL'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_AUTH_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '#SITE_DIR#personal/profile/'
    ];
}

$arTemplateParameters['USER_CONSENT'] = [];