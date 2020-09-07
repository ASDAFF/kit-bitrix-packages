<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arForms = [
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'CONSENT' => $arParams['FORM_CONSENT']
];

$arFormOrder = [
    'USE' => false,
    'ID' => $arParams['FORM_ORDER_ID'],
    'TITLE' => $arParams['FORM_ORDER_TITLE'],
    'FIELD' => $arParams['FORM_ORDER_FIELD']
];

if (!empty($arFormOrder['ID']))
    $arFormOrder['USE'] = true;

$arForms['ORDER'] = $arFormOrder;

$arFormFeedback1 = [
    'USE' => false,
    'ID' => $arParams['FORM_FEEDBACK_1_ID'],
    'FORM' => [
        'TITLE' => $arParams['FORM_FEEDBACK_1_FORM_TITLE']
    ],
    'TITLE' => $arParams['FORM_FEEDBACK_1_BLOCK_TITLE'],
    'DESCRIPTION' => [
        'SHOW' => $arParams['FORM_FEEDBACK_1_BLOCK_DESCRIPTION_SHOW'],
        'TEXT' => $arParams['FORM_FEEDBACK_1_BLOCK_DESCRIPTION_TEXT']
    ],
    'VIEW' => ArrayHelper::fromRange(['left', 'right', 'vertical'], $arParams['FORM_FEEDBACK_1_BLOCK_VIEW']),
    'ALIGN' => [
        'HORIZONTAL' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['FORM_FEEDBACK_1_BLOCK_ALIGN_HORIZONTAL'])
    ],
    'THEME' => ArrayHelper::fromRange(['dark', 'light'], $arParams['FORM_FEEDBACK_1_BLOCK_THEME']),
    'BG' => [
        'COLOR' => $arParams['FORM_FEEDBACK_1_BLOCK_BG_COLOR'],
        'IMAGE' => [
            'SHOW' => $arParams['FORM_FEEDBACK_1_BLOCK_BG_IMAGE_SHOW'],
            'PATH' => $arParams['FORM_FEEDBACK_1_BLOCK_BG_IMAGE_PATH']
        ]
    ],
    'BUTTON' => [
        'TEXT' => $arParams['FORM_FEEDBACK_1_BLOCK_BUTTON_TEXT']
    ]
];

if (!empty($arFormFeedback1['ID'])) {
    $arFormFeedback1['USE'] = true;

    if (empty($arFormFeedback1['FORM']['TITLE']))
        $arFormFeedback1['FORM']['TITLE'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_FORM_TITLE_DEFAULT');

    if (empty($arFormFeedback1['TITLE']))
        $arFormFeedback1['TITLE'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_TITLE_DEFAULT');

    if (empty($arFormFeedback1['BG']['COLOR']))
        $arFormFeedback1['BG']['COLOR'] = '#f4f4f4';

    if (empty($arFormFeedback1['BUTTON']['TEXT']))
        $arFormFeedback1['BUTTON']['TEXT'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_BUTTON_TEXT_DEFAULT');
}

$arForms['FEEDBACK_1'] = $arFormFeedback1;

$arFormFeedback2 = [
    'USE' => false,
    'ID' => $arParams['FORM_FEEDBACK_2_ID'],
    'FORM' => [
        'TITLE' => $arParams['FORM_FEEDBACK_2_FORM_TITLE']
    ],
    'TITLE' => $arParams['FORM_FEEDBACK_2_BLOCK_TITLE'],
    'DESCRIPTION' => [
        'SHOW' => $arParams['FORM_FEEDBACK_2_BLOCK_DESCRIPTION_SHOW'],
        'TEXT' => $arParams['FORM_FEEDBACK_2_BLOCK_DESCRIPTION_TEXT']
    ],
    'VIEW' => ArrayHelper::fromRange(['left', 'right', 'vertical'], $arParams['FORM_FEEDBACK_2_BLOCK_VIEW']),
    'ALIGN' => [
        'HORIZONTAL' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL'])
    ],
    'THEME' => ArrayHelper::fromRange(['dark', 'light'], $arParams['FORM_FEEDBACK_2_BLOCK_THEME']),
    'BG' => [
        'COLOR' => $arParams['FORM_FEEDBACK_2_BLOCK_BG_COLOR'],
        'IMAGE' => [
            'SHOW' => $arParams['FORM_FEEDBACK_2_BLOCK_BG_IMAGE_SHOW'],
            'PATH' => $arParams['FORM_FEEDBACK_2_BLOCK_BG_IMAGE_PATH']
        ]
    ],
    'BUTTON' => [
        'TEXT' => $arParams['FORM_FEEDBACK_2_BLOCK_BUTTON_TEXT']
    ]
];

if (!empty($arFormFeedback2['ID'])) {
    $arFormFeedback2['USE'] = true;

    if (empty($arFormFeedback2['FORM']['TITLE']))
        $arFormFeedback2['FORM']['TITLE'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_FORM_TITLE_DEFAULT');

    if (empty($arFormFeedback2['TITLE']))
        $arFormFeedback2['TITLE'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_TITLE_DEFAULT');

    if (empty($arFormFeedback2['BG']['COLOR']))
        $arFormFeedback2['BG']['COLOR'] = '#f4f4f4';

    if (empty($arFormFeedback2['BUTTON']['TEXT']))
        $arFormFeedback2['BUTTON']['TEXT'] = Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_BUTTON_TEXT_DEFAULT');
}

$arForms['FEEDBACK_2'] = $arFormFeedback2;

$arResult['FORMS'] = $arForms;

unset($arForms, $arFormOrder, $arFormFeedback1, $arFormFeedback2);