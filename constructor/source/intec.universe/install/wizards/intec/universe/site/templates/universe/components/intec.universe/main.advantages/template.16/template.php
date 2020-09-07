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
<?= Html::beginTag('div', [
    'class' => [
        'widget',
        'c-advantages',
        'c-advantages-template-16'
    ],
    'id' => $sTemplateId,
    'data' => [
        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] && !empty($arVisual['BACKGROUND']['PATH']) ? 'true' : 'false',
        'original' => $arVisual['LAZYLOAD']['USE'] && !empty($arVisual['BACKGROUND']['PATH']) ? $arVisual['BACKGROUND']['PATH'] : null
    ],
    'style' => [
        'background-image' => !$arVisual['LAZYLOAD']['USE'] && !empty($arVisual['BACKGROUND']['PATH']) ? 'url(\''.$arVisual['BACKGROUND']['PATH'].'\')' : null,
        'background-color' => '#17171d'
    ],
]) ?>
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
                        'intec-grid',
                        'intec-grid-wrap',
                        'intec-grid-a-h-start',
                        'intec-grid-i-25'
                    ]
                ]) ?>
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
                                'width' => 100,
                                'height' => 100
                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                    ?>
                        <?= Html::beginTag('div', [
                            'id' => $sAreaId,
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '768-2' => $arVisual['COLUMNS'] >= 3,
                                    '600-1' => $arVisual['COLUMNS'] >= 2
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-item-wrapper">
                                <?php if ($arVisual['PICTURE']['POSITION'] == 'top') { ?>
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'intec-grid',
                                            'intec-grid-nowrap',
                                            'intec-grid-a-v-center',
                                            'intec-grid-i-h-15',
                                        ]
                                    ]) ?>
                                    <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                                        <div class="widget-item-picture-wrap intec-grid-item-auto">
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
                                    <div class="widget-item-name intec-grid-item">
                                        <?= Html::decode($arItem['NAME']) ?>
                                    </div>
                                    <?= Html::endTag('div') ?>
                                    <?php if (!empty($arItem['PREVIEW_TEXT']) && $arVisual['PREVIEW']['SHOW']) { ?>
                                        <div class="widget-item-description">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>
                                    <?php } ?>
                                <?php } elseif($arVisual['PICTURE']['POSITION'] == 'left') { ?>
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'intec-grid',
                                            'intec-grid-nowrap',
                                            'intec-grid-a-v-start',
                                            'intec-grid-i-h-15',
                                        ]
                                    ]) ?>
                                        <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                                            <div class="widget-item-picture-wrap intec-grid-item-auto">
                                                <?= Html::tag('div', '', [
                                                    'class' => [
                                                        'widget-item-picture',
                                                    ],
                                                    'style' => [
                                                        'background-image' => 'url("'.$sPicture.'")'
                                                    ]
                                                ]) ?>
                                            </div>
                                        <?php } ?>
                                        <div class="widget-item-text intec-grid-item">
                                            <div class="widget-item-name">
                                                <?= Html::decode($arItem['NAME']) ?>
                                            </div>
                                            <?php if (!empty($arItem['PREVIEW_TEXT']) && $arVisual['PREVIEW']['SHOW']) { ?>
                                                <div class="widget-item-description">
                                                    <?= $arItem['PREVIEW_TEXT'] ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>