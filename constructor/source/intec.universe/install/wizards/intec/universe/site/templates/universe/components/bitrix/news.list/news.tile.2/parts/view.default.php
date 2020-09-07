<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arItem
 * @var string $sAreaId
 * @var Closure $tagsRender($arData)
 */

?>
<?php return function (&$arItem) use (&$arResult, &$arVisual, &$sAreaId, &$tagsRender) {

    $sPicture = $arItem['PREVIEW_PICTURE'];

    if (empty($sPicture))
        $sPicture = $arItem['DETAIL_PICTURE'];

    if (!empty($sPicture)) {
        $sPicture = CFile::ResizeImageGet($sPicture, [
            'width' => 600,
            'height' => 600
        ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

        if (!empty($sPicture['src']))
            $sPicture = $sPicture['src'];
    }

    if (empty($sPicture))
        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

?>
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'news-list-item' => true,
            'intec-grid-item' => [
                $arVisual['COLUMNS'] => true,
                '1024-3' => $arVisual['COLUMNS'] >= 4,
                '768-2' => $arVisual['COLUMNS'] >= 3,
                '500-1' => true
            ]
        ], true),
        'data-view' => 'default'
    ]) ?>
        <?= Html::beginTag('div', [
            'id' => $sAreaId,
            'class' => 'news-list-item-wrapper',
            'data-rounded' => $arVisual['ROUNDED'] ? 'true' : 'false'
        ]) ?>
            <?= Html::tag('a', '', [
                'class' => [
                    'news-list-item-image',
                    'intec-image-effect'
                ],
                'href' => $arItem['DETAIL_PAGE_URL'],
                'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null,
                'data' => [
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                ],
                'style' => [
                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                ]
            ]) ?>
            <div class="news-list-item-content">
                <div class="news-list-item-name">
                    <?= Html::tag('a', $arItem['NAME'], [
                        'class' => 'intec-cl-text-hover',
                        'href' => $arItem['DETAIL_PAGE_URL'],
                        'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null
                    ]) ?>
                </div>
                <?php if ($arResult['TAGS']['SHOW'] && !empty($arItem['DATA']['TAGS'])) {
                    $tagsRender($arItem['DATA']['TAGS']);
                } ?>
                <?php if ($arVisual['DATE']['SHOW'] && !empty($arItem['DATA']['DATE'])) { ?>
                    <div class="news-list-item-date">
                        <?= $arItem['DATA']['DATE'] ?>
                    </div>
                <?php } ?>
                <?php if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['DATA']['PREVIEW'])) { ?>
                    <div class="news-list-item-description">
                        <?= $arItem['DATA']['PREVIEW'] ?>
                    </div>
                <?php } ?>
            </div>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php } ?>