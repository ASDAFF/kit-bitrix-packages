<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php $vImage = function (&$arItem) use (&$arResult, &$arVisual) { ?>
    <?php $arParentValues = [
        'NAME' => $arItem['NAME'],
        'URL' => $arItem['DETAIL_PAGE_URL']
    ] ?>
    <?php $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$arParentValues) {
        $sPicture = $arItem['PICTURE'];

        if (!empty($sPicture)) {
            $sPicture = CFile::ResizeImageGet($sPicture, [
                'width' => 450,
                'height' => 450
            ], BX_RESIZE_IMAGE_PROPORTIONAL);

            if (!empty($sPicture))
                $sPicture = $sPicture['src'];
        }

        if (empty($sPicture) && $bOffer)
            return;

        if (empty($sPicture))
            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

    ?>
        <?= Html::beginTag('div', [
            'class' => [
                'catalog-section-item-image-look',
                'intec-image',
                'intec-image-effect'
            ],
            'data' => [
                'role' => 'gallery',
                'offer' => $bOffer ? $arItem['ID'] : 'false'
            ]
        ]) ?>
            <div class="intec-aligner"></div>
            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                'alt' => $arParentValues['NAME'],
                'title' => $arParentValues['NAME'],
                'loading' => 'lazy',
                'data' => [
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                ]
            ]) ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
    <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
        'class' => 'catalog-section-item-image-items',
        'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arParentValues['URL'] : null,
        'data-role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
    ]) ?>
        <?php $fRender($arItem) ?>
        <?php if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']))
            foreach ($arItem['OFFERS'] as &$arOffer)
                $fRender($arOffer, true);
        ?>
    <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
<?php } ?>