<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arItem
 * @var string $sAreaId
 */

?>
<?php return function (&$arItem) use (&$arResult, &$arVisual, &$sAreaId, &$tagsRender) {

    $sPicture = $arItem['PREVIEW_PICTURE'];

    if (empty($sPicture))
        $sPicture = $arItem['DETAIL_PICTURE'];

    if (!empty($sPicture)) {
        $sPicture = CFile::ResizeImageGet($sPicture, [
            'width' => 1250,
            'height' => 1250
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
            'intec-grid-item-auto' => true
        ], true),
        'data-view' => 'big'
    ]) ?>
        <?= Html::beginTag('div', [
            'id' => $sAreaId,
            'class' => [
                'news-list-item-wrapper',
                'intec-grid',
                'intec-grid-a-v-end'
            ],
            'data-rounded' => $arVisual['ROUNDED'] ? 'true' : 'false'
        ]) ?>
            <?= Html::tag('a', '', [
                'class' => 'news-list-item-image',
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
            <div class="news-list-item-content intec-grid-item">
                <div class="news-list-item-name">
                    <span>
                        <?= $arItem['NAME'] ?>
                    </span>
                </div>
                <?php if ($arResult['TAGS']['SHOW'] && !empty($arItem['DATA']['TAGS'])) {
                    $tagsRender($arItem['DATA']['TAGS']);
                } ?>
                <?php if ($arVisual['DATE']['SHOW'] && !empty($arItem['DATA']['DATE'])) { ?>
                    <div class="news-list-item-date-wrap">
                        <div class="news-list-item-date">
                            <?= $arItem['DATA']['DATE'] ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php } ?>