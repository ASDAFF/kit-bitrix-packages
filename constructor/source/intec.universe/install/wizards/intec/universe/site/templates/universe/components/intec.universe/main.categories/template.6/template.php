<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !==true) die();

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

$iRepeatBlock = 1;
$iRepeatCounter = 2;

$arSizes = [
    0 => 'big',
    1 => 'small',
    2 => 'standard'
];

?>
<div class="widget c-categories c-categories-template-6" id="<?= $sTemplateId ?>">
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
                            'i-10'
                        ]
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $iCounter++;

                        $arData = $arItem['DATA'];
                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet(
                                $sPicture, [
                                'width' => 900,
                                'height' => 900
                            ],
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                            );

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH . '/images/picture.missing.png';

                        if ($arVisual['LINK']['USE'] && !empty($arItem['DETAIL_PAGE_URL']))
                            $sTag = 'a';
                        else
                            $sTag = 'div';

                        if ($arVisual['VIEW'] == 'chess') {
                            $iSize = $iRepeatBlock % 2;

                            if ($iRepeatCounter == 2) {
                                $iRepeatCounter = 1;
                                $iRepeatBlock++;
                            } else {
                                $iRepeatCounter++;
                            }
                        } else {
                            $iSize = 2;
                        }

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'widget-item',
                                'intec-grid-item' => [
                                    'auto'
                                ]
                            ],
                            'data-size' => $arSizes[$iSize]
                        ]) ?>
                            <?= Html::beginTag($sTag, [
                                'id' => $sAreaId,
                                'class' => 'widget-item-wrapper',
                                'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null
                            ]) ?>
                                <?= Html::tag('div', '', [
                                    'class' => 'widget-item-picture',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                    ]
                                ]) ?>
                                <?php if ($arVisual['STICKER']['SHOW'] && !empty($arData['STICKER'])) { ?>
                                    <div class="widget-item-sticker-wrap" data-horizontal="<?= $arVisual['STICKER']['HORIZONTAL'] ?>">
                                        <div class="intec-aligner"></div>
                                        <div class="widget-item-sticker" data-vertical="<?= $arVisual['STICKER']['VERTICAL'] ?>">
                                            <?= $arData['STICKER'] ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="widget-item-name-wrap" data-horizontal="<?= $arVisual['NAME']['HORIZONTAL'] ?>">
                                    <div class="intec-aligner"></div>
                                    <div class="widget-item-name" data-vertical="<?= $arVisual['NAME']['VERTICAL'] ?>">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                </div>
                            <?= Html::endTag($sTag) ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>
