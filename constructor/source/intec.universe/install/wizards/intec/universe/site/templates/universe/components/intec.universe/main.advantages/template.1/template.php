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
<div class="widget c-advantages c-advantages-template-1" id="<?= $sTemplateId ?>">
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
                <div class="widget-elements widget-elements-columns-<?= $arVisual['COLUMNS'] ?>">
                    <div class="widget-elements-wrapper">
                        <?php foreach ($arResult['ITEMS'] as $arItem) {

                            $sId = $sTemplateId.'_'.$arItem['ID'];
                            $sAreaId = $this->GetEditAreaId($sId);
                            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                            $sPicture = !empty($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE']['SRC'] : null;

                            if (!empty($arItem['PREVIEW_PICTURE'])) {
                                $sPicture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], [
                                    'width' => 80,
                                    'height' => 80
                                ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                                if (!empty($sPicture)) {
                                    $sPicture = $sPicture['src'];
                                } else {
                                    $sPicture = null;
                                }
                            }

                            $sDescription = $arItem['PREVIEW_TEXT'];

                        ?>
                            <div class="widget-element<?= empty($sPicture) ? ' widget-element-without-picture' : null ?>">
                                <div class="widget-element-wrapper" id="<?= $sAreaId ?>">
                                    <?php if (!empty($sPicture)) { ?>
                                        <div class="advantages-image">
                                            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
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
                                    <div class="widget-element-name intec-cl-text-hover">
                                        <?= $arItem['NAME'] ?>
                                        <? if (!empty($sDescription)) { ?>
                                            <div class="widget-element-description">
                                                <?= Html::encode($sDescription) ?>
                                            </div>
                                        <? } ?>
                                    </div>
                                    <div class="intec-ui-clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="intec-ui-clear"></div>
                     </div>
                 </div>
            </div>
         </div>
    </div>
</div>