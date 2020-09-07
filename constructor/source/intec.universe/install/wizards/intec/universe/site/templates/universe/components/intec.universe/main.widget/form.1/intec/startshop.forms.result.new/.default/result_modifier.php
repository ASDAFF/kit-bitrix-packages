<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N'
], $arParams);

$arMacros = [
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH,
    'TEMPLATE_PATH' => $this->GetFolder(),
    'SITE_DIR' => SITE_DIR
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$bFormTitleShow = ArrayHelper::getValue($arParams, 'WEB_FORM_TITLE_SHOW');
$bFormTitleShow = $bFormTitleShow == 'Y' && !empty($arResult['LANG'][LANGUAGE_ID]['NAME']);

$arBlockBgParallax = [
    'USE' => ArrayHelper::getValue($arParams, 'BLOCK_BACKGROUND_PARALLAX_USE') == 'Y',
    'RATIO' => ArrayHelper::getValue($arParams, 'BLOCK_BACKGROUND_PARALLAX_RATIO')
];

if ($arBlockBgParallax['RATIO'] < 0) $arBlockBgParallax['RATIO'] = 0;
if ($arBlockBgParallax['RATIO'] > 100) $arBlockBgParallax['RATIO'] = 100;

$arBlockBgParallax['RATIO'] = (100 - $arBlockBgParallax['RATIO']) / 100;

$sFormBgCustom = ArrayHelper::getValue($arParams, 'WEB_FORM_BACKGROUND_CUSTOM');
$sFormBgCustom = trim($sFormBgCustom);
$sFormBg = ArrayHelper::getValue($arParams, 'WEB_FORM_BACKGROUND');
$sFormBg = $sFormBg == 'custom' && !empty($sFormBgCustom) ? $sFormBg : 'theme';
$sFormBgOpacity = ArrayHelper::getValue($arParams, 'WEB_FORM_BACKGROUND_OPACITY');
$sFormBgOpacity = trim($sFormBgOpacity);
$sFormBgOpacity = !empty($sFormBgOpacity) ? StringHelper::replace($sFormBgOpacity, ['%' => '']) : null;
$sFormBgOpacity = !empty($sFormBgOpacity) ? 1 - ($sFormBgOpacity / 100) : null;
$sFormPosition = ArrayHelper::getValue($arParams, 'WEB_FORM_POSITION');
$sBlockBg = ArrayHelper::getValue($arParams, 'BLOCK_BACKGROUND');
$sBlockBg = trim($sBlockBg);
$sBlockBg = !empty($sBlockBg) ? StringHelper::replaceMacros($sBlockBg, $arMacros) : null;
$sFormAdditionalPicture = ArrayHelper::getValue($arParams, 'WEB_FORM_ADDITIONAL_PICTURE');
$sFormAdditionalPicture = trim($sFormAdditionalPicture);
$bFormAdditionalPictureShow = ArrayHelper::getValue($arParams, 'WEB_FORM_ADDITIONAL_PICTURE_SHOW') == 'Y';
$bFormAdditionalPictureShow = $bFormAdditionalPictureShow && !empty($sFormAdditionalPicture) && $sFormPosition != 'center';
$sFormAdditionalPicture = $bFormAdditionalPictureShow ? StringHelper::replaceMacros($sFormAdditionalPicture, $arMacros) : null;

$arResult['VIEW_PARAMETERS'] = [
    'TITLE_SHOW' => $bFormTitleShow,
    'FORM_BACKGROUND' => $sFormBg,
    'FORM_BACKGROUND_CUSTOM' => $sFormBgCustom,
    'FORM_BACKGROUND_OPACITY' => $sFormBgOpacity,
    'FORM_TEXT_COLOR' => ArrayHelper::getValue($arParams, 'WEB_FORM_TEXT_COLOR'),
    'FORM_POSITION' => $sFormPosition,
    'FORM_ADDITIONAL_PICTURE_SHOW' => $bFormAdditionalPictureShow,
    'FORM_ADDITIONAL_PICTURE' => $sFormAdditionalPicture,
    'FORM_ADDITIONAL_PICTURE_HORIZONTAL' => ArrayHelper::getValue($arParams, 'WEB_FORM_ADDITIONAL_PICTURE_HORIZONTAL'),
    'FORM_ADDITIONAL_PICTURE_VERTICAL' => ArrayHelper::getValue($arParams, 'WEB_FORM_ADDITIONAL_PICTURE_VERTICAL'),
    'FORM_ADDITIONAL_PICTURE_SIZE' => ArrayHelper::getValue($arParams, 'WEB_FORM_ADDITIONAL_PICTURE_SIZE'),
    'BLOCK_BACKGROUND' => $sBlockBg,
    'BLOCK_BACKGROUND_PARALLAX' => $arBlockBgParallax,
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
];

$arResult['VARIABLES'] = [
    'REQUEST_VARIABLE_ACTION' => Html::encode(ArrayHelper::getValue($arParams, 'REQUEST_VARIABLE_ACTION')),
    'FORM_VARIABLE_CAPTCHA_SID' => Html::encode(ArrayHelper::getValue($arParams, 'FORM_VARIABLE_CAPTCHA_SID')),
    'FORM_VARIABLE_CAPTCHA_CODE' => Html::encode(ArrayHelper::getValue($arParams, 'FORM_VARIABLE_CAPTCHA_CODE'))
];

if (defined('EDITOR'))
    $arResult['VIEW_PARAMETERS']['LAZYLOAD']['USE'] = false;

if ($arResult['VIEW_PARAMETERS']['LAZYLOAD']['USE'])
    $arResult['VIEW_PARAMETERS']['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');