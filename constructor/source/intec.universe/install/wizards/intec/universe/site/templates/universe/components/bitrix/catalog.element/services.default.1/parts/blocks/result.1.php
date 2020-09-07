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
        'width' => 1100,
        'height' => 600
    ], BX_RESIZE_IMAGE_PROPORTIONAL);

    if (!empty($sPicture))
        $sPicture = $sPicture['src'];
}

if (empty($sPicture))
    $sPicture = null;

?>
<div class="intec-area intec-area-include intec-area-include-result-1 intec-content-wrap">
    <?php if (!empty($sPicture)) { ?>
        <div class="intec-area-part-picture">
            <?= Html::tag('div', '', [
                'class' => [
                    'intec-area-part-picture-wrapper'
                ],
                'data' => [
                    'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arResult['LAZYLOAD']['USE'] ? $sPicture : null
                ],
                'style' => [
                    'background-image' => !$arResult['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                ]
            ]) ?>
        </div>
    <?php } ?>
    <div class="intec-area-part-text intec-content">
        <div class="intec-area-part-text-wrapper intec-content-wrapper">
            <div class="intec-area-part-text-wrapper-2 intec-grid intec-grid-a-h-end intec-grid-a-h-850-center intec-grid-a-v-center">
                <div class="intec-area-part-text-block intec-grid-item-auto intec-grid-item-850-1 intec-grid-item-shrink-1">
                    <?php if (!empty($arBlock['HEADER'])) { ?>
                        <div class="intec-area-part-text-header">
                            <?= $arBlock['HEADER'] ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arBlock['TEXT'])) { ?>
                        <div class="intec-area-part-text-content">
                            <?= $arBlock['TEXT'] ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

unset($sPicture);