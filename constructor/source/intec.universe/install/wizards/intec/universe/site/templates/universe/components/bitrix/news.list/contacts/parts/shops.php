<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 * @var Closure $getMapCoordinates
 */

$sTag = 'div';

if (!empty($arParams['DETAIL_URL']))
    $sTag = 'a';

?>
<div class="contacts-shops">
    <div class="contacts-title">
        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_SHOPS') ?>
    </div>
    <div class="contacts-sections">
        <ul class="intec-ui intec-ui-control-tabs intec-ui-mod-block intec-ui-scheme-current" role="tablist">
            <?php $bSectionFirst = true ?>
            <?php foreach($arResult['SECTIONS'] as $arSection) { ?>
                <?php if (count($arSection['ITEMS']) <= 0) continue; ?>
                <?= Html::beginTag('li', [
                    'class' => Html::cssClassFromArray([
                        'intec-ui-part-tab' => true,
                        'active' => $bSectionFirst
                    ], true)
                ]) ?>
                    <?= Html::tag('a', $arSection['NAME'], [
                        'href' => '#contacts-'.$sTemplateId.'-section-'.$arSection['ID'],
                        'role' => 'tab',
                        'data' => [
                            'toggle' => 'tab'
                        ]
                    ]) ?>
                <?= Html::endTag('li') ?>
                <?php $bSectionFirst = false ?>
            <?php } ?>
        </ul>
        <div class="intec-ui intec-ui-control-tabs-content">
            <?php $bSectionFirst = true ?>
            <?php foreach($arResult['SECTIONS'] as $arSection) { ?>
                <?php if (count($arSection['ITEMS']) <= 0) continue; ?>
                <?= Html::beginTag('div', [
                    'id' => 'contacts-'.$sTemplateId.'-section-'.$arSection['ID'],
                    'class' => Html::cssClassFromArray([
                        'intec-ui-part-tab' => true,
                        'active' => $bSectionFirst
                    ], true),
                    'role' => 'tabpanel'
                ]) ?>
                    <div class="contacts-shops-list">
                        <div class="contacts-shops-list-wrapper row">
                            <?php foreach ($arSection['ITEMS'] as $arItem) { ?>
                            <?php
                                $sId = $sTemplateId.'_'.$arItem['ID'];
                                $sAreaId = $this->GetEditAreaId($sId);
                                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                                $iStoreId = $arItem['ID'];
                                $sImage = $arItem['PREVIEW_PICTURE'];
                                $sLink = null;

                                if (!empty($arItem['DATA']['STORE_ID']))
                                    $iStoreId = $arItem['DATA']['STORE_ID'];

                                if (empty($sImage))
                                    $sImage = $arItem['DETAIL_PICTURE'];

                                if (!empty($sImage)) {
                                    $sImage = CFile::ResizeImageGet($sImage, [
                                        'width' => 240,
                                        'height' => 240
                                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                    if (!empty($sImage))
                                        $sImage = $sImage['src'];
                                }

                                if (empty($sImage))
                                    $sImage = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                                if (!empty($arParams['DETAIL_URL']))
                                    $sLink = StringHelper::replace($arParams['DETAIL_URL'], [
                                        '#SITE_DIR#' => SITE_DIR,
                                        '#ELEMENT_ID#' => $iStoreId,
                                        '#ID#' => $iStoreId
                                    ]);
                            ?>
                                <div class="contacts-shop col-xs-12 col-md-6 col-lg-4">
                                    <div class="contacts-shop-wrapper" id="<?= $sAreaId ?>">
                                        <div class="contacts-image">
                                            <?= Html::tag($sTag, null, [
                                                'href' => $sLink,
                                                'class' => 'contacts-image-wrapper',
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sImage : null
                                                ],
                                                'style' => [
                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sImage.'\')' : null
                                                ]
                                            ]) ?>
                                        </div>
                                        <div class="contacts-information">
                                            <?php if (!empty($arItem['DATA']['ADDRESS'])) { ?>
                                                <?= Html::tag($sTag, $arItem['DATA']['ADDRESS'], [
                                                    'href' => $sLink,
                                                    'class' => $sTag == 'a' ? 'contacts-address intec-cl-text-hover' : 'contacts-address'
                                                ]) ?>
                                            <?php } ?>
                                            <?php if (!empty($arItem['DATA']['PHONE'])) { ?>
                                                <div class="contacts-phone">
                                                    <span>
                                                        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_SHOPS_PHONE') ?>:
                                                    </span>
                                                    <span>
                                                        <a href="tel:<?= $arItem['DATA']['PHONE']['VALUE'] ?>">
                                                            <?= $arItem['DATA']['PHONE']['DISPLAY'] ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($arItem['DATA']['EMAIL'])) { ?>
                                                <a href="mailto:<?= $arItem['DATA']['EMAIL'] ?>" class="contacts-email intec-cl-text">
                                                    <?= $arItem['DATA']['EMAIL'] ?>
                                                </a>
                                            <?php } ?>
                                            <?php if ($arResult['MAP']['SHOW'] && !empty($arItem['DATA']['MAP'])) { ?>
                                                <?= Html::tag('a', Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_SHOPS_SHOW_ON_MAP'), [
                                                    'class' => 'contacts-on-map',
                                                    'href' => '#'.$sTemplateId.'_map',
                                                    'data' => [
                                                        'latitude' => $arItem['DATA']['MAP']['LATITUDE'],
                                                        'longitude' => $arItem['DATA']['MAP']['LONGITUDE']
                                                    ]
                                                ]) ?>
                                            <?php } ?>
                                        </div>
                                        <div class="intec-ui-clearfix"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
                <?php $bSectionFirst = false ?>
            <?php } ?>
        </div>
    </div>
</div>