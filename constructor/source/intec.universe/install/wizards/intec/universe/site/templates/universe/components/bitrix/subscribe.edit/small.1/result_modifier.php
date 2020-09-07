<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

global $USER;

$bAuthorised = $USER->IsAuthorized();

$arParams = ArrayHelper::merge([
    'RUBRICS' => [],
    'RUBRICS_HIDDEN_USE' => 'N',
    'RUBRICS_HIDDEN' => [],
    'HEADER_SHOW' => 'N',
    'HEADER_POSITION' => 'center',
    'HEADER_TEXT' => null,
    'USER_CONSENT' => 'N',
    'USER_CONSENT_ID' => null,
    'USER_CONSENT_IS_CHECKED' => 'N',
    'USER_CONSENT_IS_LOADED' => 'N',
    'SHOW_AUTH_LINKS' => 'N',
    'AUTH_URL' => null
], $arParams);

$arParams['RUBRICS'] = array_filter($arParams['RUBRICS']);
$arParams['RUBRICS_HIDDEN'] = array_filter($arParams['RUBRICS_HIDDEN']);

$arVisual = [
    'HEADER' => [
        'SHOW' => $arParams['HEADER_SHOW'] === 'Y',
        'POSITION' => ArrayHelper::fromRange([
            'center',
            'left',
            'right'
        ], $arParams['HEADER_POSITION']),
        'TEXT' => Html::stripTags($arParams['~HEADER_TEXT'], ['br'])
    ],
    'CONSENT' => [
        'SHOW' => $arParams['USER_CONSENT'] === 'Y',
        'ID' => $arParams['USER_CONSENT_ID'],
        'CHECKED' => $arParams['USER_CONSENT_IS_CHECKED'],
        'LOADED' => $arParams['USER_CONSENT_IS_LOADED']
    ],
    'AUTHORISATION' => [
        'SHOW' => $arParams['SHOW_AUTH_LINKS'] === 'Y' && !$bAuthorised,
        'URL' => $arParams['AUTH_URL']
    ]
];

if ($arVisual['HEADER']['SHOW'] && empty($arVisual['HEADER']['TEXT']))
    $arVisual['HEADER']['SHOW'] = false;

if ($arVisual['CONSENT']['SHOW'] && empty($arVisual['CONSENT']['ID']))
    $arVisual['CONSENT']['SHOW'] = false;

if ($arVisual['AUTHORISATION']['SHOW'] && empty($arVisual['AUTHORISATION']['URL']))
    $arVisual['AUTHORISATION']['SHOW'] = false;

if (!empty($arVisual['AUTHORISATION']['URL']))
    $arVisual['AUTHORISATION']['URL'] = StringHelper::replaceMacros(
        $arVisual['AUTHORISATION']['URL'],
        ['SITE_DIR' => SITE_DIR]
    );

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arResult['DATA'] = [
    'ACCESS' => $arParams['ALLOW_ANONYMOUS'] === 'Y' || $bAuthorised,
    'SUBSCRIBED' => !empty($arResult['SUBSCRIPTION']) && $arResult['ID'] > 0,
    'CONFIRMED' => ArrayHelper::getValue($arResult, ['SUBSCRIPTION', 'CONFIRMED']) === 'Y',
    'ID' => ArrayHelper::getValue($arResult, ['SUBSCRIPTION', 'ID']),
    'FORMAT' => ArrayHelper::getValue($arResult, ['SUBSCRIPTION', 'FORMAT'], 'html'),
    'ACTION' => 'Add',
    'EMAIL' => ArrayHelper::getValue($arResult, ['SUBSCRIPTION', 'EMAIL']),
    'RUBRICS' => [
        'SELECTED' => [],
        'ACTIVE' => [],
        'HIDDEN' => []
    ]
];

if (!$arResult['DATA']['ACCESS'])
    $arResult['ERROR'][] = Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_ACCESS');

if ($arResult['DATA']['SUBSCRIBED'] && $arResult['ID'] != 0)
    $arResult['DATA']['ACTION'] = 'Update';

if (empty($arResult['DATA']['EMAIL']))
    $arResult['DATA']['EMAIL'] = $arResult['REQUEST']['EMAIL'];

if (!empty($arParams['RUBRICS'])) {
    foreach ($arResult['RUBRICS'] as &$arRubric) {
        if (ArrayHelper::isIn($arRubric['ID'], $arParams['RUBRICS']))
            $arResult['DATA']['RUBRICS']['SELECTED'][] = &$arRubric;
    }
} else {
    foreach ($arResult['RUBRICS'] as &$arRubric) {
        $arResult['DATA']['RUBRICS']['SELECTED'][$arRubric['ID']] = &$arRubric;
    }
}

unset($arRubric);

if ($arResult['DATA']['SUBSCRIBED']) {
    foreach ($arResult['RUBRICS'] as &$arRubric) {
        if ($arRubric['CHECKED']) {
            if (!ArrayHelper::isIn($arRubric['ID'], $arParams['RUBRICS']))
                $arResult['DATA']['RUBRICS']['ACTIVE'][$arRubric['ID']] = &$arRubric;

            if (!$arResult['DATA']['CONFIRMED'])
                $arResult['DATA']['RUBRICS']['ACTIVE'][$arRubric['ID']] = &$arRubric;
        }
    }

    unset($arRubric);
}

if ($arParams['RUBRICS_HIDDEN_USE'] === 'Y') {
    if (!empty($arParams['RUBRICS']) && !empty($arParams['RUBRICS_HIDDEN'])) {
        foreach ($arResult['RUBRICS'] as &$arRubric) {
            if (ArrayHelper::isIn($arRubric['ID'], $arParams['RUBRICS_HIDDEN'])) {
                if (!ArrayHelper::isIn($arRubric['ID'], $arParams['RUBRICS']) && !ArrayHelper::keyExists($arRubric['ID'], $arResult['DATA']['RUBRICS']['ACTIVE']))
                    $arResult['DATA']['RUBRICS']['HIDDEN'][$arRubric['ID']] = &$arRubric;
            }
        }

        unset($arRubric);
    }
}

unset($bAuthorised);