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
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-advantages',
        'c-advantages-template-5',
        'intec-content-wrap'
    ],
    'data-theme' => $arVisual['THEME'],
    'data' => [
        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
        'original' => $arVisual['LAZYLOAD']['USE'] ? $arVisual['BACKGROUND']['PATH'] : null
    ],
    'style' => [
        'background-image' => !$arVisual['LAZYLOAD']['USE'] && $arVisual['BACKGROUND']['USE'] ? 'url(\''.$arVisual['BACKGROUND']['PATH'].'\')' : null
    ]
]) ?>
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <div class="widget-wrapper-3">
                <div class="widget-content intec-grid intec-grid-wrap">
                    <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <div class="widget-header-custom intec-grid-item-auto intec-grid-item-1000-1">
                            <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                                <div class="widget-header-custom-title">
                                    <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                                </div>
                            <?php } ?>
                            <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                                <div class="widget-header-custom-description">
                                    <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                                </div>
                            <?php } ?>
                            <div class="widget-header-custom-decoration"></div>
                        </div>
                    <?php } ?>
                    <div class="widget-content intec-grid-item intec-grid-item-1000-1">
                        <div class="widget-content-wrapper intec-grid intec-grid-wrap intec-grid-i-h-20">
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
                                        'width' => 200,
                                        'height' => 200
                                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                    if (!empty($sPicture))
                                        $sPicture = $sPicture['src'];
                                }

                                if (empty($sPicture))
                                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                            ?>
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'widget-element-wrap',
                                        'intec-grid-item-2',
                                        'intec-grid-item-600-1'
                                    ],
                                    'data-picture' => !$arVisual['ICON']['SHOW'] ? 'false' : null
                                ]) ?>
                                    <div class="widget-element intec-grid intec-grid-wrap" id="<?= $sAreaId ?>">
                                        <?php if ($arVisual['ICON']['SHOW']) { ?>
                                            <?= Html::tag('div', '', [
                                                'class' => [
                                                    'widget-element-picture',
                                                    'intec-grid-item-auto',
                                                    'intec-grid-item-600-1'
                                                ],
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                                ],
                                                'style' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                            ]) ?>
                                        <?php } ?>
                                        <div class="widget-element-text intec-grid-item intec-grid-item-600-1">
                                            <div class="widget-element-text-name">
                                                <?= $arItem['NAME'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?= Html::endTag('div') ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>