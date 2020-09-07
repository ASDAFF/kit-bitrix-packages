<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

if (!Loader::includeModule('intec.startshop'))
    return;

/**
 * @var array $arResult
 * @var CMain $APPLICATION
 */

$request = Core::$app->request;

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arViewParams = ArrayHelper::getValue($arResult, 'VIEW_PARAMETERS');
$arVariables = ArrayHelper::getValue($arResult, 'VARIABLES');

$sFormBgTheme = $arViewParams['FORM_BACKGROUND'] == 'theme' ? ' intec-cl-background' : null;
$sFromBgCustom = $arViewParams['FORM_BACKGROUND'] == 'custom' ? $arViewParams['FORM_BACKGROUND_CUSTOM'] : null;

$sBgPicture = $arViewParams['BLOCK_BACKGROUND'];

$arBlockBg = [
    'class' => 'form-result-new-block-bg',
    'data-stellar-background-ratio' => $arViewParams['BLOCK_BACKGROUND_PARALLAX']['USE'] ? $arViewParams['BLOCK_BACKGROUND_PARALLAX']['RATIO'] : null,
    'data' => [
        'lazyload-use' => $arViewParams['LAZYLOAD']['USE'] && !empty($sBgPicture) ? 'true' : 'false',
        'original' => $arViewParams['LAZYLOAD']['USE'] && !empty($sBgPicture) ? $sBgPicture : null
    ],
    'style' => [
        'background-image' => !$arViewParams['LAZYLOAD']['USE'] && !empty($sBgPicture) ? 'url(\''.$sBgPicture.'\')' : null
    ]
];
$arFormBg = [
    'class' => 'form-result-new-form-background'.$sFormBgTheme,
    'style' => [
        'background-color' => $sFromBgCustom,
        'opacity' => $arViewParams['FORM_BACKGROUND_OPACITY']
    ]
];

$arAdditionalPicture = [];

if ($arViewParams['FORM_ADDITIONAL_PICTURE_SHOW']) {
    $arAdditionalPicture = [
        'class' => 'form-result-new-additional-picture',
        'style' => [
            'background-image ' => !$arViewParams['LAZYLOAD']['USE'] ? 'url(\''.$arViewParams['FORM_ADDITIONAL_PICTURE'].'\')' : null,
            'background-position-x' => $arViewParams['FORM_ADDITIONAL_PICTURE_HORIZONTAL'],
            'background-position-y' => $arViewParams['FORM_ADDITIONAL_PICTURE_VERTICAL'],
            'background-size' => $arViewParams['FORM_ADDITIONAL_PICTURE_SIZE']
        ],
        'data' => [
            'lazyload-use' => $arViewParams['LAZYLOAD']['USE'] ? 'true' : 'false',
            'original' => $arViewParams['LAZYLOAD']['USE'] ? $arViewParams['FORM_ADDITIONAL_PICTURE'] : null
        ]
    ];
}

$sCaptchaCode = null;
$arCaptcha = [];

if ($arResult['USE_CAPTCHA'] == 'Y') {
    $sCaptchaCode = $APPLICATION->CaptchaGetCode();

    $arCaptcha = [
        'img' => [
            'width' => 180,
            'height' => 40
        ],
        'input' => [
            'class' => 'form-result-new-input type-text type-captcha',
            'placeholder' => Loc::getMessage('C_FORM_RESULT_NEW_FORM1_CAPTCHA_PLACEHOLDER')
        ]
    ];
}

$sRequiredSign = '*';
$sSubmitText = ArrayHelper::getValue($arResult, ['LANG', LANGUAGE_ID, 'BUTTON']);

$sFormAction = $APPLICATION->GetCurPage();
$sFormId = "form_$sTemplateId";
$arForm = [
    'id' => $sFormId,
    'enctype' => 'multipart/form-data'
];

?>
<div class="ns-intec c-form-result-new c-form-result-new-form-1 intec-content-wrap">
    <?= Html::beginTag('div', $arBlockBg) ?>
        <div class="intec-content">
            <div class="intec-content-wrapper position-<?= $arViewParams['FORM_POSITION'] ?> clearfix">
                <div class="form-result-new-form-wrap theme-<?= $arViewParams['FORM_TEXT_COLOR'] ?>">
                    <?= Html::tag('div', '', $arFormBg) ?>
                    <div class="form-result-new-form-content">
                        <?php if ($arViewParams['TITLE_SHOW']) { ?>
                            <div class="form-result-new-form-header">
                                <div class="form-result-new-title">
                                    <?= $arResult['LANG'][LANGUAGE_ID]['NAME'] ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (($arResult['ERROR']['CODE'] == 0 || $arResult['ERROR']['CODE'] >= 4) && !$arResult['SENT']) { ?>
                            <?= Html::beginForm($sFormAction, 'post', $arForm) ?>
                                <?= Html::hiddenInput($arVariables['REQUEST_VARIABLE_ACTION'], 'send') ?>
                                <?php foreach ($arResult['PROPERTIES'] as $iKey => $arProperty) {

                                    $bRequired = ArrayHelper::getValue($arProperty, 'REQUIRED') == 'Y';

                                    $sFieldName = ArrayHelper::getValue($arProperty, 'CODE');
                                    $sFieldName = Html::encode($sFieldName);
                                    $sFieldCaption = ArrayHelper::getValue($arProperty, ['LANG', LANGUAGE_ID, 'NAME']);
                                    $sFieldCaption = $bRequired ? $sFieldCaption.' '.$sRequiredSign : $sFieldCaption;
                                    $bFieldActive = ArrayHelper::getValue($arProperty, 'READONLY') == 'Y';

                                ?>
                                    <div class="form-result-new-element">
                                        <?php if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_TEXT) { ?>
                                            <div class="form-result-new-element-input-wrap">
                                                <?= Html::input('text', $sFieldName, $request->post($sFieldName), [
                                                    'class' => 'form-result-new-input type-text',
                                                    'disabled' => $bFieldActive ? 'disabled' : null,
                                                    'placeholder' => $sFieldCaption
                                                ]) ?>
                                            </div>
                                            <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                                <?php if (ArrayHelper::keyExists($sFieldName, $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                                    <div class="form-result-new-error">
                                                        <?= Loc::getMessage('C_FORM_RESULT_NEW_FORM1_ERROR_EMPTY') ?>
                                                    </div>
                                                <?php } ?>
                                                <?php if (ArrayHelper::keyExists($sFieldName, $arResult['ERROR']['FIELDS']['INVALID'])) { ?>
                                                    <div class="form-result-new-error">
                                                        <?= Loc::getMessage('C_FORM_RESULT_NEW_FORM1_ERROR_INVALID') ?>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($arProperty['TYPE'] == STARTSHOP_FORM_PROPERTY_TYPE_TEXTAREA) { ?>
                                            <div class="form-result-new-element-input-wrap">
                                                <?= Html::textarea($sFieldName, $request->post($sFieldName), [
                                                    'class' => 'form-result-new-input type-textarea',
                                                    'disabled' => $bFieldActive ? 'disabled' : null,
                                                    'placeholder' => $sFieldCaption
                                                ]) ?>
                                            </div>
                                            <?php if ($arResult['ERROR']['CODE'] == 5) { ?>
                                                <?php if (ArrayHelper::keyExists($sFieldName, $arResult['ERROR']['FIELDS']['EMPTY'])) { ?>
                                                    <div class="form-result-new-error">
                                                        <?= Loc::getMessage('C_FORM_RESULT_NEW_FORM1_ERROR_EMPTY') ?>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['USE_CAPTCHA'] == 'Y') { ?>
                                    <div class="form-result-new-captcha">
                                        <div class="form-result-new-captcha-wrap">
                                            <?= Html::hiddenInput($arVariables['FORM_VARIABLE_CAPTCHA_SID'], $sCaptchaCode) ?>
                                            <div class="intec-grid intec-grid-wrap intec-grid-i-h-10 intec-grid-a-v-center">
                                                <div class="captcha-img-wrap intec-grid-item intec-grid-item-500-1">
                                                    <?= Html::img('/bitrix/tools/captcha.php?captcha_sid='.$sCaptchaCode, $arCaptcha['img']) ?>
                                                </div>
                                                <div class="intec-grid-item intec-grid-item-500-1">
                                                    <?= Html::input('text', $arVariables['FORM_VARIABLE_CAPTCHA_CODE'], null, $arCaptcha['input']) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($arResult['ERROR']['CODE'] == 4) { ?>
                                            <div class="form-result-new-captcha-error">
                                                <?= Loc::getMessage('C_FORM_RESULT_NEW_FORM1_CAPTCHA_ERROR') ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="form-result-new-submit">
                                    <?= Html::input('submit', null, $sSubmitText) ?>
                                </div>
                            <?= Html::endForm() ?>
                        <?php } else if ($arResult['SENT']) { ?>
                            <div class="form-result-new-sent">
                                <?= $arResult['LANG'][LANGUAGE_ID]['SENT'] ?>
                            </div>
                        <?php } else { ?>
                            <div class="form-result-new-message">
                                <div class="form-result-message-error">
                                    <?php if ($arResult['ERROR']['CODE'] == 1) { ?>
                                        <?= Loc::getMessage('C_FORM_RESULT_NEW_FORM1_ERROR_FORM_NOT_EXIST') ?>
                                    <?php } else if ($arResult['ERROR']['CODE'] == 2) {?>
                                        <?= Loc::getMessage('C_FORM_RESULT_NEW_FORM1_ERROR_FORM_UNBOUND') ?>
                                    <?php } else if ($arResult['ERROR']['CODE'] == 3) {?>
                                        <?= Loc::getMessage('C_FORM_RESULT_NEW_FORM1_ERROR_FORM_NO_FIELDS') ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($arViewParams['FORM_ADDITIONAL_PICTURE_SHOW']) { ?>
                    <?= Html::tag('div', '' , $arAdditionalPicture) ?>
                <?php } ?>
            </div>
        </div>
    <?= Html::endTag('div') ?>
</div>
