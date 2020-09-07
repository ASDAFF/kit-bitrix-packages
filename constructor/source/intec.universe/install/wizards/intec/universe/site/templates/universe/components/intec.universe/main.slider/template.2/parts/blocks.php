<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php return function (&$arBlocks = [], $position = 'right', $half = false) use (&$arVisual) {

    if (empty($arBlocks))
        return;

?>
    <?= Html::beginTag('div', [
        'class' => 'widget-blocks',
        'data' => [
            'position' => $position,
            'count' => count($arBlocks),
            'width' => $half ? 'half' : 'full',
            'indent' => $arVisual['BLOCKS']['INDENT'] ? 'true' : 'false'
        ]
    ]) ?>
        <?= Html::beginTag('div', [
            'class' => Html::cssClassFromArray([
                'widget-blocks-wrapper' => true,
                'intec-grid' => [
                    '' => true,
                    'wrap' => true,
                    'i-4' => $arVisual['BLOCKS']['INDENT']
                ]
            ], true)
        ]) ?>
            <?php foreach ($arBlocks as $arBlock) {

                $arBlocksData = $arBlock['DATA'];

                $sTag = !empty($arBlocksData['LINK']['VALUE']) ? 'a' : 'div';
                $sBlockPicture = $arBlock['PREVIEW_PICTURE'];

                if (empty($sBlockPicture))
                    $sBlockPicture = $arBlock['DETAIL_PICTURE'];

                if (!empty($sBlockPicture)) {
                    $sBlockPicture = CFile::ResizeImageGet($sBlockPicture, [
                            'width' => 1024,
                            'height' => 1024
                        ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                    );

                    if (!empty($sBlockPicture))
                        $sBlockPicture = $sBlockPicture['src'];
                }

                if (empty($sBlockPicture))
                    $sBlockPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

            ?>
                <div class="widget-block intec-grid-item-auto">
                    <?= Html::beginTag($sTag, [
                        'href' => $sTag === 'a' ? $arBlocksData['LINK']['VALUE'] : null,
                        'class' => 'widget-block-wrapper',
                        'target' => $sTag === 'a' && $arBlocksData['LINK']['BLANK'] ? '_blank' : null,
                        'data' => [
                            'effect-scale' => $arVisual['BLOCKS']['EFFECT']['SCALE'] ? 'true' : 'false',
                            'rounded' => $arVisual['ROUNDED'] ? 'true' : 'false'
                        ]
                    ]) ?>
                        <?= Html::tag('div', '', [
                            'class' => 'widget-block-picture',
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sBlockPicture : null
                            ],
                            'style' => [
                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sBlockPicture.'\')' : null
                            ]
                        ]) ?>
                        <?php if ($arVisual['BLOCKS']['EFFECT']['FADE']) { ?>
                            <div class="widget-block-fade"></div>
                        <?php } ?>
                        <div class="widget-block-header">
                            <?= Html::tag('div', $arBlock['NAME'], [
                                'class' => 'widget-block-header-content'
                            ]) ?>
                        </div>
                    <?= Html::endTag($sTag) ?>
                </div>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php } ?>