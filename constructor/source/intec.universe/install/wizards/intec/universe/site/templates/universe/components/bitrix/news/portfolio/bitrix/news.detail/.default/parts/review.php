<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var array $arData
 */

$sPicture = $arData['REVIEW']['VALUE']['PICTURE'];

if (!empty($sPicture)) {
    $sPicture = CFile::ResizeImageGet(
        $sPicture, [
        'width' => 150,
        'height' => 150
    ],
        BX_RESIZE_IMAGE_PROPORTIONAL_ALT
    );

    if (!empty($sPicture))
        $sPicture = $sPicture['src'];
}

if (empty($sPicture))
    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

?>
<div class="news-detail-project-review">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?= Html::beginTag('div', [
                'class' => 'news-detail-project-review-wrapper',
                'data-narrow' => $arVisual['REVIEW']['NARROW'] ? 'true' : 'false'
            ]) ?>
                <div class="news-detail-project-review-name intec-template-part intec-template-part-title" data-align="center">
                    <?= $arData['REVIEW']['NAME'] ?>
                </div>
                <div class="news-detail-project-review-value">
                    <div class="intec-grid intec-grid-768-wrap">
                        <div class="news-detail-project-review-value-picture-wrap intec-grid-item-auto intec-grid-item-768-1">
                            <?= Html::tag('div', '', [
                                'class' => 'news-detail-project-review-value-picture',
                                'style' => [
                                    'background-image' => 'url("'.$sPicture.'")'
                                ]
                            ]) ?>
                        </div>
                        <div class="intec-grid-item intec-grid-item-768-1">
                            <div class="news-detail-project-review-value-text-wrap">
                                <div class="news-detail-project-review-value-text">
                                    <?= $arData['REVIEW']['VALUE']['TEXT'] ?>
                                </div>
                            </div>
                            <div class="news-detail-project-review-value-name">
                                <?= $arData['REVIEW']['VALUE']['NAME'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>