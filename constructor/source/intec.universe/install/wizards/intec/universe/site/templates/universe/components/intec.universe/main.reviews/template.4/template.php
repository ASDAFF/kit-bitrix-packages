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

$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';

?>
<div class="widget c-reviews c-reviews-template-4" id="<?= $sTemplateId ?>">
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
                <div class="widget-items">
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $arData = $arItem['DATA'];

                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                    'width' => 130,
                                    'height' => 130
                                ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                            );

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                        $sText = $arItem['PREVIEW_TEXT'];

                        if (empty($sText))
                            $sText = $arItem['DETAIL_TEXT'];

                        if (empty($sText))
                            continue;

                    ?>
                        <div class="widget-item" id="<?= $sAreaId?>">
                            <div class="widget-item-wrapper intec-grid intec-grid-a-v-stretch intec-grid-768-wrap">
                                <div class="widget-item-person intec-grid-item-auto intec-grid-item-768-1">
                                    <div class="widget-item-person-wrapper">
                                        <?= Html::tag($sTag, '', [
                                            'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                            'class' => 'widget-item-picture',
                                            'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                            ],
                                            'style' => [
                                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                            ]
                                        ]) ?>
                                        <?= Html::tag($sTag, $arItem['NAME'], [
                                            'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                            'class' => [
                                                'widget-item-name',
                                                'intec-cl-text',
                                                'intec-cl-text-light-hover'
                                            ],
                                            'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null
                                        ]) ?>
                                        <?php if ($arVisual['POSITION']['SHOW'] && !empty($arData['POSITION'])) { ?>
                                            <div class="widget-item-position">
                                                <?= $arData['POSITION'] ?>
                                            </div>
                                        <?php } ?>
                                        <div class="widget-item-line-top"></div>
                                        <div class="widget-item-line-bottom"></div>
                                    </div>
                                </div>
                                <div class="widget-item-description intec-grid-item intec-grid-item-768-1">
                                    <?= $sText ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                    <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                        <?= Html::tag('a', $arBlocks['FOOTER']['BUTTON']['TEXT'], [
                            'href' => $arBlocks['FOOTER']['BUTTON']['LINK'],
                            'class' => [
                                'widget-footer-button',
                                'intec-ui' => [
                                    '',
                                    'size-5',
                                    'scheme-current',
                                    'control-button',
                                    'mod' => [
                                        'transparent',
                                        'round-half'
                                    ]
                                ]
                            ]
                        ]) ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>