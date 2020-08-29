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
?>

<?foreach ($arParams["ITEM_TAGS"] as $tag):?>

    <?if($arResult["TAGS_CHAIN"] && array_key_exists($tag, $arResult["TAGS_CHAIN"])):?>
        <a class="blog__sidebar-tag main-color_bg-on-hover" data-active="true" href="<?=$arResult["TAGS_CHAIN"][$tag]["TAG_WITHOUT"]?>"><?=$arResult["TAGS_CHAIN"][$tag]["TAG_NAME"]?>
            <svg class="icon_cancel_filter_small" width="6" height="6">
                <use
                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>
            </svg>
        </a>
    <?else:?>
        <a class="blog-tags__tag main-color_bg-on-hover" href="<?=$arResult["SEARCH"][$tag]["URL"]?>">
        <span><?=$arResult["SEARCH"][$tag]["NAME"]?></span></a>
    <?endif;?>
<?endforeach;?>