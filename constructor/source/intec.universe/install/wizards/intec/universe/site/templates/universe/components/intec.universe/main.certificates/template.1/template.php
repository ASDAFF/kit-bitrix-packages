<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

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
<div class="widget c-certificates c-certificates-template-1" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
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
            <?php } ?>
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-start',
                            'a-h-'.$arVisual['ALIGNMENT'],
                            'i-h-20',
                            'i-v-15'
                        ]
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
                                    '1000-4' => $arVisual['COLUMNS'] >= 5,
                                    '750-3' => $arVisual['COLUMNS'] >= 4,
                                    '550-2' => $arVisual['COLUMNS'] >= 3,
                                    '350-1' => true
                                ]
                            ], true),
                            'id' => $sAreaId
                        ]) ?>
                            <div class="widget-item-wrapper">
                                <?= Html::beginTag('div', [
                                    'class' => 'widget-item-picture',
                                    'data-src' => $sPicture
                                ]) ?>
                                    <div class="widget-item-picture-wrapper intec-ui-picture">
                                        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPictureResized, [
                                            'alt' => $arItem['NAME'],
                                            'loading' => 'lazy',
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPictureResized : null
                                            ]
                                        ]) ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                                <?php if ($arVisual['NAME']['SHOW']) { ?>
                                    <div class="widget-item-name">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
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
            <?php } ?>
        </div>
        <?php if (!defined('EDITOR')) { ?>
            <script>
                (function ($, api) {
                    $(document).ready(function () {
                        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                        var items = $('.widget-item', root);

                        items.container = $('.widget-items', root);
                        items.container.lightGallery({
                            'selector': '.widget-item-picture'
                        });
                    });
                })(jQuery, intec)
            </script>
        <?php } ?>
    </div>
</div>