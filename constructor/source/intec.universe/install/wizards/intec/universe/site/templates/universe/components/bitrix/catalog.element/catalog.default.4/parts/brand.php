<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\template\Properties;

/**
 * @var array $arVisual
 * @var array $arData
 */

$arBrandPicture = CFile::ResizeImageGet(
    $arData['BRAND']['PICTURE'], [
        'width' => 150,
        'height' => 150
    ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT
);

if (empty($arBrandPicture))
    return;

$sStub = Properties::get('template-images-lazyload-stub');

?>
<div class="catalog-element-brand-order intec-grid-item-auto intec-grid-item-1024-1">
    <?= Html::beginTag('a' ,[
        'class' => 'catalog-element-brand',
        'href' => $arData['BRAND']['URL'],
        'target' => '_blank'
    ]) ?>
        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $arBrandPicture['src'], [
            'class' => 'intec-image-effect',
            'alt' => $arData['BRAND']['NAME'],
            'title' => $arData['BRAND']['NAME'],
            'loading' => 'lazy',
            'data' => [
                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                'original' => $arVisual['LAZYLOAD']['USE'] ? $arBrandPicture['src'] : null
            ]
        ]) ?>
    <?= Html::endTag('a') ?>
</div>
<?php unset($arBrandPicture) ?>