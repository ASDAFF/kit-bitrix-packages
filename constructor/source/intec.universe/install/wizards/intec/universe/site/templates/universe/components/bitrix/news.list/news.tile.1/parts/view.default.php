<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var Closure $tagsRender($arItemTags)
 * @var string $sAreaId
 */

?>
<?php return function (&$arItem) use (&$arResult, &$arVisual, &$tagsRender, &$sAreaId) {

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
        <div class="news-list-item-wrapper" id="<?= $sAreaId ?>">
            <div class="news-list-item-image-wrapper">
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
            </div>
            <div class="news-list-item-text">
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
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>