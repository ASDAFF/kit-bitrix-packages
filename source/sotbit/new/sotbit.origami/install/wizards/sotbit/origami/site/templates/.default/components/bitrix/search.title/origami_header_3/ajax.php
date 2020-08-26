<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (empty($arResult["CATEGORIES"]))
	return;
?>
<div class="header__search-matches">
	<ul class="header__search-matches-list origami_main_scroll">
        <?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
            <?if($category_id !== 'all'):?>
                <?foreach($arCategory["ITEMS"] as $i => $arItem):?>
                    <?if (isset($arItem['TYPE']) && $arItem['TYPE'] == 'all')
                        unset($arCategory["ITEMS"][$i])
                    ?>
                <?endforeach;?>

                <?foreach($arCategory["ITEMS"] as $i => $arItem):?>
                    <?if(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
                        $arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];
                        ?>
                        <li class="header__search-matches-item">
                            <a class="header__search-matches-item-link" href="<?=$arItem["URL"]?>">
                                <div class="header__search-matches-img">
                                    <img src=<?=$arElement["PICTURE"]["src"]?> alt="">
                                </div>
                                <div class="header__search-matches-description">
                                    <p class="header__search-matches-name"><?=$arItem["NAME"]?></p>
                                    <?
                                    foreach($arElement["PRICES"] as $code=>$arPrice)
                                    {
                                        if ($arPrice["MIN_PRICE"] != "Y")
                                            continue;

                                        if($arPrice["CAN_ACCESS"])
                                        {
                                            if($arPrice["DISCOUNT_VALUE"] !== $arPrice["VALUE"]):?>
                                                <p class="header__search-matches-price">
                                                    <?=$arPrice["PRINT_DISCOUNT_VALUE"]?>
                                                    <span class="header__search-matches-old-price"><?=$arPrice["PRINT_VALUE"]?></span>
                                                </p>
                                            <?else:?>
                                                <p class="header__search-matches-price">
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
                        <li class="header__search-matches-item">
                            <a class="header__search-matches-item-link" href="<?=$arItem["URL"]?>">
                                <div class="header__search-matches-description">
                                    <p class="header__search-matches-name"><?=$arItem["NAME"]?></p>
                                </div>
                            </a>
                        </li>
                    <?endif;?>
                <?endforeach;?>
            <?endif;?>
        <?endforeach;?>
    </ul>
    <script>
        (function scrollSearch () {
        let search = document.querySelector('.header__search-matches-list');
        if (search) {
            new PerfectScrollbar(search,{
                wheelSpeed: 0.5,
                wheelPropagation: true,
                minScrollbarLength: 20,
                typeContainer: 'li'
            });
        }
    })();
    document.querySelector('.header__search-matches-list').addEventListener('wheel', function (evt) {
            evt.preventDefault();
        });
    </script>
		<div class="header__search-matches-item-btn">
			<a class="header__search-matches-btn main-color-btn-fill" href="<?=$arResult["CATEGORIES"]['all']['ITEMS'][0]['URL']?>">
				<?=$arResult["CATEGORIES"]['all']['ITEMS'][0]['NAME']?>
			</a>
        </div>
        <div></div>
</div>
