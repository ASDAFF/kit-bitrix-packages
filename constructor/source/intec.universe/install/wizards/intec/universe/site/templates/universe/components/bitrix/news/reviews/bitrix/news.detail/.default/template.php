<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
$arData = $arResult['DATA'];

$sPicture = $arResult['DETAIL_PICTURE'];

if (empty($sPicture))
    $sPicture = $arResult['PREVIEW_PICTURE'];

if (!empty($sPicture)) {
    $sPicture = CFile::ResizeImageGet($sPicture, [
        'width' => 96,
        'height' => 96
    ], BX_RESIZE_IMAGE_PROPORTIONAL);

    if (!empty($sPicture))
        $sPicture = $sPicture['src'];
}

if (empty($sPicture))
    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

?>
<div class="ns-bitrix c-news-detail c-news-detail-default" id="<?= $sTemplateId ?>">
    <div class="news-detail" data-role="item">
        <div class="news-detail-preview">
            <div class="news-detail-preview-wrapper intec-content intec-content-visible">
                <div class="news-detail-preview-wrapper-2 intec-content-wrapper">
                    <div class="news-detail-preview-wrapper-3 intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-i-h-20">
                        <div class="news-detail-item-picture-wrap intec-grid-item-auto intec-grid-item-600-1">
                            <?= Html::tag('div', '', [
                                'class' => 'news-detail-item-picture',
                                'style' => 'background-image: url(\''.$sPicture.'\')'
                            ])?>
                        </div>
                        <div class="news-detail-text-wrap intec-grid-item intec-grid-item-600-1">
                            <div class="news-detail-description">
                                <?= $arResult['DETAIL_TEXT'] ?>
                            </div>
                            <div class="news-detail-person">
                                <?php if ($arData['PERSON']['NAME']['SHOW']) { ?>
                                    <div class="news-detail-person-name">
                                        <?= $arData['PERSON']['NAME']['VALUE'] ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arData['PERSON']['POSITION']['SHOW']) { ?>
                                    <div class="news-detail-person-position">
                                        <?= $arData['PERSON']['POSITION']['VALUE'] ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arData['PERSON']['SITE_URL']['SHOW']) { ?>
                                    <a class="news-detail-person-site-url" href="<?= $arData['PERSON']['SITE_URL']['VALUE'] ?>" rel="nofollow">
                                        <i class="far fa-globe"></i><?= $arData['PERSON']['SITE_URL']['VALUE'] ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($arVisual['DOCUMENT']['SHOW'] || $arVisual['SERVICES']['SHOW'] || $arVisual['CASES']['SHOW'] || $arVisual['VIDEO']['SHOW']) { ?>
            <div class="news-detail-information intec-content intec-content-visible">
                <div class="news-detail-information-wrapper intec-content-wrapper">
                    <?php if ($arData['DOCUMENT']['SHOW'] && $arVisual['DOCUMENT']['SHOW']) { ?>
                        <?php $sFile = CFile::GetPath($arData['DOCUMENT']['VALUE']) ?>
                        <div class="news-detail-document-wrap">
                            <div class="news-detail-document" data-role="document" data-src="<?= $sFile ?>">
                                <div class="news-detail-document-button">
                                    <i class="far fa-search-plus"></i>
                                </div>
                                <img loading="lazy" title="" alt="document" src="<?= $sFile ?>">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($arData['SERVICES']['SHOW'] && $arVisual['SERVICES']['SHOW'] || $arData['CASES']['SHOW'] && $arVisual['CASES']['SHOW']) { ?>
                        <div class="news-detail-elements intec-grid intec-grid-wrap intec-grid-i-h-20">
                            <?php if ($arData['SERVICES']['SHOW'] && $arVisual['SERVICES']['SHOW']) { ?>
                                <div class="news-detail-services intec-grid-item intec-grid-item-600-1">
                                    <div class="news-detail-services-title">
                                        <?= Loc::getMessage('C_NEWS_LIST_REVIEWS_LIST_SERVICES_TITLE') ?>
                                    </div>
                                    <div class="news-detail-services-items">
                                        <?php $APPLICATION->IncludeComponent(
                                            'intec.universe:main.categories',
                                            'template.14',
                                            [
                                                'IBLOCK_TYPE' => $arParams['SERVICES_IBLOCK_TYPE'],
                                                'IBLOCK_ID' => $arParams['SERVICES_IBLOCK_ID'],
                                                'SECTIONS_MODE' => 'id',
                                                'SECTIONS' => null,
                                                'FILTER' => [
                                                    'ID' => $arData['SERVICES']['VALUE']
                                                ],
                                                'LINK_MODE' => $arParams['SERVICES_LINK_MODE'],
                                                'PROPERTY_LINK' => $arParams['SERVICES_PROPERTY_LINK'],
                                                'HEADER_SHOW' => 'N',
                                                'DESCRIPTION_SHOW' => 'N',
                                                'ELEMENTS_COUNT' => '',
                                                'CACHE_TYPE' => 'N',
                                                'PICTURE_SHOW' => 'Y',
                                                'PREVIEW_SHOW' => 'Y',
                                                'LINK_USE' => 'Y',
                                                'COLUMNS' => 1,
                                                'SORT_BY' => $arParams['SERVICES_SORT_BY'],
                                                'SORT_ORDER' => $arParams['SERVICES_SORT_ORDER']
                                            ],
                                            $component
                                        ) ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($arData['CASES']['SHOW'] && $arVisual['CASES']['SHOW']) { ?>
                                <div class="news-detail-cases intec-grid-item intec-grid-item-600-1">
                                    <div class="news-detail-cases-title">
                                        <?= Loc::getMessage('C_NEWS_LIST_REVIEWS_LIST_CASES_TITLE') ?>
                                    </div>
                                    <div class="news-detail-cases-items">
                                        <?php $APPLICATION->IncludeComponent(
                                            'intec.universe:main.categories',
                                            'template.14',
                                            [
                                                'IBLOCK_TYPE' => $arParams['CASES_IBLOCK_TYPE'],
                                                'IBLOCK_ID' => $arParams['CASES_IBLOCK_ID'],
                                                'SECTIONS_MODE' => 'id',
                                                'SECTIONS' => null,
                                                'FILTER' => [
                                                    'ID' => $arData['CASES']['VALUE']
                                                ],
                                                'LINK_MODE' => $arParams['CASES_LINK_MODE'],
                                                'PROPERTY_LINK' => $arParams['CASES_PROPERTY_LINK'],
                                                'HEADER_SHOW' => 'N',
                                                'DESCRIPTION_SHOW' => 'N',
                                                'ELEMENTS_COUNT' => '',
                                                'CACHE_TYPE' => 'N',
                                                'PICTURE_SHOW' => 'Y',
                                                'PREVIEW_SHOW' => 'Y',
                                                'LINK_USE' => 'Y',
                                                'COLUMNS' => 1,
                                                'SORT_BY' => $arParams['CASES_SORT_BY'],
                                                'SORT_ORDER' => $arParams['CASES_SORT_ORDER']
                                            ],
                                            $component
                                        ) ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arData['VIDEO']['VALUE']) && $arVisual['VIDEO']['SHOW']) { ?>
                        <div class="news-detail-video">
                            <?php $APPLICATION->IncludeComponent(
                                "intec.universe:main.video",
                                "template.1",
                                array(
                                    "COMPONENT_TEMPLATE" => "template.1",
                                    "IBLOCK_TYPE" => $arParams['VIDEO_IBLOCK_TYPE'],
                                    "IBLOCK_ID" => $arParams['VIDEO_IBLOCK_ID'],
                                    "ELEMENT" => $arData['VIDEO']['VALUE'],
                                    "PROPERTY_LINK" => $arParams['VIDEO_PROPERTY_LINK'],
                                    "PROPERTY_IMAGE_USE" => "",
                                    "HEADER_SHOW" => "N",
                                    "DESCRIPTION_SHOW" => "N",
                                    "QUALITY" => "maxresdefault",
                                    "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                                    "CACHE_TIME" => $arParams['CACHE_TIME'],
                                    "BUTTON_COLOR_THEME" => "light"
                                ),
                                $component
                            );?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>