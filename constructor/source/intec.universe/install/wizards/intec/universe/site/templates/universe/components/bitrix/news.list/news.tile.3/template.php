<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

/**
 * @var Closure $tagsRender($arTags)
 */
$tagsRender = include(__DIR__.'/parts/tags.php');

?>
<div class="ns-bitrix c-news-list c-news-list-tile-3" id="<?= $sTemplateId ?>">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <?php if ($arVisual['NAVIGATION']['SHOW']['TOP']) { ?>
                <div data-pagination-num="<?= $arResult['NAVIGATION']['NUMBER'] ?>">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
            <?php } ?>
            <div class="news-list-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'news-list-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-stretch',
                            'i-7'
                        ]
                    ],
                    'data-role' => 'items'
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $bDate = $arVisual['DATE']['SHOW'] && !empty($arItem['DATA']['DATE']);

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
                            'data-date' => $bDate ? 'true' : 'false'
                        ]) ?>
                            <?= Html::beginTag('div', [
                                'id' => $sAreaId,
                                'class' => 'news-list-item-wrapper',
                                'data-rounded' => $arVisual['ROUNDED'] ? 'true' : 'false'
                            ]) ?>
                                <div class="news-list-item-image">
                                    <?= Html::tag('a', '', [
                                        'class' => 'intec-image-effect',
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
                                    <?php if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['DATA']['PREVIEW'])) { ?>
                                        <div class="news-list-item-description">
                                            <?= $arItem['DATA']['PREVIEW'] ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if ($bDate) { ?>
                                    <div class="news-list-item-date">
                                        <?= $arItem['DATA']['DATE'] ?>
                                    </div>
                                <?php } ?>
                            <?= Html::endTag('div') ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($arVisual['NAVIGATION']['SHOW']['BOTTOM']) { ?>
                <div data-pagination-num="<?= $arResult['NAVIGATION']['NUMBER'] ?>">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
            <?php } ?>
        </div>
    </div>
    <?php if ($arResult['TAGS']['SHOW'] && $arResult['TAGS']['MODE'] === 'active')
        include(__DIR__.'/parts/form.php');
    ?>
</div>
