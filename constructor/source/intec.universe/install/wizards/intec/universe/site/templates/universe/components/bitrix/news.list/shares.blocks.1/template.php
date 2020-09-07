<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
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
        'c-news-list-shares-blocks-1'
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
            <div class="news-list-items intec-grid intec-grid-wrap intec-grid-i-10">
                <?php foreach($arResult['ITEMS'] as $arItem) { ?>
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
                            'width' => 640,
                            'height' => 480
                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                        if (!empty($sImage))
                            $sImage = $sImage['src'];
                    }

                    if (empty($sImage))
                        $sImage = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                ?>
                    <div class="news-list-item intec-grid-item-2 intec-grid-item-720-1">
                        <div class="news-list-item-wrapper" id="<?= $sAreaId ?>">
                            <a class="news-list-item-wrapper-2" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                <?= Html::tag('div', null, [
                                    'class' => 'news-list-item-picture',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sImage : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sImage.'\')' : null
                                    ]
                                ]) ?>
                                <div class="news-list-item-gradient"></div>
                                <div class="news-list-item-gradient-2"></div>
                                <div class="news-list-item-information">
                                    <div class="news-list-item-name">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                    <?php if (!empty($arItem['DATA']['DURATION'])) {?>
                                        <div class="news-list-item-duration">
                                            <?= $arItem['DATA']['DURATION'] ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arItem['DATA']['DISCOUNT'])) {?>
                                        <span class="news-list-item-discount intec-cl-background">
                                        <?= Loc::getMessage('C_NEWS_LIST_SHARES_BLOCKS_1_DISCOUNT_PREFIX').' '.$arItem['DATA']['DISCOUNT'] ?>
                                    </span>
                                    <? } ?>
                                </div>
                            </a>
                        </div>
                    </div>
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