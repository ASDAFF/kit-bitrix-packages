<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 */

$sTag = 'div';

if (!empty($arParams['DETAIL_URL']))
    $sTag = 'a';

?>
<div class="contacts-offices">
    <?php if ($arResult['TITLE']['SHOW']) { ?>
        <div class="contacts-title">
            <?= $arResult['TITLE']['TEXT'] ?>
        </div>
    <?php } ?>
    <?php if ($arResult['DESCRIPTION']['SHOW']) { ?>
        <div class="contacts-description">
            <?= $arResult['DESCRIPTION']['TEXT'] ?>
        </div>
    <?php } ?>
    <div class="contacts-sections">
        <?php foreach($arResult['SECTIONS'] as $arSection) { ?>
            <?php if (count($arSection['ITEMS']) <= 0) continue; ?>
            <div class="contacts-section">
                <div class="contacts-section-title">
                    <?= $arSection['NAME'] ?>
                </div>
                <div class="contacts-offices-list">
                    <div class="contacts-offices-list-wrapper">
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
                                    'width' => 360,
                                    'height' => 245
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
                            <div class="contacts-office">
                                <div class="contacts-office-wrapper" id="<?= $sAreaId ?>">
                                    <?= Html::tag($sTag, null, [
                                        'href' => $sLink,
                                        'class' => 'contacts-image',
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sImage : null
                                        ],
                                        'style' => [
                                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sImage.'\')' : null
                                        ]
                                    ]) ?>
                                    <div class="contacts-information">
                                        <?php if (!empty($arItem['DATA']['ADDRESS'])) { ?>
                                            <div class="contacts-information-section contacts-address">
                                                <div class="contacts-information-title">
                                                    <i class="glyph-icon-location_2 intec-cl-text icon-contacts"></i>
                                                    <div class="contacts-information-text">
                                                        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_OFFICES_ADDRESS') ?>:
                                                    </div>
                                                </div>
                                                <div class="contacts-information-content">
                                                    <?= Html::tag($sTag, $arItem['DATA']['ADDRESS'], [
                                                        'class' => $sTag == 'a' ? 'intec-cl-text-hover' : null,
                                                        'href' => $sLink
                                                    ]) ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($arItem['DATA']['WORK_TIME'])) { ?>
                                            <div class="contacts-information-section contacts-work-time">
                                                <div class="contacts-information-title">
                                                    <i class="period-icon glyph-icon-clock intec-cl-text icon-contacts"></i>
                                                    <div class="contacts-information-text">
                                                        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_OFFICES_WORK_TIME') ?>:
                                                    </div>
                                                </div>
                                                <div class="contacts-information-content">
                                                    <?php foreach ($arItem['DATA']['WORK_TIME'] as $arValue) { ?>
                                                        <div class="contacts-work-time">
                                                            <?= !empty($arValue['RANGE']) ? $arValue['RANGE'].' '.$arValue['TIME'] : $arValue['TIME'] ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($arItem['DATA']['EMAIL']) || !empty($arItem['DATA']['PHONE'])) { ?>
                                            <div class="contacts-information-section contacts-contacts">
                                                <div class="contacts-information-title">
                                                    <i class="glyph-icon-mail intec-cl-text icon-contacts"></i>
                                                    <div class="contacts-information-text">
                                                        <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_OFFICES_CONTACTS') ?>:
                                                    </div>
                                                </div>
                                                <div class="contacts-information-content">
                                                    <?php if (!empty($arItem['DATA']['PHONE'])) { ?>
                                                        <div class="contacts-phone">
                                                            <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_OFFICES_PHONE') ?>:
                                                            <a href="tel:<?= $arItem['DATA']['PHONE']['VALUE'] ?>">
                                                                <?= $arItem['DATA']['PHONE']['DISPLAY'] ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (!empty($arItem['DATA']['EMAIL'])) { ?>
                                                        <div class="contacts-email">
                                                            <a href="mailto:<?= $arItem['DATA']['EMAIL'] ?>" class="contacts-email">
                                                                <?= $arItem['DATA']['EMAIL'] ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($arResult['MAP']['SHOW'] && !empty($arItem['DATA']['MAP'])) { ?>
                                            <?= Html::tag('a', Loc::getMessage('C_NEWS_LIST_CONTACTS_LIST_OFFICES_SHOW_ON_MAP'), [
                                                'class' => 'contacts-information-on-map',
                                                'href' => '#'.$sTemplateId.'_map',
                                                'data' => [
                                                    'latitude' => $arItem['DATA']['MAP']['LATITUDE'],
                                                    'longitude' => $arItem['DATA']['MAP']['LONGITUDE']
                                                ]
                                            ]) ?>
                                        <?php } ?>
                                    </div>
                                    <div class="intec-ui-clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>