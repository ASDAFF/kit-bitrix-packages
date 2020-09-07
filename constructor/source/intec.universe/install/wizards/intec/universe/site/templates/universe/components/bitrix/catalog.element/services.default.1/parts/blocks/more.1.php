<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

$sPicture = $arBlock['PICTURE'];

if (!empty($sPicture)) {
    $sPicture = CFile::ResizeImageGet($sPicture, [
        'width' => 450,
        'height' => 450
    ], BX_RESIZE_IMAGE_PROPORTIONAL);

    if (!empty($sPicture))
        $sPicture = $sPicture['src'];
}

if (empty($sPicture))
    $sPicture = null;

?>
<div class="intec-area intec-area-include intec-area-include-link-1 intec-content-wrap">
    <div class="intec-area-part-wrapper intec-content">
        <div class="intec-area-part-wrapper-2 intec-content-wrapper">
            <div class="intec-area-part-wrapper-3 intec-grid intec-grid-nowrap intec-grid-700-wrap intec-grid-a-v-center intec-grid-i-20">
                <?php if (!empty($sPicture)) { ?>
                    <div class="intec-area-part-picture intec-grid-item-2 intec-grid-item-700-auto intec-grid-item-shrink-1">
                        <?= Html::img($arResult['LAZYLOAD']['USE'] ? $arResult['LAZYLOAD']['STUB'] : $sPicture, [
                            'loading' => 'lazy',
                            'alt' => $arResult['NAME'],
                            'title' => $arResult['NAME'],
                            'data' => [
                                'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arResult['LAZYLOAD']['USE'] ? $sPicture : null
                            ]
                        ]) ?>
                    </div>
                <?php } ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'intec-area-part-content',
                        'intec-grid-item-'.(!empty($sPicture) ? '2' : '1'),
                        'intec-grid-item-700-1'
                    ]
                ]) ?>
                    <?php if (!empty($arBlock['HEADER'])) { ?>
                        <div class="intec-area-part-header">
                            <?= $arBlock['HEADER'] ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arBlock['TEXT'])) { ?>
                        <div class="intec-area-part-description">
                            <?= $arBlock['TEXT'] ?>
                        </div>
                    <?php } ?>
                    <?php if ($arBlock['BUTTON']['SHOW']) { ?>
                        <div class="intec-area-part-link">
                            <a href="<?= $arBlock['BUTTON']['LINK'] ?>" class="intec-ui intec-ui-control-button intec-ui-mod-round-4 intec-ui-scheme-current intec-ui-size-4">
                                <?= $arBlock['BUTTON']['TEXT'] ?>
                            </a>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>
