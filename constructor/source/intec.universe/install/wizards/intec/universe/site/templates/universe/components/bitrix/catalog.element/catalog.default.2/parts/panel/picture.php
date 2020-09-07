<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$sStub = Properties::get('template-images-lazyload-stub');

$vPanelPicture = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$sStub) {

    if (!empty($arItem['DETAIL_PICTURE']))
        $arPicture = $arItem['DETAIL_PICTURE'];
    else if (!empty($arItem['PREVIEW_PICTURE']))
        $arPicture = $arItem['PREVIEW_PICTURE'];

    if ($bOffer && empty($arPicture))
        return;

?>
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-element-panel-picture-item',
            'intec-image'
        ],
        'data' => [
            'role' => 'panel.picture',
            'offer' => $bOffer ? $arItem['ID'] : 'false'
        ]
    ]) ?>
        <div class="intec-aligner"></div>
        <?php if (!empty($arPicture)) {

            $arPicture = CFile::ResizeImageGet($arPicture,[
                'width' => 120,
                'height' => 120
            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)

        ?>
            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $arPicture['src'], [
                'alt' => $arResult['NAME'],
                'title' => $arResult['NAME'],
                'loading' => 'lazy',
                'data' => [
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['src'] : null
                ]
            ]) ?>
        <?php } else { ?>
            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : SITE_TEMPLATE_PATH.'/images/picture.missing.png', [
                'alt' => $arResult['NAME'],
                'title' => $arResult['NAME'],
                'loading' => 'lazy',
                'data' => [
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? SITE_TEMPLATE_PATH.'/images/picture.missing.png' : null
                ]
            ]) ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?php };

$vPanelPicture($arResult);

if (!empty($arResult['OFFERS'])) {
    foreach ($arResult['OFFERS'] as $arOffer)
        $vPanelPicture($arOffer, true);

    unset($arOffer);
}

unset($vPanelPicture);