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
use Sotbit\Origami\Helper\Config;
Loc::loadMessages(__FILE__);

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>

<div class="puzzle_block popular_category__wrapper main-container">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=!empty($arParams["LINK_TO_THE_CATALOG"]) ? $arParams["LINK_TO_THE_CATALOG"] : $arResult["SECTIONS"][0]["LIST_PAGE_URL"]?>" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("SOTBIT_POPULAR_CATEGORIES_LINK_TEXT");?>
            <i class="icon-nav_1"></i>
        </a>
    </p>
    <div class="popular_category_block">
        <div class="row">
        <?
        foreach($arResult["SECTIONS"] as $arSection)
        {
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

            ?>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 mt-3 popular_category_block_variant__canvas" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                <a class="popular_category_block_variant__canvas_link <?=$hoverClass?>" href="<?=$arSection["SECTION_PAGE_URL"]?>" title="<?=$arSection["NAME"]?>">
                    <img class="popular_category_block_variant__canvas_img
                    <?=$lazyClass?>"
                        <?=$strLazyLoad?>
                         width="<?=$arSection["PICTURE"]["WIDTH"]?>"
                         height="<?=$arSection["PICTURE"]["HEIGHT"]?>"
                         alt="<?=$arSection["PICTURE"]["ALT"]?>"
                         title="<?=$arSection["PICTURE"]["TITLE"]?>"
                    >
                    <?if($lazyLoad):?>
                        <span class="loader-lazy"></span>
                    <?endif;?>
                </a>
                <div class="popular_category_block_variant__content">
                    <div class="popular_category_block_variant__canvas_title fonts__main_text">
                        <?=$arSection["NAME"]?>
                    </div>

                    <?if($arSection["CHILD_CATEGORIES"]):?>
                    <div class="popular_category_block_variant__tags">
                        <?
                        foreach($arSection["CHILD_CATEGORIES"] as $key => $child)
                        {
                            $this->AddEditAction($key, $child['EDIT_LINK'], CIBlock::GetArrayByID($child["IBLOCK_ID"], "SECTION_EDIT"));
                            $this->AddDeleteAction($key, $child['DELETE_LINK'], CIBlock::GetArrayByID($child["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
                            ?>
                            <a href="<?=$child["SECTION_PAGE_URL"]?>" class="popular_category_block_variant__tags_item fonts__middle_comment" title="<?=$child["NAME"]?>" id="<?=$this->GetEditAreaId($key);?>">
                                <?
                                echo $child["NAME"];
                                echo $child["ELEMENT_CNT"] ? "&nbsp;<span>".$child["ELEMENT_CNT"]."</span>" : "";
                                ?>
                            </a>
                            <?
                        }
                        ?>
                    </div>
                    <?endif?>

                    <div class="popular_category_block_variant__comment">
                        <?=$arSection["DESCRIPTION"]?>
                    </div>
                </div>
            </div>

            <?
        }
        ?>
        </div>
    </div>
</div>
