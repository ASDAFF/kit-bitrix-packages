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


<?if(!empty($arResult["SECTIONS"])):?>
<div class="puzzle_block popular_category__wrapper main-container">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=($arResult["SECTIONS"][0]["LIST_PAGE_URL"]) ? $arResult["SECTIONS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_CATALOG"]?>" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("KIT_POPULAR_CATEGORIES_LINK_TEXT");?>
            <i class="icon-nav_1"></i>
        </a>
    </p>
    <div class="popular_category_block_new">
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

            <a class="popular_category_new_two_link <?=$hoverClass?>" title="<?=$arSection["NAME"]?>" href="<?=$arSection["SECTION_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                <div class = "popular_category_new_two_img-container">
                    <img class="popular_category_new_two_img <?=$lazyClass?>"
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
                <p class="popular_category_block_new__canvas_title fonts__middle_text"><?=$arSection["NAME"]?></p>
            </a>

            <?
        }
        ?>
    </div>
</div>
<?endif;?>
