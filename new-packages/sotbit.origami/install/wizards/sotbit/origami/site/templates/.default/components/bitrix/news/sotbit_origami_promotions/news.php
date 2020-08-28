<?

use Bitrix\Main\Context;
use Sotbit\Origami\Helper\Config;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

global ${$arParams['FILTER_NAME']};

$objDateTime = \Bitrix\Main\Type\DateTime::createFromPhp(new \DateTime());
$currentDate = $objDateTime->toString();
$inactive = false;

$obPromo = CIBlockElement::GetList(Array(), array("IBLOCK_ID"=>$arParams["IDLOCK_ID"], "<DATE_ACTIVE_TO"=>$currentDate), false, false, array("ID", "IBLOCK_ID"));
if($masPromo = $obPromo->Fetch()) {
    $inactive = true;
}

$tab = 1;

$request = Context::getCurrent()->getRequest();
$value = $request->get("past");

if ($value == 'y') {
    ${$arParams['FILTER_NAME']} = ["<=DATE_ACTIVE_TO" => date
    ($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT"))), 'ACTIVE_DATE' => ''];
    $tab = 2;
} else {
    ${$arParams['FILTER_NAME']} = [0 => [
        "LOGIC" => "OR",
        [
            ">=DATE_ACTIVE_TO" => date
            ($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT"))),
        ],
        ["DATE_ACTIVE_TO" => false],
    ]];
}

${$arParams['FILTER_NAME']}['IBLOCK_ID'] = $arParams['IBLOCK_ID'];

$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    ${$arParams['FILTER_NAME']}['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID']
    ];
}

?>
<div class="row">
    <div class="col-sm-12">
        <div class="stocks__description">
            <div class="stocks__description-text-wrapper">
                <span class="stocks__description-text">
                 <? $APPLICATION->IncludeComponent(
                     "bitrix:main.include",
                     "",
                     [
                         "AREA_FILE_SHOW" => "page",
                         "AREA_FILE_SUFFIX" => "inc",
                         "EDIT_TEMPLATE" => "",
                     ]
                 ); ?>
                 <? $APPLICATION->IncludeComponent(
                     "bitrix:main.include",
                     "",
                     [
                         "AREA_FILE_SHOW" => "page",
                         "AREA_FILE_SUFFIX" => "inc",
                         "EDIT_TEMPLATE" => "",
                     ]
                 ); ?>
                 <? $APPLICATION->IncludeComponent(
                     "bitrix:main.include",
                     "",
                     [
                         "AREA_FILE_SHOW" => "page",
                         "AREA_FILE_SUFFIX" => "inc",
                         "EDIT_TEMPLATE" => "",
                     ]
                 ); ?>
              </span>
            </div>
            <div class="stocks__collapse-btns main-color">
                <span class="stocks__collapse-btns-show"><?= \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_BUTTON_SHOW_ALL') ?></span>
                <span class="stocks__collapse-btns-hide"><?= \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_BUTTON_COLLAPSE') ?></span>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12">
        <div class="promotions_list__tabs">
            <?
            if ($tab == 1) {
                ?>
                <span class="promotions_list__tab promotions_list__tab_active">
						<?= \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_TAB_1') ?>
					</span>
                <?if($inactive):?>
                    <a class="promotions_list__tab"
                       href="<?= $arResult['FOLDER'] ?>?past=y">
                        <?= \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_TAB_2') ?>
                    </a>
                <?endif;?>
                <?
            } else {
                ?>
                <a class="promotions_list__tab"
                   href="<?= $arResult['FOLDER'] ?>">
                    <?= \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_TAB_1') ?>
                </a>
                <span class="promotions_list__tab promotions_list__tab_active">
						<?= \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_TAB_2') ?>
					</span>
                <?
            }
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">
        <?
        $promotionsTemplate = Config::get('PROMOTION_LIST_TEMPLATE');
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            $promotionsTemplate,
            [
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "NEWS_COUNT" => $arParams["NEWS_COUNT"],
                "SORT_BY1" => $arParams["SORT_BY1"],
                "SORT_ORDER1" => $arParams["SORT_ORDER1"],
                "SORT_BY2" => $arParams["SORT_BY2"],
                "SORT_ORDER2" => $arParams["SORT_ORDER2"],
                "FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
                "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                "DETAIL_URL" => $arResult["FOLDER"]
                    . $arResult["URL_TEMPLATES"]["detail"],
                "SECTION_URL" => $arResult["FOLDER"]
                    . $arResult["URL_TEMPLATES"]["section"],
                "IBLOCK_URL" => $arResult["FOLDER"]
                    . $arResult["URL_TEMPLATES"]["news"],
                "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                "SET_TITLE" => $arParams["SET_TITLE"],
                "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                "MESSAGE_404" => $arParams["MESSAGE_404"],
                "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                "SHOW_404" => $arParams["SHOW_404"],
                "FILE_404" => $arParams["FILE_404"],
                "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
                "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
                "PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
                "ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
                "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
                "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
                "CHECK_DATES" => $arParams["CHECK_DATES"],
            ],
            $component
        ); ?>
    </div>
</div>
