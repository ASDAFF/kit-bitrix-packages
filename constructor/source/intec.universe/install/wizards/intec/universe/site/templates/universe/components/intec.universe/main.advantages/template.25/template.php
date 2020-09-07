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

?>
<div class="widget c-advantages c-advantages-template-25" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
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
                <div class="widget-items intec-grid intec-grid-wrap intec-grid-i-h-10 intec-grid-i-v-20">
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
                                'width' => 450,
                                'height' => 90
                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '1200-4' => $arVisual['COLUMNS'] > 4,
                                    '768-3' => $arVisual['COLUMNS'] >= 4,
                                    '600-2' => true,
                                    '400-1' => true
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                                    <div class="widget-item-picture">
                                        <div class="intec-aligner"></div>
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
                                    </div>
                                <?php } ?>
                                <div class="widget-item-name">
                                    <?= $arItem['NAME'] ?>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
