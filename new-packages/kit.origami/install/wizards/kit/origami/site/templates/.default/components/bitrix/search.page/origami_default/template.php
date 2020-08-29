<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

global $kitSeoMetaH1;

?>

<div class="search-page">
    <div class="catalog-search-title">
        <? if (\Bitrix\Main\Loader::includeModule('kit.seosearch') && $kitSeoMetaH1)
            echo $kitSeoMetaH1;
        else {
            echo Loc::getMessage("CATALOG_SEARCH_TITLE") . "" .
                $arResult["REQUEST"]["~QUERY"] . "" .
                Loc::getMessage("CATALOG_SEARCH_TITLE_PART") . " " .
                $arResult["NAV_RESULT"]->NavRecordCount;
        } ?>
    </div>
    <div class="catalog-search-title_empty">
        <?= Loc::getMessage("FORM_WAS_EMPTY") ?>
    </div>
    <div class="catalog-search-sub_title">
        <?= Loc::getMessage("TRY_ANOTHER_TITLE") ?>
    </div>
    <form method="get">
        <div class="catalog-search-line">
            <? if ($arParams["USE_SUGGEST"] === "Y"):
                if (strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"])) {
                    $arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
                    $obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
                    $obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
                }

                $APPLICATION->IncludeComponent(
                    "bitrix:search.suggest.input",
                    "",
                    array(
                        "NAME" => "q",
                        "VALUE" => $arResult["REQUEST"]["~QUERY"],
                        "INPUT_SIZE" => 40,
                        "DROPDOWN_SIZE" => 10,
                        "FILTER_MD5" => $arResult["FILTER_MD5"],
                    ),
                    $component, array("HIDE_ICONS" => "Y")
                );
            else: ?>
                <input type="text" name="q" class="catalog-search-input" value="<?= $arResult["REQUEST"]["QUERY"] ?>"
                       size="40" placeholder="<?= Loc::getMessage("INPUT_PLACEHOLDER") ?>">
            <? endif; ?>
            <button type="submit" class="catalog-search-btn" value="">
                <span class="catalog-search-btn_text">
                    <?= Loc::getMessage("SEARCH_GO") ?>
                </span>
                <svg class="icon-search" width="21" height="21">
                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_search"></use>
                </svg>
            </button>
        </div>

        <input type="hidden" name="how" value="<? echo $arResult["REQUEST"]["HOW"] == "d" ? "d" : "r" ?>"/>
    </form>

    <? if ($arResult["NAV_RESULT"]->NavRecordCount == 0): ?>
        <div class="no-search_result">
            <div class="icon_empty_search_result">
                <svg class="site-navigation__item-icon">
                    <use
                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_empty_search_result"></use>
                </svg>
            </div>
            <div class="text_block">
                <div class="no-result_title">
                    <?= Loc::getMessage("NO_RESULT_TITLE") ?>
                </div>
                <div class="no-result_subtitle">
                    <?= Loc::getMessage("TRY_ANOTHER_REQUEST") ?>
                </div>
            </div>
        </div>
    <? endif; ?>
</div>
