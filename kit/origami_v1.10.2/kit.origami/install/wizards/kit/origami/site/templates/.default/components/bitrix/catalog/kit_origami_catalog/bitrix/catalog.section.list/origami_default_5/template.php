<div class="catalog-main_page">
    <div class="row">
        <?foreach($arResult["SECTIONS"] as $arSection):?>
        <div class="catalog-section_item">
            <div class="catalog-section_wrapper">
                <div class="section_item-head">
                    <a class="catalog-section_item-image" href="<?=$arSection['SECTION_PAGE_URL']?>" title="<?=$arSection['NAME']?>">
                        <img class="popular_category_block_variant__canvas_img"
                             src="<?=$arSection['PICTURE']['SRC']?>"
                             alt="<?=$arSection['PICTURE']['ALT']?>"
                             title="<?=$arSection['PICTURE']['TITLE']?>"
                        >
                    </a>
                    <div class="items-titles">
                        <a href="<?=$arSection['SECTION_PAGE_URL']?>" title="<?=$arSection['NAME']?>" class="catalog-section_item-title">
                            <?=$arSection['NAME']?>
                        </a>
                        <?if(isset($arSection["CHILD_CATEGORIES"])):?>
                        <div class="catalog-section_item-description">
                            <div class="catalog_links">
                                <?foreach($arSection["CHILD_CATEGORIES"] as $arChild):?>
                                <div class="catalog-items_links">
                                    <div>
                                        <a href="<?=$arChild["SECTION_PAGE_URL"]?>" title="<?=$arChild["NAME"]?>">
                                            <span><?=$arChild["NAME"]?><?if($arChild["ELEMENT_CNT"]):?> <b><?=$arChild["ELEMENT_CNT"]?></b><?endif;?></span>
                                        </a>
                                    </div>
                                </div>
                                <?endforeach;?>
                            </div>
                        </div>
                        <div class="icon-nav_button-mobile_wrapper">
                            <i data-role="prop_angle" class="icon-nav_button"></i>
                        </div>
                        <?endif;?>
                    </div>
                </div>
                <?if($arSection["~DESCRIPTION"]):?>
                <div class="description_block">
                    <div class="description_text-wrapper">
                        <span class="description_text">
                            <span class="show_hide-buttons">
                                <span class="description-show_more-btn">
                                    <span class="dots">...</span>
                                    <span><?=GetMessage("ORIGAMI_SECT_TEMPLATE_5_MORE")?></span>
                                     <i data-role="prop_angle" class="icon-nav_button"></i>
                                </span>
                            </span>
                            <?=$arSection["~DESCRIPTION"]?>
                            <span class="show_hide-buttons ">
                                <span class="description-show_low-btn">
                                    <?=GetMessage("ORIGAMI_SECT_TEMPLATE_5_MIN")?>
                                    <i data-role="prop_angle" class="icon-nav_button"></i>
                                </span>
                            </span>
                        </span>
                    </div>
                </div>
                <?endif;?>
            </div>
            <?if(isset($arSection["CHILD_CATEGORIES"])):?>
            <div class="items_links-mobile">
                <?foreach($arSection["CHILD_CATEGORIES"] as $arChild):?>
                <a href="<?=$arChild["SECTION_PAGE_URL"]?>" class="catalog-items_links" title="<?=$arChild["NAME"]?>"><?=$arChild["NAME"]?></a>
                <?endforeach;?>
            </div>
            <?endif;?>
        </div>
        <?endforeach;?>
    </div>
</div>
