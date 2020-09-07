<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
<div class="widget c-sections c-sections-template-3" id="<?= $sTemplateId ?>">
    <?php if (!$arVisual['WIDE']) { ?>
    <div class="intec-content intec-content-visual">
        <div class="intec-content-wrapper">
    <?php } ?>
        <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
            <div class="widget-header">
                <?php if ($arVisual['WIDE']) { ?>
                    <div class="intec-content intec-content-visual">
                        <div class="intec-content-wrapper">
                <?php } ?>
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
                <?php if ($arVisual['WIDE']) { ?>
                        </div>
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
                        'wrap'
                    ]
                ],
                'data' => [
                    'grid' => $arVisual['COLUMNS']
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
                            'width' => 950,
                            'height' => 950
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
                                '1200-2' => $arVisual['COLUMNS'] >= 4,
                                '768-1' => true
                            ]
                        ], true)
                    ]) ?>
                        <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
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
                            <div class="widget-item-fade"></div>
                            <?php if ($arVisual['LINK']['USE']) { ?>
                                <?= Html::tag('a', '', [
                                    'href' => $arItem['SECTION_PAGE_URL'],
                                    'class' => 'widget-item-link'
                                ]) ?>
                            <?php } ?>
                            <div class="widget-item-text">
                                <div class="widget-item-name">
                                    <?= $arItem['NAME'] ?>
                                </div>
                                <?php if ($arVisual['TEXT']['SHOW']) { ?>
                                    <div class="widget-item-description">
                                        <?= $arItem['DESCRIPTION'] ?>
                                    </div>
                                <?php } ?>
                                <div class="widget-item-text-decoration"></div>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
        <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
            <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                    <?php if ($arVisual['WIDE']) { ?>
                        <div class="intec-content intec-content-visual">
                            <div class="intec-content-wrapper">
                    <?php } ?>
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
                    <?php if ($arVisual['WIDE']) { ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    <?php if (!$arVisual['WIDE']) { ?>
        </div>
    </div>
    <?php } ?>
</div>
