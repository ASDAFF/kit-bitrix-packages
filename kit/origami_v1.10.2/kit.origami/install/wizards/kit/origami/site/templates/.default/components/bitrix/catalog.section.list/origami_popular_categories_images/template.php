<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
Loc::loadMessages(__FILE__);
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>

<div class="puzzle_block popular_category__wrapper main-container">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=$arParams["LINK_TO_THE_CATALOG"] ? $arParams["LINK_TO_THE_CATALOG"] : $arResult["SECTIONS"][0]["LIST_PAGE_URL"]?>" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("KIT_POPULAR_CATEGORIES_LINK_TEXT");?>
            <i class="icon-nav_1"></i>
        </a>
    </p>
    <div class="popular_category_block-two">
        <div class="row">
        <?
        $i = 0;
        foreach($arResult["SECTIONS"] as $arSection)
        {
            $i++;
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));

            if($lazyLoad)
            {
                $lazyClass = 'lazy';
                $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arSection["PICTURE"]["SRC"].'"';
            }else{
                $lazyClass = '';
                $strLazyLoad = 'src="'.$arSection["PICTURE"]["SRC"].'"';
            }

            if($i <= 3)
                $divClass = "col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 mt-3 popular_category_block-two__canvas";
            else
                $divClass = "col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 mb-3 mt-3 popular_category_block-two__canvas";
            ?>

            <div class="<?=$divClass?>">
                <a class="popular_category_block-two__canvas_link <?=$hoverClass?>" title="<?=$arSection["NAME"]?>" href="<?=$arSection["SECTION_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                    <div class="popular_category_block-two__canvas_block_img">
                        <div class="popular_category_block-two__canvas_title fonts__small_title">
                            <?=$arSection["NAME"]?>
                        </div>
                        <img class="popular_category_block-two__canvas_img <?=$lazyClass?>"
                            <?=$strLazyLoad?>
                             width="<?=$arSection["PICTURE"]["WIDTH"]?>"
                             height="<?=$arSection["PICTURE"]["HEIGHT"]?>"
                             alt="<?=$arSection["PICTURE"]["ALT"]?>"
                             title="<?=$arSection["PICTURE"]["TITLE"]?>"
                        >
                        <?if($lazyLoad):?>
                            <span class="loader-lazy"></span>
                        <?endif;?>
                    </div>
                </a>
            </div>

            <?
        }
        ?>
        </div>
    </div>
</div>
