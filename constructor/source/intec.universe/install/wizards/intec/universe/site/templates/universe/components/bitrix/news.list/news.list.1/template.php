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
<div class="ns-bitrix c-news-list c-news-list-list-1" id="<?= $sTemplateId ?>">
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
                            'i-v-25'
                        ]
                    ],
                    'data-role' => 'items'
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                'width' => 350,
                                'height' => 350
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                            if (!empty($sPicture['src']))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'news-list-item',
                                'intec-grid-item-1'
                            ],
                            'data-delimiter' => $arVisual['DELIMITER']['SHOW'] ? 'true' : null
                        ]) ?>
                            <div class="news-list-item-wrapper" id="<?= $sAreaId ?>">
                                <div class="intec-grid intec-grid-600-wrap">
                                    <?php if ($arVisual['IMAGE']['SHOW']) { ?>
                                        <div class="intec-grid-item-auto intec-grid-item-600-1">
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
                                                    ],
                                                    'data-image-view' => $arVisual['IMAGE']['VIEW']
                                                ]) ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="intec-grid-item intec-grid-item-600-1">
                                        <div class="news-list-item-text">
                                            <?= Html::tag('a', $arItem['NAME'], [
                                                'class' => [
                                                    'news-list-item-name',
                                                    'intec-cl-text-light-hover'
                                                ],
                                                'href' => $arItem['DETAIL_PAGE_URL'],
                                                'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null
                                            ]) ?>
                                            <?php if ($arResult['TAGS']['SHOW'] && !empty($arItem['DATA']['TAGS'])) {
                                                $tagsRender($arItem['DATA']['TAGS']);
                                            } ?>
                                            <?php if ($arVisual['DATE']['SHOW'] && !empty($arItem['DATA']['DATE'])) { ?>
                                                <div class="news-list-item-date">
                                                    <?= $arItem['DATA']['DATE'] ?>
                                                </div>
                                            <?php } ?>
                                            <div class="news-list-item-description">
                                                <?= $arItem['DATA']['PREVIEW'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
