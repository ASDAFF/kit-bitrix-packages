<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\Build;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arMacros = [
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH,
    'TEMPLATE_PATH' => $this->GetFolder(),
    'SITE_DIR' => SITE_DIR
];

$arPosition = ['center', 'left', 'right'];

$arVisual = [
    'TITLE' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'WEB_FORM_TITLE_SHOW') == 'Y' &&
            !empty($arResult['LANG'][LANGUAGE_ID]['NAME']),
        'POSITION' => ArrayHelper::fromRange($arPosition, ArrayHelper::getValue($arParams, 'WEB_FORM_TITLE_POSITION'))
    ],
    'DESCRIPTION' => false,
    'THEME' => ArrayHelper::fromRange(['dark', 'light'], ArrayHelper::getValue($arParams, 'WEB_FORM_THEME')),
    'BUTTON' => [
        'POSITION' => ArrayHelper::fromRange($arPosition, ArrayHelper::getValue($arParams, 'WEB_FORM_BUTTON_POSITION'))
    ],
    'BACKGROUND' => [
        'USE' => ArrayHelper::getValue($arParams, 'WEB_FORM_BACKGROUND_USE') == 'Y',
        'COLOR' => [
            'VALUE' => ArrayHelper::fromRange(['theme', 'custom'], ArrayHelper::getValue($arParams, 'WEB_FORM_BACKGROUND_COLOR')),
            'CUSTOM' => null
        ],
        'OPACITY' => 1
    ],
    'CONSENT' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'WEB_FORM_CONSENT_SHOW'),
        'LINK' => ArrayHelper::getValue($arParams, 'WEB_FORM_CONSENT_LINK')
    ]
];

unset($arPosition);

if ($arVisual['BACKGROUND']['USE']) {
    if ($arVisual['BACKGROUND']['COLOR']['VALUE'] == 'custom') {
        $colorCustom = ArrayHelper::getValue($arParams, 'WEB_FORM_BACKGROUND_COLOR_CUSTOM');

        if (!empty($colorCustom))
            $arVisual['BACKGROUND']['COLOR']['CUSTOM'] = $colorCustom;

        unset($colorCustom);
    }

    $opacity = ArrayHelper::getValue($arParams, 'WEB_FORM_BACKGROUND_OPACITY');

    if (!empty($opacity)) {
        $opacity = StringHelper::replace($opacity, ['%' => '']);

        if ($opacity >= 0 && $opacity <= 100)
            if (Type::isNumeric($opacity)) {
                $opacity = 1 - $opacity / 100;
                $arVisual['BACKGROUND']['OPACITY'] = $opacity;
            }
    }

    unset($opacity);
}

if ($arVisual['CONSENT']['SHOW'] == 'parameters') {
    $oBuild = Build::getCurrent();

    if (!empty($oBuild)) {
        $oPage = $oBuild->getPage();
        $oProperties = $oPage->getProperties();
        $arVisual['CONSENT']['SHOW'] = $oProperties->get('base-consent');

        unset($oPage, $oProperties);
    }

    unset($oBuild);
} elseif ($arVisual['CONSENT']['SHOW'] == 'Y')
    $arVisual['CONSENT']['SHOW'] = true;
elseif ($arVisual['CONSENT']['SHOW'] == 'N')
    $arVisual['CONSENT']['SHOW'] = false;

if ($arVisual['CONSENT']['SHOW']) {
    if (!empty($arVisual['CONSENT']['LINK']))
        $arVisual['CONSENT']['LINK'] = StringHelper::replaceMacros($arVisual['CONSENT']['LINK'], $arMacros);
    else
        $arVisual['CONSENT']['SHOW'] = false;
}

unset($arMacros);

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arResult['VARIABLES'] = [
    'REQUEST_VARIABLE_ACTION' => Html::encode(ArrayHelper::getValue($arParams, 'REQUEST_VARIABLE_ACTION')),
    'FORM_VARIABLE_CAPTCHA_SID' => Html::encode(ArrayHelper::getValue($arParams, 'FORM_VARIABLE_CAPTCHA_SID')),
    'FORM_VARIABLE_CAPTCHA_CODE' => Html::encode(ArrayHelper::getValue($arParams, 'FORM_VARIABLE_CAPTCHA_CODE'))
];