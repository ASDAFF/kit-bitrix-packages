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
                <div class="items_links-block">
                    <div class="catalog-section_item-title_wrapper">
                        <a href="<?=$arSection['SECTION_PAGE_URL']?>" class="catalog-section_item-title" title="<?=$arSection['NAME']?>">
                            <?=$arSection['NAME']?>
                        </a>
                    </div>
                    <div class="icon-nav_button-mobile_wrapper">
                        <i data-role="prop_angle" class="icon-nav_button"></i>
                    </div>
                    <?if(isset($arSection["CHILD_CATEGORIES"])):?>
                    <div class="items_links">
                        <?foreach($arSection["CHILD_CATEGORIES"] as $arChild):?>
                        <a href="<?=$arChild["SECTION_PAGE_URL"]?>" title="<?=$arChild["NAME"]?>" class="catalog-items_links"><?=$arChild["NAME"]?><?if($arChild["ELEMENT_CNT"]):?> <span>(<?=$arChild["ELEMENT_CNT"]?>)</span><?endif;?></a>
                        <?endforeach;?>
                    </div>
                    <?endif?>
                </div>
            </div>
            <?if(isset($arSection["CHILD_CATEGORIES"])):?>
            <div class="items_links-mobile">
                <?foreach($arSection["CHILD_CATEGORIES"] as $arChild):?>
                    <a href="<?=$arChild["SECTION_PAGE_URL"]?>" title="<?=$arChild["NAME"]?>" class="catalog-items_links"><?=$arChild["NAME"]?><?if($arChild["ELEMENT_CNT"]):?> <span>(<?=$arChild["ELEMENT_CNT"]?>)</span><?endif;?></a>
                <?endforeach;?>
            </div>
            <?endif?>
        </div>
        <?endforeach?>
    </div>
</div>