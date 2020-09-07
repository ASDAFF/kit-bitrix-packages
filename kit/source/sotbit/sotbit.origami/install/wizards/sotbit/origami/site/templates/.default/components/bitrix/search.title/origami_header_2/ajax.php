<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (empty($arResult["CATEGORIES"]))
	return;
?>
<div class="header-two__search-matches">
	<ul class="header-two__search-matches-list origami_main_scroll">
        <?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
            <?foreach($arCategory["ITEMS"] as $i => $arItem):?>
                <?if(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
                    $arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];
                ?>
					<li class="header-two__search-matches-item">
						<a href="<?=$arItem["URL"]?>">
							<div class="header-two__search-matches-img">
								<img src=<?=$arElement["PICTURE"]["src"]?> alt="">
							</div>
							<div class="header-two__search-matches-description">
								<p class="header-two__search-matches-name"><?=$arItem["NAME"]?></p>
								<?
	                            foreach($arElement["PRICES"] as $code=>$arPrice)
	                            {
	                                if ($arPrice["MIN_PRICE"] != "Y")
	                                    continue;

	                                if($arPrice["CAN_ACCESS"])
	                                {
	                                    if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
											<p class="header-two__search-matches-price">
	                                            <?=$arPrice["PRINT_DISCOUNT_VALUE"]?>
											</p>
	                                    <?else:?>
											<p class="header-two__search-matches-price">
												<?=$arPrice["PRINT_VALUE"]?>
											</p>
	                                    <?endif;
	                                }
	                                if ($arPrice["MIN_PRICE"] == "Y")
	                                    break;
	                            }
								?>
							</div>
						</a>
					</li>
                <?else:?>
			        <li class="header-two__search-matches-item">
				        <a href="<?=$arItem["URL"]?>">
					        <div class="header-two__search-matches-description">
						        <p class="header-two__search-matches-name"><?=$arItem["NAME"]?></p>
					        </div>
				        </a>
			        </li>
                <?endif;?>
            <?endforeach;?>
        <?endforeach;?>
    </ul>
    <script>
    scrollSearch();
    document.querySelector('.header-two__search-matches-list').addEventListener('wheel', function (evt) {
            evt.preventDefault();

        });
    </script>
		<div class="header-two__search-matches-item-btn">
			<a class="header-two__search-matches-btn" href="<?=$arResult["CATEGORIES"]['all']['ITEMS'][0]['URL']?>">
				<?=$arResult["CATEGORIES"]['all']['ITEMS'][0]['NAME']?>
			</a>
        </div>
        <div></div>
</div>
