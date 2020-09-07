<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arResult
 * @var array $arParams
 */

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arParams = ArrayHelper::merge([
    'FORM_ID' => null,
    'FORM_TEMPLATE' => null,
    'FORM_TITLE' => null,
    'FORM_CONSENT' => null,
    'FORM_BUTTON' => null,
    'FORM_POSITION' => 'top'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arForm = [
    'SHOW' => !empty($arParams['FORM_ID']),
    'POSITION' => ArrayHelper::fromRange(['top', 'bottom'], $arParams['FORM_POSITION']),
    'ID' => $arParams['FORM_ID'],
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'TITLE' => $arParams['FORM_TITLE'],
    'BUTTON' => !empty($arParams['FORM_BUTTON']) ? $arParams['FORM_BUTTON'] : Loc::getMessage('C_NEWS_REVIEWS_FORM_BUTTON_DEFAULT'),
    'PARAMETERS' => [
        'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_REVIEWS',
        'CONSENT_URL' => StringHelper::replaceMacros($arParams['FORM_CONSENT'], $arMacros)
    ]
];
?>
<?php $vReviews = function ($arForm) { ?>
    <div class="news-reviews intec-content">
        <div class="news-reviews-wrapper intec-content-wrapper">
            <div class="news-reviews-wrapper-2">
                <a class="intec-ui intec-ui-control-button intec-ui-size-4 intec-ui-scheme-current intec-ui-mod-round-5"
                   onclick="(function() {
                       universe.forms.show(<?= JavaScript::toObject([
                           'id' => $arForm['ID'],
                           'template' => $arForm['TEMPLATE'],
                           'parameters' => $arForm['PARAMETERS'],
                           'settings' => [
                               'title' => $arForm['TITLE']
                           ],
                           'fields' => []
                        ]) ?>);

                       if (window.yandex && window.yandex.metrika) {
                           window.yandex.metrika.reachGoal('forms.open');
                           window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arForm['ID'].'.open') ?>);
                       }
                   })()">
                    <?= $arForm['BUTTON'] ?>
                </a>
            </div>
        </div>
    </div>
<?php } ?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-news c-news-reviews p-news">
    <?php if ($arForm['SHOW'] && $arForm['POSITION'] === 'top') { ?>
        <?php $vReviews($arForm) ?>
    <?php } ?>
    <div class="news-content">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:news.list',
            '.default',
            array(
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'NEWS_COUNT' => $arParams['NEWS_COUNT'],
                'SORT_BY1' => $arParams['SORT_BY1'],
                'SORT_ORDER1' => $arParams['SORT_ORDER1'],
                'SORT_BY2' => $arParams['SORT_BY2'],
                'SORT_ORDER2' => $arParams['SORT_ORDER2'],
                'FIELD_CODE' => $arParams['LIST_FIELD_CODE'],
                'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'],
                'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['detail'],
                'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
                'IBLOCK_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news'],
                'DISPLAY_PANEL' => $arParams['DISPLAY_PANEL'],
                'SET_TITLE' => $arParams['SET_TITLE'],
                'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
                'MESSAGE_404' => $arParams['MESSAGE_404'],
                'SET_STATUS_404' => $arParams['SET_STATUS_404'],
                'SHOW_404' => $arParams['SHOW_404'],
                'FILE_404' => $arParams['FILE_404'],
                'INCLUDE_IBLOCK_INTO_CHAIN' => $arParams['INCLUDE_IBLOCK_INTO_CHAIN'],
                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],
                'CACHE_FILTER' => $arParams['CACHE_FILTER'],
                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
                'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
                'PAGER_TITLE' => $arParams['PAGER_TITLE'],
                'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
                'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
                'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
                'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
                'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
                'PAGER_BASE_LINK_ENABLE' => $arParams['PAGER_BASE_LINK_ENABLE'],
                'PAGER_BASE_LINK' => $arParams['PAGER_BASE_LINK'],
                'PAGER_PARAMS_NAME' => $arParams['PAGER_PARAMS_NAME'],
                'DISPLAY_DATE' => $arParams['DISPLAY_DATE'],
                'DISPLAY_NAME' => $arParams['DISPLAY_NAME'],
                'DISPLAY_PICTURE' => $arParams['DISPLAY_PICTURE'],
                'DISPLAY_PREVIEW_TEXT' => $arParams['DISPLAY_PREVIEW_TEXT'],
                'PREVIEW_TRUNCATE_LEN' => $arParams['PREVIEW_TRUNCATE_LEN'],
                'ACTIVE_DATE_FORMAT' => $arParams['LIST_ACTIVE_DATE_FORMAT'],
                'USE_PERMISSIONS' => $arParams['USE_PERMISSIONS'],
                'GROUP_PERMISSIONS' => $arParams['GROUP_PERMISSIONS'],
                'FILTER_NAME' => $arParams['FILTER_NAME'],
                'HIDE_LINK_WHEN_NO_DETAIL' => $arParams['HIDE_LINK_WHEN_NO_DETAIL'],
                'CHECK_DATES' => $arParams['CHECK_DATES'],

                'PROPERTY_PERSON_NAME' => $arParams['PROPERTY_PERSON_NAME'],
                'PROPERTY_PERSON_POSITION' => $arParams['PROPERTY_PERSON_POSITION'],
                'PROPERTY_SITE_URL' => $arParams['PROPERTY_SITE_URL'],
                'PROPERTY_DOCUMENT' => $arParams['PROPERTY_DOCUMENT'],
                'PROPERTY_SERVICES' => $arParams['PROPERTY_SERVICES'],
                'PROPERTY_CASES' => $arParams['PROPERTY_CASES'],
                'PROPERTY_VIDEO' => $arParams['PROPERTY_VIDEO'],
                'DOCUMENT_SHOW' => $arParams['LIST_DOCUMENT_SHOW'],
                'SERVICES_SHOW' => $arParams['LIST_SERVICES_SHOW'],
                'SERVICES_IBLOCK_TYPE' => $arParams['SERVICES_IBLOCK_TYPE'],
                'SERVICES_IBLOCK_ID' => $arParams['SERVICES_IBLOCK_ID'],
                'SERVICES_LINK_MODE' => $arParams['SERVICES_LINK_MODE'],
                'SERVICES_PROPERTY_LINK' => $arParams['SERVICES_PROPERTY_LINK'],
                'CASE_SHOW' => $arParams['LIST_CASE_SHOW'],
                'CASES_IBLOCK_TYPE' => $arParams['CASES_IBLOCK_TYPE'],
                'CASES_IBLOCK_ID' => $arParams['CASES_IBLOCK_ID'],
                'CASES_LINK_MODE' => $arParams['CASES_LINK_MODE'],
                'CASES_PROPERTY_LINK' => $arParams['CASES_PROPERTY_LINK'],
                'VIDEO_SHOW' => $arParams['LIST_VIDEO_SHOW'],
                'VIDEO_IBLOCK_TYPE' => $arParams['VIDEO_IBLOCK_TYPE'],
                'VIDEO_IBLOCK_ID' => $arParams['VIDEO_IBLOCK_ID'],
                'VIDEO_PROPERTY_LINK' => $arParams['VIDEO_PROPERTY_LINK']
            ),
            $component
        ) ?>
    </div>
    <?php if ($arForm['SHOW'] && $arForm['POSITION'] === 'bottom') { ?>
        <?php $vReviews($arForm) ?>
    <?php } ?>
</div>
