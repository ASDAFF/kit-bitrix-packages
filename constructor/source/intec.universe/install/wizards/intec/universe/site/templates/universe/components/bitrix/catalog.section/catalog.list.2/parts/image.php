<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php $vImage = function (&$arItem) use (&$arResult, &$arVisual) { ?>
    <?php $sStub = Properties::get('template-images-lazyload-stub') ?>
    <?php $fRender = function ($arPicture, $sName, $sLink, $arOffer = null) use (&$arResult, &$arVisual, &$sStub) {
        $sPicture = $arPicture;

        if (!empty($sPicture)) {
            $sPicture = CFile::ResizeImageGet($sPicture, [
                'width' => 450,
                'height' => 450
            ], BX_RESIZE_IMAGE_PROPORTIONAL);

            if (!empty($sPicture))
                $sPicture = $sPicture['src'];
        }

        if (empty($sPicture))
            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

    ?>
        <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
            'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $sLink : null,
            'class' => [
                'catalog-section-item-image-look',
                'intec-image',
                'intec-image-effect'
            ],
            'data' => [
                'offer' => !empty($arOffer) ? $arOffer['ID'] : 'false',
                'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
            ]
        ]) ?>
            <div class="intec-aligner"></div>
            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $sPicture, [
                'alt' => $sName,
                'title' => $sName,
                'loading' => 'lazy',
                'data' => [
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                ]
            ]) ?>
        <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
    <?php } ?>
    <?php $fRender(
        $arItem['PICTURE'],
        $arItem['NAME'],
        $arItem['DETAIL_PAGE_URL']
    );

    if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) {
        $arPicture = !empty($arOffer['PICTURE']) ? $arOffer['PICTURE'] : $arItem['PICTURE'];
        foreach ($arItem['OFFERS'] as &$arOffer)
            $fRender(
                $arPicture,
                $arItem['NAME'],
                $arItem['DETAIL_PAGE_URL'],
                $arOffer
            );
    }
    ?>
<?php } ?>