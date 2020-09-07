<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$request = Core::$app->request;

$this->setFrameMode(true);

$arViewParams = ArrayHelper::getValue($arResult, 'VIEW_PARAMETERS');

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

if ($arResult['isUseCaptcha'] == 'Y') {
    $sCaptchaCode = Html::encode($arResult["CAPTCHACode"]);

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
$sSubmitText = ArrayHelper::getValue($arResult, ['arForm', 'BUTTON']);

?>
<div class="ns-bitrix c-form-result-new c-form-result-new-form-1 intec-content-wrap">
    <?= Html::beginTag('div', $arBlockBg) ?>
        <div class="intec-content">
            <div class="intec-content-wrapper position-<?= $arViewParams['FORM_POSITION'] ?> clearfix">
                <div class="form-result-new-form-wrap theme-<?= $arViewParams['FORM_TEXT_COLOR'] ?>">
                    <?= Html::tag('div', '', $arFormBg) ?>
                    <div class="form-result-new-form-content">
                        <?php if ($arViewParams['TITLE_SHOW'] || $arViewParams['DESCRIPTION_SHOW']) { ?>
                            <div class="form-result-new-form-header">
                                <?php if ($arViewParams['TITLE_SHOW']) { ?>
                                    <div class="form-result-new-title">
                                        <?= $arResult['FORM_TITLE'] ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arViewParams['DESCRIPTION_SHOW']) { ?>
                                    <div class="form-result-new-description">
                                        <?= $arResult['FORM_DESCRIPTION'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?= $arResult['FORM_HEADER'] ?>
                            <?php if ($arResult["isFormErrors"] == 'Y') { ?>
                                <div class="form-result-new-error">
                                    <?= $arResult["FORM_ERRORS_TEXT"] ?>
                                </div>
                            <?php } ?>
                            <?php foreach ($arResult['QUESTIONS'] as $arQuestion) {

                                $bRequired = ArrayHelper::getValue($arQuestion, 'REQUIRED') == 'Y';

                                $sFieldCaption = ArrayHelper::getValue($arQuestion, 'CAPTION');
                                $sFieldCaption = $bRequired ? $sFieldCaption.' '.$sRequiredSign : $sFieldCaption;
                                $sFieldId = ArrayHelper::getValue($arQuestion, ['STRUCTURE', 0, 'ID']);
                                $sFieldType = ArrayHelper::getValue($arQuestion, ['STRUCTURE', 0, 'FIELD_TYPE']);
                                $sFieldName = 'form_'.$sFieldType.'_'.$sFieldId;
                                $sFieldValue = Html::encode($request->post($sFieldName));

                                $arInput = [
                                    'class' => 'form-result-new-input type-'.$sFieldType,
                                    'placeholder' => $sFieldCaption
                                ];

                            ?>
                                <div class="form-result-new-element">
                                    <div class="form-result-new-element-input-wrap">
                                        <?php if ($sFieldType == 'text' || $sFieldType == 'email') { ?>
                                            <?= Html::input($sFieldType, $sFieldName, $sFieldValue, $arInput) ?>
                                        <?php } else if ($sFieldType == 'textarea') { ?>
                                            <?= Html::textarea($sFieldName, $sFieldValue, $arInput) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['isUseCaptcha'] == 'Y') { ?>
                                <div class="form-result-new-captcha">
                                    <div class="form-result-new-captcha-wrap">
                                        <?= Html::hiddenInput('captcha_sid', $sCaptchaCode) ?>
                                        <div class="intec-grid intec-grid-wrap intec-grid-i-h-10 intec-grid-a-v-center">
                                            <div class="captcha-img-wrap intec-grid-item intec-grid-item-500-1">
                                                <?= Html::img('/bitrix/tools/captcha.php?captcha_sid='.$sCaptchaCode, $arCaptcha['img']) ?>
                                            </div>
                                            <div class="intec-grid-item intec-grid-item-500-1">
                                                <?= Html::input('text', 'captcha_word', null, $arCaptcha['input']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-result-new-submit">
                                <?= Html::input('submit', 'web_form_apply', $sSubmitText) ?>
                            </div>
                        <?= $arResult['FORM_FOOTER'] ?>
                    </div>
                </div>
                <?php if ($arViewParams['FORM_ADDITIONAL_PICTURE_SHOW']) { ?>
                    <?= Html::tag('div', '' , $arAdditionalPicture) ?>
                <?php } ?>
            </div>
        </div>
    <?= Html::endTag('div') ?>
</div>