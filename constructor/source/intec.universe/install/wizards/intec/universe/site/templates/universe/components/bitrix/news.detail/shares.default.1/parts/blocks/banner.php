<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

$sPicture = $arBlock['PICTURE'];

?>
<div class="news-detail-banner">
    <?php if (!$arBlock['WIDE']) { ?>
        <div class="news-detail-banner-wrapper intec-content">
            <div class="news-detail-banner-wrapper-2 intec-content-wrapper">
    <?php } ?>
    <?= Html::tag('div', '', [
        'class' => 'news-detail-banner-image',
        'data' => [
            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
        ],
        'style' => [
            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null,
            'height' => $arBlock['HEIGHT']
        ],
        'title' => $arResult['NAME']
    ]) ?>
    <?php if (!$arBlock['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>