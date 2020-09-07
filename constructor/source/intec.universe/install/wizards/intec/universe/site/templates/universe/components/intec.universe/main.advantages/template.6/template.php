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
<div class="widget c-advantages c-advantages-template-6" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content">
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
                            'i-h-15',
                            'i-v-25'
                        ]
                    ]
                ]) ?>
                <?php foreach ($arResult ['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sPicture = null;

                    if ($arVisual['VIEW'] === 'icon') {
                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                'width' => 80,
                                'height' => 80
                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                    }

                    $iCount++;

                    ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-item' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '1100-2' => $arVisual['COLUMNS'] >= 3,
                                '500-1' => $arVisual['COLUMNS'] >= 2
                            ]
                        ], true)
                    ]) ?>
                    <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                        <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-start">
                            <div class="intec-grid-item-auto">
                                <?php if ($sPicture !== null) { ?>
                                    <?= Html::tag('div', '', [
                                        'class' => 'widget-item-icon',
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                        ],
                                        'style' => [
                                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                        ]
                                    ]) ?>
                                <?php } else { ?>
                                    <div class="widget-item-point intec-cl-background">
                                        <?php if ($arVisual['VIEW'] === 'number') { ?>
                                            <?= $iCount ?>
                                        <?php } else { ?>
                                            <i class="far fa-check"></i>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="intec-grid-item">
                                <?php if ($arVisual['NAME']['SHOW']) { ?>
                                    <div class="widget-item-name">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                                    <div class="widget-item-description">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                    <?= Html::endTag('div') ?>
                <? } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>