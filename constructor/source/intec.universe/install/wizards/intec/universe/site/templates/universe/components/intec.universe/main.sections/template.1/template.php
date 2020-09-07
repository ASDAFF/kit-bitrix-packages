<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);


if (empty($arResult['SECTIONS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

?>
<div class="widget c-sections c-sections-template-1" id="<?= $sTemplateId ?>">
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
            <?= Html::beginTag('div', [
                'class' => [
                    'widget-content',
                    'intec-grid' => [
                        '',
                        'wrap',
                        'a-v-stretch',
                        'a-h-center'
                    ]
                ]
            ]) ?>
                <?php foreach ($arResult['SECTIONS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sPicture = $arItem['PICTURE'];

                    if (!empty($sPicture)) {
                        $sPicture = CFile::ResizeImageGet($sPicture, [
                            'width' => 450,
                            'height' => 450
                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                        if (!empty($sPicture))
                            $sPicture = $sPicture['src'];
                    }

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                ?>
                    <?= Html::beginTag('div', [ /** Главный блок элемента */
                        'class' => Html::cssClassFromArray([
                            'widget-element-wrap' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '900-4' => $arVisual['COLUMNS'] >= 5,
                                '700-3' => $arVisual['COLUMNS'] >= 4,
                                '550-2' => $arVisual['COLUMNS'] >= 3,
                                '350-1' => true,
                            ]
                        ], true)
                    ]) ?>
                        <div class="widget-element" id="<?= $sAreaId ?>">
                            <a class="widget-element-picture-block" href="<?= $arItem['SECTION_PAGE_URL'] ?>">
                                <div class="widget-element-picture-wrap intec-image intec-image-effect">
                                    <div class="intec-aligner"></div>
                                    <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                                        'class' => 'widget-element-picture',
                                        'loading' => 'lazy',
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                        ]
                                    ]) ?>
                                </div>
                            </a>
                            <div class="widget-element-name-wrap">
                                <a class="widget-element-name intec-cl-text-hover" href="<?= $arItem['SECTION_PAGE_URL'] ?>">
                                    <?= $arItem['NAME'] ?>
                                    <?php if ($arVisual['QUANTITY']['SHOW']) { ?>
                                        <?= '('.$arItem['ELEMENT_CNT'].')' ?>
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>