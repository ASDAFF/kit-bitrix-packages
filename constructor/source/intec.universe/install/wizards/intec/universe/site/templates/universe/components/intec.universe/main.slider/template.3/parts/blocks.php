<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php return function (&$arBlocks) use (&$arVisual) { ?>
    <?php $iBlocksCount = count($arBlocks) ?>
    <?= Html::beginTag('div', [
        'class' => 'widget-blocks',
        'style' => [
            'height' => !empty($arVisual['BLOCKS']['HEIGHT']) ? $arVisual['BLOCKS']['HEIGHT'].'px' : null
        ]
    ]) ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-blocks-items',
                'intec-grid' => [
                    '',
                    'wrap'
                ]
            ],
            'data' => [
                'count' => $iBlocksCount,
                'effect-scale' => $arVisual['BLOCKS']['EFFECT']['SCALE'] ? 'true' : 'false'
            ]
        ]) ?>
            <?php foreach ($arBlocks as $arItem) {

                $arData = $arItem['DATA'];

                $sTag = !empty($arData['LINK']['VALUE']) ? 'a' : 'div';
                $sPicture = $arItem['PREVIEW_PICTURE'];

                if (empty($sPicture))
                    $sPicture = $arItem['DETAIL_PICTURE'];

                if (!empty($sPicture)) {
                    $sPicture = CFile::ResizeImageGet($sPicture, [
                        'width' => 1024,
                        'height' => 1024
                    ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                    if (!empty($sPicture))
                        $sPicture = $sPicture['src'];
                }

                if (empty($sPicture))
                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                $arGrid = [
                    '1' => $arVisual['BLOCKS']['POSITION'] === 'right',
                    '1024' => true,
                    '768-1' => true
                ];

                if ($arVisual['BLOCKS']['POSITION'] === 'bottom')
                    $arGrid = ArrayHelper::merge([
                        $iBlocksCount => true
                    ], $arGrid);

            ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-block' => true,
                        'intec-grid-item' => $arGrid
                    ], true)
                ]) ?>
                    <?= Html::beginTag($sTag, [
                        'href' => $sTag === 'a' ? $arData['LINK']['VALUE'] : null,
                        'class' => 'widget-block-wrapper',
                        'target' => $sTag === 'a' && $arData['LINK']['BLANK'] ? '_blank' : null
                    ]) ?>
                        <?= Html::tag('div', '', [
                            'class' => 'widget-block-picture',
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                            ],
                            'style' => [
                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                            ]
                        ]) ?>
                        <?php if ($arVisual['BLOCKS']['EFFECT']['FADE']) { ?>
                            <div class="widget-block-fade"></div>
                        <?php } ?>
                        <div class="widget-block-text">
                            <div class="widget-block-header">
                                <?= $arItem['NAME'] ?>
                            </div>
                            <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                                <div class="widget-block-description">
                                    <?= $arItem['PREVIEW_TEXT'] ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?= Html::endTag($sTag) ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php } ?>