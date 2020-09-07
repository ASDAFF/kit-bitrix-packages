<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
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
<div class="widget c-staff c-staff-template-4" id="<?= $sTemplateId ?>">
    <?php if (!$arVisual['WIDE']) { ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
    <?php } ?>
        <div class="widget-wrapper">
            <div class="widget-wrapper-2">
                <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                    <div class="widget-header">
                        <div class="intec-content">
                            <div class="intec-content-wrapper">
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
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-content-wrap'
                    ],
                    'data-wide' => $arVisual['WIDE'] ? 'true' : 'false'
                ]) ?>
                    <div class="widget-background intec-cl-background"></div>
                    <div class="intec-content intec-content-visible">
                        <div class="intec-content-wrapper widget-content">
                            <div class="owl-carousel" data-role="items">
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
                                            'width' => 750,
                                            'height' => 750
                                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                        if (!empty($sPicture))
                                            $sPicture = $sPicture['src'];
                                    }

                                    if (empty($sPicture))
                                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                                    ?>
                                    <div class="widget-element" data-role="item">
                                        <div class="widget-element-wrapper" id="<?= $sAreaId ?>">
                                            <div class="intec-grid intec-grid-wrap intec-grid-a-v-center">
                                                <div class="intec-grid-item-2 intec-grid-item-950-1">
                                                    <div class="widget-element-description">
                                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                                    </div>
                                                    <div class="widget-element-name">
                                                        <?= $arItem['NAME'] ?>
                                                    </div>
                                                </div>
                                                <div class="widget-element-image-wrap intec-grid-item-2 intec-grid-item-a-end">
                                                    <?= Html::tag('div', '', [
                                                        'class' => 'widget-element-image',
                                                        'data' => [
                                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                                        ],
                                                        'style' => [
                                                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                                        ]
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="intec-ui intec-ui-control-navigation" data-role="navigation"></div>
                            <div class="widget-panel" data-role="panel">
                                <div class="widget-panel-current" data-role="panel.current"></div>
                            </div>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    <?php if (!$arVisual['WIDE']) { ?>
        </div>
    </div>
    <?php } ?>
</div>
<?php include(__DIR__.'/parts/script.php') ?>