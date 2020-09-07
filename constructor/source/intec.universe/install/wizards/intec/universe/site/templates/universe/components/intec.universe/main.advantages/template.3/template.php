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

$iCount = 0;

?>
<div class="widget c-advantages c-advantages-template-3" id="<?= $sTemplateId ?>" data-indent="<?= $arVisual['INDENT']['USE'] ?>">
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
                <div class="c-widget-elements">
                    <div class="c-widget-elements-wrapper">
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
                                    'width' => 700,
                                    'height' => 700
                                ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                if (!empty($sPicture))
                                    $sPicture = $sPicture['src'];
                            }

                            if (empty($sPicture))
                                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                            $iCount++;
                            $bLeft = $iCount % 2 == 1;

                        ?>
                            <div class="widget-element<?= $bLeft ? ' widget-element-left' : null ?>" id="<?= $sAreaId ?>">
                                <div class="widget-element-wrapper">
                                    <?php if ($bLeft) { ?>
                                        <div class="widget-element-content">
                                            <div class="widget-element-content-aligner"></div>
                                            <div class="widget-element-content-wrapper">
                                                <div class="widget-element-name">
                                                    <?= $arItem['NAME'] ?>
                                                </div>
                                                <div class="widget-element-line"></div>
                                                <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                                                    <div class="widget-element-description">
                                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?= Html::tag('div', '', [
                                            'class' => 'widget-element-image',
                                            'style' => [
                                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null,
                                                'background-size' => $arVisual['BACKGROUND']['SIZE']
                                            ],
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                            ]
                                        ]) ?>
                                        <?php if ($arVisual['ARROW']['SHOW']) { ?>
                                            <div class="widget-element-arrow"></div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?= Html::tag('div', '', [
                                            'class' => 'widget-element-image',
                                            'style' => [
                                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null,
                                                'background-size' => $arVisual['BACKGROUND']['SIZE']
                                            ],
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                            ]
                                        ]) ?>
                                        <div class="widget-element-content">
                                            <div class="widget-element-content-aligner"></div>
                                            <div class="widget-element-content-wrapper">
                                                <div class="widget-element-name">
                                                    <?= $arItem['NAME'] ?>
                                                </div>
                                                <div class="widget-element-line"></div>
                                                <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                                                    <div class="widget-element-description">
                                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php if ($arVisual['ARROW']['SHOW']) { ?>
                                            <div class="widget-element-arrow"></div>
                                        <?php } ?>
                                    <?php } ?>
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