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
<?if($arResult["SEARCH"]):?>
    <div class="blog__sidebar-item">
        <div class="blog__sidebar-item-title">
            <span><?=GetMessage("BLOG_TAGS_TITLE");?></span>
        </div>
        <div class="blog__sidebar-item-tags-wrapper">
            <div class="blog__sidebar-item-tags">
                <?if($arParams["SHOW_CHAIN"] != "N" && !empty($arResult["TAGS_CHAIN"])):?>
                    <?foreach ($arResult["TAGS_CHAIN"] as $tags):?>
                    <?
                        $tagsArray[]=$tags["TAG_NAME"];
                        ?>
                        <a class="blog__sidebar-tag main-color_bg-on-hover" data-active="true" href="<?=$tags["TAG_WITHOUT"]?>"><?=$tags["TAG_NAME"]?>
                        <svg class="icon_cancel_filter_small" width="6" height="6">
                            <use
                                    xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>
                        </svg>
                        </a>
                    <?endforeach;?>
                <?endif;?>
                <?if(is_array($arResult["SEARCH"]) && !empty($arResult["SEARCH"])):?>
                    <?foreach ($arResult["SEARCH"] as $key => $res):?>
                        <?if(!in_array($res["NAME"],$tagsArray)):?>
                        <a class="blog__sidebar-tag main-color_bg-on-hover" href="<?=$res["URL"]?>" rel="nofollow"><?=$res["NAME"]?>
                            <svg class="icon_cancel_filter_small" width="6" height="6">
                                <use
                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>
                            </svg>
                        </a>
                        <?endif;?>
                    <?endforeach;?>
                <?endif;?>
            </div>
        </div>
        <span class="blog__sidebar-items-tags-show-all"></span>
    </div>
<?endif;?>