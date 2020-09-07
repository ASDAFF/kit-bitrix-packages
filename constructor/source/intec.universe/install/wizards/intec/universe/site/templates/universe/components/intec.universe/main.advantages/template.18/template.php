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
<div class="widget c-advantages c-advantages-template-18">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'intec-grid',
                        'intec-grid-wrap',
                        'intec-grid-a-h-start',
                        'intec-grid-i-25'
                    ]
                ]) ?>
                    <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'widget-header',
                                'intec-grid-item' => [
                                    '2',
                                    '1024-1'
                                ]
                            ]
                        ]) ?>
                            <div class="widget-header-wrapper">
                                <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                                    <div class="widget-title">
                                        <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                                    <div class="widget-description">
                                        <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
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
                                'width' => 258,
                                'height' => 258
                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                    ?>
                        <?= Html::beginTag('div', [
                            'id' => $sAreaId,
                            'class' => [
                                'widget-item',
                                'intec-grid-item' => [
                                    '2',
                                    '1024-1'
                                ]
                            ]
                        ]) ?>
                            <div class="widget-item-wrapper">
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'intec-grid',
                                        'intec-grid-wrap',
                                        'intec-grid-a-v-start',
                                        'intec-grid-i-h-20',
                                    ]
                                ]) ?>
                                    <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                                        <div class="widget-item-picture-wrap intec-grid-item-auto intec-grid-item-600-1">
                                            <?= Html::tag('div', '', [
                                                'class' => [
                                                    'widget-item-picture',
                                                ],
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                                ],
                                                'style' => [
                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                                ]
                                            ]) ?>
                                        </div>
                                    <?php } ?>
                                    <div class="widget-item-text intec-grid-item">
                                        <div class="widget-item-name">
                                            <?= Html::decode($arItem['NAME']) ?>
                                        </div>
                                        <?php if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) { ?>
                                            <div class="widget-item-description">
                                                <?= $arItem['PREVIEW_TEXT'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>