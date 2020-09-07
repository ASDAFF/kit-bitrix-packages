<div class="catalog-main_page">
    <div class="row">
        <?foreach($arResult["SECTIONS"] as $arSection):?>
        <div class="catalog-section_item">
            <div class="catalog_section-content_wrapper">
                <a class="catalog-section_item-image" href="<?=$arSection["SECTION_PAGE_URL"]?>" title="<?=$arSection["NAME"]?>">
                    <img class="popular_category_block_variant__canvas_img"
                         src="<?=$arSection['PICTURE']['SRC']?>"
                         alt="<?=$arSection['PICTURE']['ALT']?>"
                         title="<?=$arSection['PICTURE']['TITLE']?>"
                    >
                </a>
                <div class="items_titles">
                    <div class="catalog-section_item-title_wrapper">
                        <a href="<?=$arSection["SECTION_PAGE_URL"]?>" title="<?=$arSection['NAME']?>" class="catalog-section_item-title">
                            <?=$arSection['NAME']?>
                        </a>
                    </div>
                    <div class="icon-nav_button-mobile_wrapper">
                        <i data-role="prop_angle" class="icon-nav_button"></i>
                    </div>
                    <?if(isset($arSection["CHILD_CATEGORIES"])):?>
                    <div class="items_links">
                        <?foreach($arSection["CHILD_CATEGORIES"] as $arChild):?>
                        <a class="catalog-items_links" href="<?=$arChild["SECTION_PAGE_URL"]?>" title="<?=$arChild["NAME"]?>"><?=$arChild["NAME"]?><?if($arChild["ELEMENT_CNT"]):?> <b><?=$arChild["ELEMENT_CNT"]?></b><?endif;?></a>
                        <?endforeach;?>
                    </div>
                    <?endif;?>
                </div>
            </div>
            <?if(isset($arSection["CHILD_CATEGORIES"])):?>
            <div class="items_links-mobile">
                <?foreach($arSection["CHILD_CATEGORIES"] as $arChild):?>
                <a class="catalog-items_links" href="<?=$arChild["SECTION_PAGE_URL"]?>" title="<?=$arChild["NAME"]?>"><?=$arChild["NAME"]?></a>
                <?endforeach;?>
            </div>
            <?endif;?>
        </div>
        <?endforeach;?>
    </div>
</div>
