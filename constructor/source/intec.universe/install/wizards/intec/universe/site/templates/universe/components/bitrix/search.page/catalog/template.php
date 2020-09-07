<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\bitrix\Component;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if($arParams["SHOW_TAGS_CLOUD"] == "Y") {
    $arCloudParams = Array(
        "SEARCH" => $arResult["REQUEST"]["~QUERY"],
        "TAGS" => $arResult["REQUEST"]["~TAGS"],
        "CHECK_DATES" => $arParams["CHECK_DATES"],
        "arrFILTER" => $arParams["arrFILTER"],
        "SORT" => $arParams["TAGS_SORT"],
        "PAGE_ELEMENTS" => $arParams["TAGS_PAGE_ELEMENTS"],
        "PERIOD" => $arParams["TAGS_PERIOD"],
        "URL_SEARCH" => $arParams["TAGS_URL_SEARCH"],
        "TAGS_INHERIT" => $arParams["TAGS_INHERIT"],
        "FONT_MAX" => $arParams["FONT_MAX"],
        "FONT_MIN" => $arParams["FONT_MIN"],
        "COLOR_NEW" => $arParams["COLOR_NEW"],
        "COLOR_OLD" => $arParams["COLOR_OLD"],
        "PERIOD_NEW_TAGS" => $arParams["PERIOD_NEW_TAGS"],
        "SHOW_CHAIN" => "N",
        "COLOR_TYPE" => $arParams["COLOR_TYPE"],
        "WIDTH" => $arParams["WIDTH"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "RESTART" => $arParams["RESTART"],
    );

    if(is_array($arCloudParams["arrFILTER"]))
    {
        foreach($arCloudParams["arrFILTER"] as $strFILTER)
        {
            if($strFILTER=="main")
            {
                $arCloudParams["arrFILTER_main"] = $arParams["arrFILTER_main"];
            }
            elseif($strFILTER=="forum" && IsModuleInstalled("forum"))
            {
                $arCloudParams["arrFILTER_forum"] = $arParams["arrFILTER_forum"];
            }
            elseif(strpos($strFILTER,"iblock_")===0)
            {
                foreach($arParams["arrFILTER_".$strFILTER] as $strIBlock)
                    $arCloudParams["arrFILTER_".$strFILTER] = $arParams["arrFILTER_".$strFILTER];
            }
            elseif($strFILTER=="blog")
            {
                $arCloudParams["arrFILTER_blog"] = $arParams["arrFILTER_blog"];
            }
            elseif($strFILTER=="socialnetwork")
            {
                $arCloudParams["arrFILTER_socialnetwork"] = $arParams["arrFILTER_socialnetwork"];
            }
        }
    }
    $APPLICATION->IncludeComponent("bitrix:search.tags.cloud", ".default", $arCloudParams, $component, array("HIDE_ICONS" => "Y"));
}

?>
<div class="ns-bitrix c-search-page c-search-page-catalog" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="search-page-content">
                <div class="search-page-form">
                    <form action="" method="get">
                        <input type="hidden" name="tags" value="<?= $arResult['REQUEST']['TAGS'] ?>" />
                        <input type="hidden" name="how" value="<?= $arResult['REQUEST']['HOW'] == 'd' ? 'd' : 'r' ?>" />
                        <div class="intec-grid intec-grid-nowrap intec-grid-i-h-5">
                            <div class="intec-grid-item intec-grid-item-shrink-1 ">
                                <?php if($arParams["USE_SUGGEST"] === "Y") { ?>
                                    <?php if(strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"])) {
                                        $arResult['FILTER_MD5'] = $arResult['NAV_RESULT']->GetFilterMD5();
                                        $obSearchSuggest = new CSearchSuggest($arResult['FILTER_MD5'], $arResult['REQUEST']['~QUERY']);
                                        $obSearchSuggest->SetResultCount($arResult['NAV_RESULT']->NavRecordCount);
                                    } ?>
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:search.suggest.input',
                                        '',
                                        [
                                            'NAME' => 'q',
                                            'VALUE' => $arResult['REQUEST']['~QUERY'],
                                            'INPUT_SIZE' => -1,
                                            'DROPDOWN_SIZE' => 10,
                                            'FILTER_MD5' => $arResult['FILTER_MD5'],
                                        ],
                                        $component,
                                        ['HIDE_ICONS' => 'Y']
                                    ) ?>
                                <?php } else { ?>
                                    <input class="search-form-input intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-2 intec-ui-size-1" type="text" placeholder="<?= Loc::getMessage('CT_PLACEHOLDER') ?>" name="q" value="<?= $arResult['REQUEST']['QUERY'] ?>" />
                                <?php } ?>
                            </div>
                            <div class="intec-grid-item-auto">
                                <button class="search-form-button intec-ui intec-ui-control-button intec-ui-mod-round-2 intec-ui-scheme-current intec-ui-size-1" type="submit" value="<?= Loc::getMessage('CT_BSP_GO') ?>">
                                    <?=Loc::GetMessage('CT_BSP_GO');?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>