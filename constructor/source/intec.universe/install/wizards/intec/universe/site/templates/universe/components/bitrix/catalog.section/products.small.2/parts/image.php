<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php $vImage = function (&$arItem) use (&$arVisual) {
    $sPicture = $arItem['PICTURE'];

    if (!empty($sPicture)) {
        $sPicture = CFile::ResizeImageGet($sPicture, [
            'width' => 300,
            'height' => 300
        ], BX_RESIZE_IMAGE_PROPORTIONAL);

        if (!empty($sPicture))
            $sPicture = $sPicture['src'];
    }

    if (empty($sPicture))
        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

?>
    <?= Html::beginTag('a', [
        'class' => [
            'catalog-section-item-picture-wrap',
            'intec-image-effect'
        ],
        'href' => $arItem['DETAIL_PAGE_URL']
    ]) ?>
        <?= Html::tag('div', null, [
            'class' => 'catalog-section-item-picture',
            'title' => $arItem['NAME'],
            'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : null,
            'data-original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null,
            'style' => [
                'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$sPicture.'\')'
            ]
        ]) ?>
    <?= Html::endTag('a') ?>
<?php } ?>