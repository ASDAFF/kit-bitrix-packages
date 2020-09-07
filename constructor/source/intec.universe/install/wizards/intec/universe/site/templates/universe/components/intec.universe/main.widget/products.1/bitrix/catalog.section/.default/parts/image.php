<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arVisual
 */

$vImage = function (&$arItem) use (&$arVisual, $arResult) {
    $fRender = function ($arPictures, $sName, $sLink, &$arOffer = null) use (&$arVisual, $arResult) {
        $bSlider = false;

        if (!empty($arPictures) && Type::isArray($arPictures)) {
            foreach ($arPictures as $iKey => $arPicture) {
                $arPicture = CFile::ResizeImageGet(
                    $arPicture,
                    [
                        'width' => 450,
                        'height' => 450
                    ],
                    BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                );

                if (!empty($arPicture))
                    $arPictures[$iKey] = $arPicture['src'];
            }
        }

        if (empty($arPictures) && !empty($arOffer))
            return;

        if (empty($arPictures))
            $arPictures[] = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

        if ($arVisual['IMAGE']['SLIDER'] && count($arPictures) > 1)
            $bSlider = true;

    ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-item-image',
                'intec-image'
            ],
            'data' => [
                'role' => 'image',
                'offer' => !empty($arOffer) ? $arOffer['ID'] : 'false',
                'view' => $arVisual['OFFERS']['VIEW']
            ]
        ]) ?>
            <?php if ($bSlider) { ?>
                <div class="widget-item-image-wrapper widget-item-image-slider owl-carousel">
                    <?php foreach ($arPictures as $sPicture) { ?>
                        <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                            'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? Html::decode($sLink) : null,
                            'class' => [
                                'widget-item-image-element',
                                'intec-image-effect'
                            ],
                            'data' => [
                                'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                            ]
                        ]) ?>
                            <div class="intec-aligner"></div>
                            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                                'alt' => Html::decode($sName),
                                'title' => Html::decode($sName),
                                'loading' => 'lazy',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ]
                            ]) ?>
                        <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
                    <?php } ?>
                </div>
            <?php } else {

                $sPicture = $arPictures[0];

            ?>
                <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? Html::decode($sLink) : null,
                    'class' => [
                        'widget-item-image-wrapper',
                        'intec-image-effect'
                    ]
                ]) ?>
                    <div class="intec-aligner"></div>
                    <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                        'alt' => Html::decode($sName),
                        'title' => Html::decode($sName),
                        'loading' => 'lazy',
                        'data' => [
                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                        ]
                    ]) ?>
                <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php };

    $fRender(
        $arItem['PICTURES'],
        $arItem['NAME'],
        $arItem['DETAIL_PAGE_URL']
    );

    if (!empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer)
            $fRender(
                !empty($arOffer['PICTURES']) ? $arOffer['PICTURES'] : $arItem['PICTURES'],
                $arItem['NAME'],
                $arItem['DETAIL_PAGE_URL'],
                $arOffer
            );
};