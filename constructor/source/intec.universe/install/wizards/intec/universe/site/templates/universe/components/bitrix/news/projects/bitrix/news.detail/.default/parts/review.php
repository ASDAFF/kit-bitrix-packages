<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 * @var string $sTemplateId
 */

$arReview = ArrayHelper::getValue($arResult, 'REVIEW');

if (empty($arReview))
    return;

$sReviewImage = null;

if (!empty($arReview['PREVIEW_PICTURE'])) {
    $sReviewImage = $arReview['PREVIEW_PICTURE'];
} else if (!empty($arReview['DETAIL_PICTURE'])) {
    $sReviewImage = $arReview['DETAIL_PICTURE'];
}

$sReviewImage = CFile::ResizeImageGet($sReviewImage, array(
    'width' => 80,
    'height' => 80
), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

if (!empty($sReviewImage)) {
    $sReviewImage = $sReviewImage['src'];
} else {
    $sReviewImage = null;
}

?>
<div class="project-section project-section-review">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="project-section-title intec-ui-markup-header">
                <?= Loc::getMessage('N_PROJECTS_N_D_DEFAULT_SECTION_REVIEW') ?>
            </div>
            <div class="project-review">
                <div class="project-review-header">
                    <?php if (!empty($sReviewImage)) { ?>
                        <div class="project-review-image">
                            <?= Html::tag('div', '', [
                                'class' => [
                                    'project-review-image-wrapper'
                                ],
                                'data' => [
                                    'lazyload-use' => $arLazyLoad['USE'] ? 'true' : 'false',
                                    'original' => $arLazyLoad['USE'] ? $sReviewImage : null
                                ],
                                'style' => [
                                    'background-image' => !$arLazyLoad['USE'] ? 'url(\''.$sReviewImage.'\')' : null
                                ]
                            ]) ?>
                        </div>
                    <?php } ?>
                    <div class="project-review-name">
                        <?= $arReview['NAME'] ?>
                    </div>
                    <div class="project-review-signature">
                        <?= $arReview['PREVIEW_TEXT'] ?>
                    </div>
                </div>
                <div class="project-review-information">
                    <?= $arReview['DETAIL_TEXT'] ?>
                </div>
            </div>
        </div>
    </div>
</div>

