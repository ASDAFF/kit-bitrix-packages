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
        'c-advantages-template-13',
    ],
    'id' => $sTemplateId,
    'data-size' => $arVisual['PICTURE']['SIZE']
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
                        'intec-grid-i-h-15',
                        'intec-grid-i-v-25'
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
                                'width' => $arVisual['PICTURE']['SIZE'],
                                'height' => $arVisual['PICTURE']['SIZE']
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
                                    '1024-3' => $arVisual['COLUMNS'] >= 4,
                                    '500-2' => $arVisual['COLUMNS'] >= 3,
                                    '400-1' => $arVisual['COLUMNS'] >= 2
                                ]
                            ], true),
                            'id' => $sAreaId
                        ]) ?>
                            <?= Html::beginTag('div', [
                                'class' => 'widget-item-wrapper',
                                'data' => [
                                    'margin' => $arVisual['ELEMENT']['MARGIN']
                                ]
                            ])?>
                                <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                                    <div class="widget-item-picture-wrap">
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
                                    </div>
                                <?php } ?>
                                <div class="widget-item-text">
                                    <?php if ($arVisual['NAME']['SHOW']) { ?>
                                        <?= Html::tag('div', $arItem['NAME'], [
                                            'class' => [
                                                'widget-item-name'
                                            ],
                                            'data' => [
                                                'size' => $arVisual['NAME']['SIZE'],
                                                'margin' => $arVisual['NAME']['MARGIN']
                                            ]
                                        ]) ?>
                                    <?php } ?>
                                    <?php if (!empty($arItem['PREVIEW_TEXT']) && $arVisual['PREVIEW']['SHOW']) { ?>
                                        <div class="widget-item-description">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>