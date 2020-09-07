<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arData
 */

$sPicture = $arResult['PREVIEW_PICTURE'];

if (empty($sPicture))
    $sPicture = $arResult['DETAIL_PICTURE'];

if (!empty($sPicture))
    $sPicture = $sPicture['SRC'];

if (empty($sPicture))
    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

?>
<div class="news-detail-banner">
    <?= Html::beginTag('div', [
        'class' => 'news-detail-banner-picture',
        'style' => [
            'background-image' => 'url("'.$sPicture.'")'
        ]
    ]) ?>
        <div class="news-detail-banner-fade"></div>
        <?= Html::beginTag('div', [
            'class' => 'news-detail-banner-body',
            'style' => [
                'height' => $arVisual['BANNER']['HEIGHT'].'px'
            ]
        ]) ?>
            <div class="news-detail-banner-body-wrapper intec-content">
                <div class="news-detail-banner-body-wrapper-2 intec-content-wrapper">
                    <div class="news-detail-banner-body-wrapper-3 intec-grid intec-grid-a-v-stretch">
                        <div class="news-detail-banner-content-wrapper intec-grid-item-2 intec-grid-item-768-1">
                            <div class="intec-aligner"></div>
                            <div class="news-detail-banner-content">
                                <?php if ($arVisual['BANNER']['TYPE']) { ?>
                                    <div class="news-detail-banner-type">
                                        <?= $arData['BANNER']['TYPE'] ?>
                                    </div>
                                <?php } ?>
                                <div class="news-detail-banner-name">
                                    <?= $arResult['NAME'] ?>
                                </div>
                                <?php if ($arVisual['BANNER']['URL']) { ?>
                                    <div class="news-detail-banner-buttons">
                                        <?= Html::tag('a', $arData['BANNER']['BUTTON'], [
                                            'href' => $arData['BANNER']['URL'],
                                            'class' => 'news-detail-banner-button',
                                            'rel' => 'nofollow',
                                            'target' => '_blank'
                                        ]) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($arVisual['BANNER']['IMAGE']) {

                            $arData['BANNER']['IMAGE'] = CFile::ResizeImageGet(
                                $arData['BANNER']['IMAGE'], [
                                    'width' => 900,
                                    'height' => 900
                                ],
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                            );

                            if (!empty($arData['BANNER']['IMAGE']))
                                $arData['BANNER']['IMAGE'] = $arData['BANNER']['IMAGE']['src'];

                        ?>
                            <div class="news-detail-banner-decoration-wrap intec-grid-item-2">
                                <div class="news-detail-banner-decoration">
                                    <div class="news-detail-banner-decoration-background">
                                        <?= Html::tag('div', '', [
                                            'class' => 'news-detail-banner-decoration-picture',
                                            'style' => [
                                                'background-image' => 'url("'.$arData['BANNER']['IMAGE'].'")'
                                            ]
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
</div>
<?php unset($sPicture) ?>