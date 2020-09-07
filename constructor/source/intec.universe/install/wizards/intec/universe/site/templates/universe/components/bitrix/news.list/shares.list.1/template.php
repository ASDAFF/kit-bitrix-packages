<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-news-list',
        'c-news-list-shares-list-1'
    ]
]) ?>
    <?php if ($arVisual['NAVIGATION']['TOP']['SHOW']) { ?>
        <div class="news-list-navigation news-list-navigation-top">
            <?= $arResult['NAV_STRING'] ?>
        </div>
    <?php } ?>
    <div class="news-list-content intec-content intec-content-visible">
        <div class="news-list-content-wrapper intec-content-wrapper">
            <?php if ($arVisual['IBLOCK']['DESCRIPTION']['SHOW'] && !empty($arResult['DESCRIPTION'])) { ?>
                <div class="news-list-description">
                    <?= $arResult['DESCRIPTION'] ?>
                </div>
            <?php } ?>
            <div class="news-list-items">
                <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                <?php
                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sImage = $arItem['PREVIEW_PICTURE'];

                    if (empty($sImage))
                        $sImage = $arItem['DETAIL_PICTURE'];

                    if (!empty($sImage)) {
                        $sImage = CFile::ResizeImageGet($sImage, [
                            'width' => 380,
                            'height' => 270
                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                        if (!empty($sImage))
                            $sImage = $sImage['src'];
                    }

                    if (empty($sImage))
                        $sImage = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                ?>
                    <?= Html::beginTag('div', [
                        'id' => $sAreaId,
                        'class' => 'news-list-item'
                    ]) ?>
                        <div class="news-list-item-wrapper intec-grid intec-grid-nowrap intec-grid-500-wrap">
                            <?= Html::tag('a', null, [
                                'href' => $arItem['DETAIL_PAGE_URL'],
                                'class' => [
                                    'news-list-item-picture',
                                    'intec-grid-item' => [
                                        'auto',
                                        '500-1'
                                    ]
                                ],
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sImage : null
                                ],
                                'style' => [
                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sImage.'\')' : null
                                ]
                            ]) ?>
                            <div class="news-list-item-information intec-grid-item">
                                <?php if ($arVisual['DATE']['SHOW'] && !empty($arItem['DATA']['DATE'])) { ?>
                                    <div class="news-list-item-date">
                                        <?= $arItem['DATA']['DATE'] ?>
                                    </div>
                                <?php } ?>
                                <div class="news-list-item-name">
                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                        <?= $arItem['NAME'] ?>
                                    </a>
                                </div>
                                <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) { ?>
                                    <div class="news-list-item-description">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php if ($arVisual['NAVIGATION']['BOTTOM']['SHOW']) { ?>
        <div class="news-list-navigation news-list-navigation-bottom">
            <?= $arResult['NAV_STRING'] ?>
        </div>
    <?php } ?>
<?= Html::endTag('div') ?>
