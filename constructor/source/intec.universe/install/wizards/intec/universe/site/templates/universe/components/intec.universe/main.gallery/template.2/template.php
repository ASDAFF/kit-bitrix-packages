<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];


?>
<div class="widget c-gallery c-gallery-template-2" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content-wrap">
        <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
            <div class="widget-header">
                <div class="widget-header-wrapper intec-content">
                    <div class="widget-header-wrapper-2 intec-content-wrapper">
                        <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                            <div class="widget-title align-<?= $arBlocks['HEADER']['POSITION'] ?>">
                                <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                            </div>
                        <?php } ?>
                        <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                            <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                                <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="widget-content">
            <?php if (!$arVisual['WIDE']) { ?>
                <div class="widget-content-wrapper intec-content">
                    <div class="widget-content-wrapper-2 intec-content-wrapper">
            <?php } ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-items' => true,
                        'intec-grid' => [
                            '' => true,
                            'wrap' => true,
                            'a-v-start' => true,
                            'a-h-center' => true,
                            'i-4' => $arVisual['DELIMITERS']
                        ]
                    ], true),
                    'data' => [
                        'role' => 'items'
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        $sPictureResized = CFile::ResizeImageGet($sPicture, [
                            'width' => 600,
                            'height' => 600
                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                        if (!empty($sPictureResized)) {
                            $sPictureResized = $sPictureResized['src'];
                        } else {
                            $sPictureResized = $sPicture['SRC'];
                        }

                        $sPicture = $sPicture['SRC'];

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '1000-4' => $arVisual['COLUMNS'] >= 6,
                                    '720-3' => $arVisual['COLUMNS'] >= 4,
                                    '500-2' => true
                                ]
                            ], true),
                            'data' => [
                                'role' => 'item',
                                'src' => $sPicture,
                                'preview-src' => $sPictureResized
                            ]
                        ]) ?>
                            <div id="<?= $sAreaId ?>" class="widget-item-wrapper">
                                <?= Html::beginTag('div', [
                                    'class' => 'widget-item-picture',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPictureResized : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPictureResized.'\')' : null
                                    ]
                                ]) ?>
                                    <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPictureResized, [
                                        'alt' => $arItem['NAME'],
                                        'title' => $arItem['NAME'],
                                        'loading' => 'lazy',
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPictureResized : null
                                        ]
                                    ]) ?>
                                 <?= Html::endTag('div') ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            <?php if (!$arVisual['WIDE']) { ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
            <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                <div class="widget-footer-wrapper intec-content">
                    <div class="widget-footer-wrapper-2 intec-content-wrapper">
                        <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                            <a href="<?= $arBlocks['FOOTER']['BUTTON']['LINK'] ?>" class="<?= Html::cssClassFromArray([
                                'widget-footer-button',
                                'intec-ui' => [
                                    '',
                                    'control-button',
                                    'mod-transparent',
                                    'mod-round-half',
                                    'scheme-current',
                                    'size-5'
                                ]
                            ]) ?>"><?= Html::encode($arBlocks['FOOTER']['BUTTON']['TEXT']) ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if (!defined('EDITOR')) { ?>
        <?php include(__DIR__.'/parts/script.php') ?>
    <?php } ?>
</div>