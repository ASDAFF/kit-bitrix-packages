<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

$iCounter = 0;

?>
<div class="widget c-advantages c-advantages-template-29" id="<?= $sTemplateId ?>">
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
        <div class="widget-items">
            <?php foreach ($arResult['ITEMS'] as $arItem) {

                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                $sPicture = $arItem['PREVIEW_PICTURE'];

                if (empty($sPicture))
                    $sPicture = $arItem['DETAIL_PICTURE'];

                if (!empty($sPicture)) {
                    $sPicture = CFile::ResizeImageGet($sPicture, [
                        'width' => 500,
                        'height' => 500
                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                    if (!empty($sPicture))
                        $sPicture = $sPicture['src'];
                }

                if (empty($sPicture))
                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
            ?>
            <div class="widget-item-wrap<?= ($iCounter % 2) !== 0 ? ' fill' : null ?>">
                <div class="intec-content">
                    <div class="intec-content-wrapper">
                        <?= Html::beginTag('div',[
                            'id' => $sAreaId,
                            'class' => [
                                Html::cssClassFromArray([
                                    'widget-item' => true,
                                    'intec-grid' => true,
                                    'intec-grid-wrap' => true,
                                    'intec-grid-a-v-center' => true,
                                    'intec-grid-a-h-between' => $arVisual['PICTURE']['SHOW'],
                                    'intec-grid-i-15' => true,
                                    'intec-grid-o-horizontal-reverse' => $arVisual['PICTURE']['SHOW'] ? $iCounter % 2 : false
                                ], true)
                            ],
                            'data-picture-show' => $arVisual['PICTURE']['SHOW'] ? 'true' : 'false'
                        ]) ?>
                            <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                                <?= Html::beginTag('div',[
                                    'class' => [
                                        Html::cssClassFromArray([
                                            'widget-item-picture-wrap' => true,
                                            'align-left' => ($iCounter % 2) === 0,
                                            'align-right' => ($iCounter % 2) !== 0,
                                            'intec-grid-item' => [
                                                '2' => $arVisual['PICTURE']['SHOW'],
                                                '800-1' => $arVisual['PICTURE']['SHOW'],
                                            ]
                                        ], true)
                                    ]
                                ]) ?>
                                    <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                                        'class' => 'widget-element-picture',
                                        'loading' => 'lazy',
                                        'alt' => $arItem['NAME'],
                                        'title' => $arItem['NAME'],
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                        ]
                                    ]) ?>
                                <?= Html::endTag('div') ?>
                            <?php } ?>
                            <?= Html::beginTag('div',[
                                'class' => [
                                    Html::cssClassFromArray([
                                        'widget-item-text' => true,
                                        'intec-grid-item' => [
                                            '2' => $arVisual['PICTURE']['SHOW'],
                                            '800-1' => $arVisual['PICTURE']['SHOW'],
                                        ]
                                    ], true)
                                ]
                            ]) ?>
                                <div class="widget-item-name">
                                    <?= $arItem['NAME'] ?>
                                </div>
                                <?php if (!empty($arItem['PREVIEW_TEXT']) && $arVisual['PREVIEW']['SHOW']) { ?>
                                    <div class="widget-item-description">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                <?php } ?>
                            <?= Html::endTag('div') ?>
                        <?= Html::endTag('div') ?>
                        <?php $iCounter++; ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>