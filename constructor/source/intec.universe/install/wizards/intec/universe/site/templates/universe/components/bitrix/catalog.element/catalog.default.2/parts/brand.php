<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\Html;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arVisual
 */

if (empty($arResult['BRAND']['PICTURE']))
    return;

$sPicture = $arResult['BRAND']['PICTURE'];
$sPicture = CFile::ResizeImageGet($sPicture, [
    'width' => 200,
    'height' => 60
], BX_RESIZE_IMAGE_PROPORTIONAL);

if (!empty($sPicture))
    $sPicture = $sPicture['src'];

if (empty($sPicture)) {
    unset($sPicture);
    return;
}

$sStub = Properties::get('template-images-lazyload-stub');

?>
<div class="catalog-element-brand">
    <a href="<?= $arResult['BRAND']['DETAIL_PAGE_URL'] ?>">
        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $sPicture, [
            'alt' => $arResult['BRAND']['NAME'],
            'title' => $arResult['BRAND']['NAME'],
            'loading' => 'lazy',
            'data' => [
                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
            ]
        ]) ?>
    </a>
</div>
<?php

unset($sPicture, $sStub);