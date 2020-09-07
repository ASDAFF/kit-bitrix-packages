<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

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

?>
<div class="ns-bitrix c-news-list c-news-list-reviews-list" id="<?= $sTemplateId ?>">
    <div class="news-list-items" data-role="items">
        <?php foreach ($arResult['ITEMS'] as $arItem) {

            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

            $arData = $arItem['DATA'];

            $sPicture = $arItem['PREVIEW_PICTURE'];

            if (empty($sPicture))
                $sPicture = $arItem['DETAIL_PICTURE'];

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
            <div class="news-list-item-wrap" id="<?= $sAreaId ?>">
                <div class="intec-content">
                    <div class="intec-content-wrapper">
                        <div class="news-list-item">
                            <div class="news-list-item-wrapper intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-i-h-20">
                                <div class="intec-grid-item-auto intec-grid-item-600-1">
                                    <?= Html::tag('a', '', [
                                        'class' => 'news-list-item-picture',
                                        'style' => 'background-image: url(\''.$sPicture.'\')',
                                        'href' => $arItem['DETAIL_PAGE_URL']
                                    ])?>
                                </div>
                                <div class="intec-grid-item intec-grid-item-600-1">
                                    <div class="news-list-item-description">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                    <div class="intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-i-h-20">
                                        <?php if ($arData['PERSON']['NAME']['SHOW'] && !empty($arData['PERSON']['NAME']['VALUE'])
                                            || $arData['PERSON']['POSITION']['SHOW'] && !empty($arData['PERSON']['POSITION']['VALUE'])
                                            || $arData['PERSON']['SITE_URL']['SHOW'] && !empty($arData['PERSON']['SITE_URL']['VALUE'])
                                        ) { ?>
                                        <div class="news-list-item-info intec-grid-item intec-grid-item-1000-2 intec-grid-item-800-1">
                                            <div class="news-list-item-info-wrapper">
                                                <?php if ($arData['PERSON']['NAME']['SHOW']) { ?>
                                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="news-list-item-person-name">
                                                        <?= $arData['PERSON']['NAME']['VALUE'] ?>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($arData['PERSON']['POSITION']['SHOW']) { ?>
                                                    <div class="news-list-item-person-position">
                                                        <?= $arData['PERSON']['POSITION']['VALUE'] ?>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($arData['PERSON']['SITE_URL']['SHOW']) { ?>
                                                    <a href="<?= $arData['PERSON']['SITE_URL']['VALUE'] ?>" class="news-list-item-site-url" rel="nofollow">
                                                        <i class="far fa-globe"></i><?= $arData['PERSON']['SITE_URL']['VALUE'] ?>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if (!empty($arData['SERVICES']) && $arVisual['SERVICES']['SHOW']) { ?>
                                            <div class="news-list-item-services intec-grid-item intec-grid-item-1000-2 intec-grid-item-800-1">
                                                <div class="news-list-item-services-wrapper">
                                                    <div class="news-list-item-services-title">
                                                        <?= Loc::getMessage('C_NEWS_LIST_REVIEWS_LIST_SERVICES_TITLE') ?>
                                                    </div>
                                                    <div class="news-list-item-services-items">
                                                        <?php foreach($arData['SERVICES'] as $arService) { ?>
                                                            <div class="news-list-item-services-item">
                                                                <?= Html::tag(!empty($arService['DETAIL_PAGE_URL']) ? 'a' : 'div',
                                                                    $arService['NAME'], [
                                                                    'href' => !empty($arService['DETAIL_PAGE_URL']) ? $arService['DETAIL_PAGE_URL'] : null,
                                                                    'class' => 'intec-ui-markup-a'
                                                                ]) ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ((!empty($arData['VIDEO']['VALUE']) && $arVisual['VIDEO']['SHOW'])
                                            || ($arData['DOCUMENT']['SHOW'] && $arVisual['DOCUMENT']['SHOW'])
                                            || (!empty($arData['CASE']) && $arVisual['CASE']['SHOW'])
                                        ) { ?>
                                            <div class="news-list-item-additional intec-grid-item-3 intec-grid-item-1000-1">
                                                <div class="news-list-item-additional-wrapper">
                                                    <?php if (!empty($arData['VIDEO']['VALUE']) && $arVisual['VIDEO']['SHOW']) { ?>
                                                        <div class="news-list-item-video-wrap">
                                                            <div class="news-list-item-video">
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
                                                        </div>
                                                    <?php } else if ($arData['DOCUMENT']['SHOW'] && $arVisual['DOCUMENT']['SHOW']) { ?>
                                                        <?php $arFileSmall = CFile::ResizeImageGet($arData['DOCUMENT']['VALUE'], array('width'=>320, 'height'=>320), BX_RESIZE_IMAGE_PROPORTIONAL, true) ?>
                                                        <?php $arFileBig = CFile::ResizeImageGet($arData['DOCUMENT']['VALUE'], array('width'=>1000, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, true) ?>
                                                        <div class="news-list-item-document-wrap">
                                                            <div class="news-list-item-document" data-role="document" data-src="<?= $arFileBig['src'] ?>">
                                                                <?= Html::beginTag('div', [
                                                                    'class' => 'news-list-item-document-wrapper',
                                                                    'style' => 'background-image: url(\''.$arFileSmall['src'].'\')',
                                                                ])?>
                                                                    <div class="news-list-item-document-button">
                                                                        <i class="far fa-search-plus"></i>
                                                                    </div>
                                                                    <img loading="lazy" src="<?= $arFileSmall['src'] ?>" alt="">
                                                                <?= Html::endTag('div') ?>
                                                            </div>
                                                        </div>
                                                    <?php } else if (!empty($arData['CASE']) && $arVisual['CASE']['SHOW']) {
                                                        $sTag = !empty($arData['CASE']['DETAIL_PAGE_URL']) ? 'a' : 'div';
                                                    ?>
                                                        <div class="news-list-item-portfolio">
                                                            <?= Html::tag( $sTag, Loc::getMessage('C_NEWS_LIST_REVIEWS_LIST_BUTTON_CASE'), [
                                                                'href' => $sTag == 'a' ? $arData['CASE']['DETAIL_PAGE_URL'] : null,
                                                                'class' => [
                                                                    'news-list-item-button-case',
                                                                    'intec-ui' => [
                                                                        '',
                                                                        'control-button',
                                                                        'scheme-current',
                                                                        'mod-round-half',
                                                                        'size-4',
                                                                        'markup-a'
                                                                    ]
                                                                ]
                                                            ]) ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="news-list-item-buttons intec-grid intec-grid-wrap intec-grid-i-10 intec-ui-p-t-30">
                                        <div class="intec-grid-item-auto intec-grid-item-600-1">
                                            <?= Html::tag( 'a', Loc::getMessage('C_NEWS_LIST_REVIEWS_LIST_BUTTON_MORE'), [
                                                'href' => $arItem['DETAIL_PAGE_URL'],
                                                'class' => [
                                                    'news-list-item-button-more',
                                                    'intec-ui' => [
                                                        '',
                                                        'control-button',
                                                        'mod-transparent',
                                                        'mod-round-half',
                                                        'size-4',
                                                    ],
                                                    'intec-cl' => [
                                                        'background-hover',
                                                        'border-hover',
                                                    ]
                                                ]
                                            ]) ?>
                                        </div>
                                        <?php if ((!empty($arData['VIDEO']['VALUE']) && $arVisual['VIDEO']['SHOW'])
                                            || ($arData['DOCUMENT']['SHOW'] && $arVisual['DOCUMENT']['SHOW'])
                                        ) { ?>
                                            <?php if (!empty($arData['CASE']) && $arVisual['CASE']['SHOW']) {
                                                $sTag = !empty($arData['CASE']['DETAIL_PAGE_URL']) ? 'a' : 'div';
                                            ?>
                                                <div class="intec-grid-item-auto intec-grid-item-600-1">
                                                    <?= Html::tag( $sTag, Loc::getMessage('C_NEWS_LIST_REVIEWS_LIST_BUTTON_CASE'), [
                                                        'href' => $sTag == 'a' ? $arData['CASE']['DETAIL_PAGE_URL'] : null,
                                                        'class' => [
                                                            'news-list-item-button-case',
                                                            'intec-ui' => [
                                                                '',
                                                                'control-button',
                                                                'scheme-current',
                                                                'mod-round-half',
                                                                'size-4',
                                                                'markup-a'
                                                            ]
                                                        ]
                                                    ]) ?>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>