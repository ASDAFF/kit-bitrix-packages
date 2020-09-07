<div class="catalog-main_page">
    <div class="row">
        <?foreach($arResult["SECTIONS"] as $arSection):?>
        <div class="catalog-section_item">
            <div class="catalog-section_wrapper">
                <div class="section_item-head">
                    <a class="catalog-section_item-image" href="#">
                        <img class="popular_category_block_variant__canvas_img"
                             src="<?=$arSection['PICTURE']['SRC']?>"
                             alt="<?=$arSection['PICTURE']['ALT']?>"
                             title="<?=$arSection['PICTURE']['TITLE']?>"
                        >
                    </a>
                    <div class="items_titles">
                        <div class="catalog-section_item-title_wrapper">
                            <a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="catalog-section_item-title" title="<?=$arSection["NAME"]?>">
                                <?=$arSection["NAME"]?>
                            </a>
                        </div>
                        <div class="icon-nav_button-mobile_wrapper">
                            <i data-role="prop_angle" class="icon-nav_button"></i>
                        </div>
                        <?if(isset($arSection["CHILD_CATEGORIES"])):?>
                        <div class="catalog-section_item-description">
                            <div class="catalog_links">
                                <?foreach($arSection["CHILD_CATEGORIES"] as $arChildren):?>
                                <div class="catalog-items_links">
                                    <div>
                                        <a href="<?=$arChildren["SECTION_PAGE_URL"]?>" title="<?=$arChildren["NAME"]?>">
                                            <div class="catalog-items_links-img">
                                                <img class="popular_category_block_variant__canvas_img"
                                                     src="<?=$arChildren["PICTURE"]["SRC"]?>"
                                                     alt="<?=$arChildren['PICTURE']['ALT']?>"
                                                     title="<?=$arChildren['PICTURE']['TITLE']?>"
                                                >
                                            </div>
                                            <b><?=$arChildren["NAME"]?></b>
                                        </a>
                                        <?if(isset($arChildren["CHILD_CATEGORIES"])):?>
                                            <i data-role="prop_angle" class="icon-nav_button"></i>
                                        <?endif;?>
                                    </div>
                                    <?if(isset($arChildren["CHILD_CATEGORIES"])):?>
                                    <div class="catalog-items-items_list-wrapper">
                                        <ul class="catalog-items-items_list">
                                            <?foreach($arChildren["CHILD_CATEGORIES"] as $arChild):?>
                                            <li class="catalog-items-item_section">
                                                <a href="<?=$arChild["SECTION_PAGE_URL"]?>" title="<?=$arChild["NAME"]?>"><?=$arChild["NAME"]?></a>
                                            </li>
                                            <?endforeach;?>
                                        </ul>
                                    </div>
                                    <?endif;?>
                                </div>
                                <?endforeach;?>
                            </div>
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
                                    <span><?=GetMessage("ORIGAMI_SECT_TEMPLATE_3_MORE")?></span>
                                    <i data-role="prop_angle" class="icon-nav_button"></i>
                                </span>
                             </span>
                            <?=$arSection["~DESCRIPTION"]?>
                             <span class="show_hide-buttons ">
                                <span class="description-show_low-btn">
                                    <?=GetMessage("ORIGAMI_SECT_TEMPLATE_3_MIN")?>
                                    <i data-role="prop_angle" class="icon-nav_button"></i>
                                </span>
                             </span>
                         </span>
                        </span>
                    </div>
                </div>
                <?endif;?>
            </div>
            <?if(isset($arSection["CHILD_CATEGORIES"])):?>
            <ul class="items_links-mobile">
                <?foreach($arSection["CHILD_CATEGORIES"] as $arChild):?>
                <li>
                    <a href="<?=$arChild["SECTION_PAGE_URL"]?>" class="catalog-items_links" title="<?=$arChild["NAME"]?>"><?=$arChild["NAME"]?></a>
                </li>
                <?endforeach;?>
            </ul>
            <?endif;?>
        </div>
        <?endforeach;?>
    </div>
</div>
