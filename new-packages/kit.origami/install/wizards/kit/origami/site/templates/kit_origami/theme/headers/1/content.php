<?php
global $APPLICATION;
global $USER;
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;
use Sotbit\Origami\Config\Option;



$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
$page = $APPLICATION->GetCurPage(false);
?>
<!-- The menu -->
<nav id="menu" class="bootstrap_style">
    <div>

        <div class="container_menu_mobile__search_block">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:search.title",
                "origami_mobile",
                array(
                    "INPUT_ID" => "title-search-input-mobile",
                    "CONTAINER_ID" => "title-search-mobile",
                    "NUM_CATEGORIES" => "1",
                    "CHECK_DATES" => "N",
                    "ORDER" => "date",
                    "PAGE" => SITE_DIR."catalog/",
                    "SHOW_INPUT" => "Y",
                    "SHOW_OTHERS" => "N",
                    "TOP_COUNT" => "5",
                    "USE_LANGUAGE_GUESS" => "Y",
                    "CATEGORY_0" => array(
                        0 => "iblock_kit_origami_catalog",
                    ),
                    "CATEGORY_0_iblock_catalog" => array(
                        0 => Option::get("IBLOCK_ID"),
                    ),
                    "CATEGORY_0_TITLE" => "РўРѕРІР°СЂС‹",
                    "PRICE_CODE" => array("BASE"),
                    "SHOW_PREVIEW" => "Y",
                    "PREVIEW_WIDTH" => "80",
                    "PREVIEW_HEIGHT" => "80"
                )
            );
            ?>
        </div>
        <div class="header_info_block__item header_info_block__block_region">
            <div class="header_info_block__block_region__title">
                <div id="mobileRegion" class="mobileRegion"></div>
            </div>
        </div>
        <ul class="container_menu_mobile__list_wrapper">
            <li><a href="<?=SITE_DIR?>catalog/"><?=GetMessage('MENU_CATALOG');?></a>
                <ul id="container_menu_mobile">
                </ul>
            </li>
        </ul>
        <div class="container_menu_mobile__list">

            <div class="container_menu_mobile__item fonts__small_text">
                <a class="container_menu_mobile__item_link" href="<?=Config::get('PERSONAL_PAGE')?>">
                    <span class="icon-locked"></span>
                    <?=Loc::getMessage('MENU_PERSONAL_ACCOUNT');?>
                </a>
            </div>
            <?if(Config::checkAction("COMPARE")):?>
            <div class="container_menu_mobile__item fonts__small_text">
                <a class="container_menu_mobile__item_link" href="<?=Config::get('COMPARE_PAGE')?>">
                    <span class="mobile_icon_chart-bar">
                        <svg class="" width="14" height="14">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_compare"></use>
                        </svg>
                    </span>
                    <?=Loc::getMessage('MENU_COMPARISON');?>
                    <span class="container_menu_mobile__item_link_col"></span>
                </a>
            </div>
            <?endif;?>
            <?if(Config::checkAction("DELAY")):?>
            <div class="container_menu_mobile__item fonts__small_text">
                <a class="container_menu_mobile__item_link" href="<?=Config::get('BASKET_PAGE')?>">
                    <span class="mobile_icon_heart">
                        <svg class="" width="14" height="14">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_favourite"></use>
                        </svg>
                    </span>
                    <?=Loc::getMessage('MENU_FAVOURITES');?>
                    <span class="container_menu_mobile__item_link_col"></span>
                </a>
            </div>
            <?endif;?>
            <?if(Config::checkAction("BUY")):?>
            <div class="container_menu_mobile__item fonts__small_text">
                <a class="container_menu_mobile__item_link" href="<?=Config::get('BASKET_PAGE')?>">
                    <span class="mobile_icon_shopping-basket">
                        <svg class="" width="14" height="14">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cart"></use>
                        </svg>
                    </span>
                    <?=Loc::getMessage('MENU_CART');?>
                    <span class="container_menu_mobile__item_link_col"></span>
                </a>
            </div>
            <?endif;?>
            <?if($USER->IsAuthorized()):?>
                <div class="container_menu_mobile__item fonts__small_text">
                    <a class="container_menu_mobile__item_link" href="?logout=yes">
                        <span class="mobile_icon_login">
                            <svg class="" width="14" height="14">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_login"></use>
                            </svg>
                        </span>
                        <?=Loc::getMessage('MENU_LOGOUT');?>
                    </a>
                </div>
            <?endif?>

        </div>
        <div class="container_menu__contact">
            <p class="container_menu__contact_title fonts__main_text"><?=GetMessage('MENU_CONTACT_INFO');?></p>
            <?
                if(
                    \Bitrix\Main\Loader::includeModule('kit.regions') &&
                    \SotbitOrigami::isUseRegions() &&
                    is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.data')
                ):
                    $APPLICATION->IncludeComponent(
                        "kit:regions.data",
                        "origami_mobile_menu_contacts",
                        [
                            "CACHE_TIME"    => "36000000",
                            "CACHE_TYPE"    => "A",
                            "REGION_FIELDS" => ['UF_ADDRESS', 'UF_EMAIL', 'UF_PHONE'],
                            "REGION_ID"     => $_SESSION['SOTBIT_REGIONS']['ID']
                        ]
                    );
                else:?>
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", [
                        "AREA_FILE_SHOW" => "file",
                        "PATH"           =>
                            SITE_DIR."include/kit_origami/mmenu_contacts_address.php",
                    ]);?>

                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                        "AREA_FILE_SHOW" => "file",
                        "PATH"           =>
                            SITE_DIR."include/kit_origami/mmenu_contacts_email.php",
                    ]);

                    $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                        "AREA_FILE_SHOW" => "file",
                        "PATH"           =>
                            SITE_DIR."include/kit_origami/mmenu_contacts_phone.php",
                    ]);
                    ?>
                <?endif;?>
        </div>
        <div class="container_menu__contact_soc">
            <?
            $vk = \Sotbit\Origami\Helper\Config::get('VK');
            $tw = \Sotbit\Origami\Helper\Config::get('TW');
            $ok = \Sotbit\Origami\Helper\Config::get('OK');
            $inst = \Sotbit\Origami\Helper\Config::get('INST');
            $telega = \Sotbit\Origami\Helper\Config::get('TELEGA');
            if($vk)
            {
                ?>
                <a href="<?=$vk?>"><i class="fab fa-vk"></i></a>
                <?
            }
            if($tw)
            {
                ?>
                <a href="<?=$tw?>"><i class="fab fa-twitter"></i></a>
                <?
            }
            if($ok)
            {
                ?>
                <a href="<?=$ok?>"><i class="fab fa-odnoklassniki"></i></a>
                <?
            }
            if($inst)
            {
                ?>
                <a href="<?=$inst?>"><i class="fab fa-instagram"></i></a>
                <?
            }
            if($telega)
            {
                ?>
                <a href="<?=$telega?>"><i class="fab fa-telegram-plane"></i></a>
                <?
            }
            ?>
        </div>
    </div>

</nav>

<div class="page" id="main-header">
    <div>
        <div class="header_mmenu">
            <a id="menu_link" href="#menu">
                <span class="burger_block"></span>
            </a>
            <div class="header_mmenu__content">
                <div class="header_mmenu__logo">
                    <a href="<?=SITE_DIR?>">

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/kit_origami/logo.php"
                            )
                        );?>

                    </a>
                </div>

                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR."include/kit_origami/basket_mobile_top.php"
                    )
                );?>

            </div>
        </div>
        <div class="container_menu_mobile__phone_block_wrapper  fonts__middle_text">
            <?
            if(
                \Bitrix\Main\Loader::includeModule('kit.regions') &&
                \SotbitOrigami::isUseRegions() &&
                is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.data')
            ):
                $APPLICATION->IncludeComponent(
                    "kit:regions.data",
                    "origami_header_phone_mobile",
                    [
                        "CACHE_TIME"    => "36000000",
                        "CACHE_TYPE"    => "A",
                        "REGION_FIELDS" => ['UF_PHONE'],
                        "REGION_ID"     => $_SESSION['SOTBIT_REGIONS']['ID']
                    ]
                );
            else:
                $phone = file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_DIR."include/kit_origami/contacts_phone.php");?>
                <div class="container_menu__contact_item_wrapper">
                    <?
                    global $APPLICATION;
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        [
                            "AREA_FILE_SHOW" => "file",
                            "PATH"           => SITE_DIR."include/kit_origami/contacts_phone.php",
                        ]
                    );
                    ?>
                </div>
            <?endif;?>
        </div>
        <div class="header_top_bottom_line">
            <div class="header_top_block puzzle_block main-container">
                <div class="header_top_block__menu_wrapper">

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "origami_top_header",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "2",
                            "MENU_CACHE_GET_VARS" => array(
                            ),
                            "MENU_CACHE_TIME" => "36000000",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "kit_top",
                            "USE_EXT" => "Y",
                            "COMPONENT_TEMPLATE" => "origami_top_header",
                            "MENU_THEME" => "site"
                        ),
                        false
                    );?>
                </div>
                <div class="header_top_block__phone">
                    <div class="header_top_block__phone__title">
                        <?
                        if(
                            \Bitrix\Main\Loader::includeModule('kit.regions') &&
                            \SotbitOrigami::isUseRegions() &&
                            is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.data')
                        ):
                            $APPLICATION->IncludeComponent(
                                "kit:regions.data",
                                "origami_header_phone",
                                [
                                    "CACHE_TIME"    => "36000000",
                                    "CACHE_TYPE"    => "A",
                                    "REGION_FIELDS" => ['UF_PHONE'],
                                    "REGION_ID"     => $_SESSION['SOTBIT_REGIONS']['ID']
                                ]
                            );
                        else:
                            $phone = file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_DIR."include/kit_origami/contacts_phone.php");?>
                            <div class="container_menu__contact_item_wrapper">
                                <?
                                global $APPLICATION;
                                $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    [
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH"           => SITE_DIR."include/kit_origami/contacts_phone.php",
                                    ]
                                );
                                ?>
                            </div>
                        <?endif;?>
                    </div>
                </div>
                <div class="header_top_block__cabinet <?=(Config::get('BASKET_TYPE') == 'origami_top_without_basket')? "right-panel--show" : "";?>">
                    <?
                    if($USER->IsAuthorized()) {
                        $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "origami_profile_menu",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(
                                ),
                                'CACHE_SELECTED_ITEMS' => false,
                                "MENU_CACHE_TIME" => "36000000",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "origami_profile",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => "origami_profile_menu",
                                "MENU_THEME" => "site",
                                'PATH_TO_PROFILE' => Config::get('PERSONAL_PAGE')

                            ),
                            false
                        );
                    }
                    ?>
                </div>
                <? include $_SERVER["DOCUMENT_ROOT"] . SITE_DIR . "auth/index.php";?>
            </div>
        </div>
        <div class="header_info_block__wrapper">
            <div class="header_info_block puzzle_block main-container">
                <svg class="header_info_block-icon__fixed-header" width="24" height="18">
                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_menu"></use>
                </svg>
                <div class="header_info_block__block_region_and_address">
                    <div class="header_info_block__block_region">

                    <div class="select-city-wrap">
		                <div class="select-city__block">
			                <span class="select-city__block__text"><?=Loc::getMessage(SotbitRegions::moduleId.'_YOUR_CITY')?>: </span>

                        <?if(\SotbitOrigami::isUseRegions())
                        {
                        ?>
                            <svg class="product-presence__location-icon" width="13" height="16">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_location_filled"></use>
                            </svg>
<?
                            $template = Config::get('REGION_TEMPLATE');
                            if(!$template)
                                $template = 'origami_location';

                            $APPLICATION->IncludeComponent(
	                            "kit:regions.choose",
                                $template,
	                            array(
		                            "FROM_LOCATION" => "Y",
		                            "COMPONENT_TEMPLATE" => "origami_combine"
	                            ),
	                            false
                            );
                        }
                        else
                        {
                            ?>
                            <span class="header_info_block__item header_info_block__block_region">
                                <?/*<i class="fas fa-map-marker-alt"></i>*/?>
                                <svg class="product-presence__location-icon product-presence__location-icon--disabled" width="13" height="16">
                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_location_filled"></use>
                                </svg>
                                <span class="header_info_block__block_region__title">
                                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/contacts_city.php"));?>
                                </span>
                            </span>
                            <?
                        }
                        ?>
		                </div>
		                <div class="select-city__dropdown-wrap" style="<?=($arResult['SHOW_POPUP'] == 'Y')?'display:block;':'display:none;'?>">
			                <div class="select-city__dropdown">
				                <div class="select-city__dropdown__title-wrap">
				                <span class="select-city__dropdown__title"><?=Loc::getMessage(SotbitRegions::moduleId.'_YOUR_CITY')?>
                                    <?=$arResult['USER_REGION_NAME_LOCATION']?>?
				                </span>
				                </div>
				                <div class="select-city__dropdown__choose-wrap">
				                <span
						                class="select-city__dropdown__choose__yes select-city__dropdown__choose"
						                data-id="<?=$arResult['USER_REGION_ID']?>"
				                >
					                <?=Loc::getMessage(SotbitRegions::moduleId.'_YES')?>
				                </span>
					                <span class="select-city__dropdown__choose__no select-city__dropdown__choose">
					                <?=Loc::getMessage(SotbitRegions::moduleId.'_NO')?>
				                </span>
				                </div>
			                </div>
		                </div>
	                </div>
                    </div>
                    <div class="header_info_block__block_address">
                        <span class="header_info_block__title">
                            <?
                            if(
                                \Bitrix\Main\Loader::includeModule('kit.regions') &&
                                \SotbitOrigami::isUseRegions() &&
                                is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.data')
                            ):
                                $APPLICATION->IncludeComponent(
                                    "kit:regions.data",
                                    "origami_address",
                                    [
                                        "CACHE_TIME"    => "36000000",
                                        "CACHE_TYPE"    => "A",
                                        "REGION_FIELDS" => ['UF_ADDRESS'],
                                        "REGION_ID"     => $_SESSION['SOTBIT_REGIONS']['ID']
                                    ]
                                );
                            else:?>
                                <span class='container_menu__contact_item_wrapper' style="display: block;">
                                    <?
                                    $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH"           =>
                                            SITE_DIR."include/kit_origami/contacts_address.php",
                                    ]);
                                    ?>
                                </span>
                            <?endif;?>
                        </span>
                    </div>
                </div>
                <div class="header_info_block__item header_info_block__logo_block">
                    <? if($page != '/') :?>
                        <a class="header_info_block__logo_block_link" href="<?=SITE_DIR?>">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/logo.php"));?>
                        </a>
                    <? else: ?>
                        <span class="header_info_block__logo_block_link">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/logo.php"));?>
                        </span>
                    <? endif ?>
                    <div class="header_info_block__logo_block_comment fonts__middle_comment">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/logo_description.php"));?>
                    </div>
                </div>
                <div class="block__feedback_and_basket">
                    <?if(\Sotbit\Origami\Helper\Config::get('HEADER_CALL') == 'Y' && \Bitrix\Main\Loader::includeModule('kit.orderphone')):?>
                        <div class="header_info_block__item header_info_block__feedback">
                            <div class="header_info_block__feedback_button fonts__main_comment main_btn-white" onclick="callbackPhone('<?=SITE_DIR?>',
                                    '<?=SITE_ID?>', this)">
                                <?=GetMessage('CALLBACKPHONE');?>
                            </div>
                        </div>
                    <?endif;?>
                    <div class="header_info_block__item basket-block <?=(Config::get('BASKET_TYPE') == 'origami_top_without_basket')? "right-panel--show" : "";?>">
                        <?
                        $APPLICATION->IncludeComponent(
                            "bitrix:search.title",
                            "origami_default",
                            array(
                                "INPUT_ID" => "title-search-input",
                                "CONTAINER_ID" => "title-search",
                                "NUM_CATEGORIES" => "1",
                                "CHECK_DATES" => "N",
                                "ORDER" => "date",
                                "PAGE" => SITE_DIR."catalog/",
                                "SHOW_INPUT" => "Y",
                                "SHOW_OTHERS" => "N",
                                "TOP_COUNT" => "5",
                                "USE_LANGUAGE_GUESS" => "Y",
                                "CATEGORY_0" => array("iblock_catalog"),
                                "CATEGORY_0_iblock_catalog" => array("all"),
                                "CATEGORY_0_TITLE" => "РўРѕРІР°СЂС‹",
                                "PRICE_CODE" => array("BASE"),
                                "SHOW_PREVIEW" => "Y",
                                "PREVIEW_WIDTH" => "80",
                                "PREVIEW_HEIGHT" => "80"
                            )
                        );
                        ?>

                        <?
                        if (Config::get('BASKET_TYPE') == 'origami_top_without_basket') {
                            $templateBasket = 'origami_top_without_basket';
                        } else {
                            $templateBasket = 'origami_basket_top';
                        }

                        $APPLICATION->IncludeComponent(
                            "bitrix:sale.basket.basket.line",
                            $templateBasket,
                            array(
                                "PATH_TO_BASKET" => Config::get('BASKET_PAGE'),
                                "PATH_TO_PERSONAL" => Config::get('PERSONAL_PAGE'),
                                "SHOW_PERSONAL_LINK" => "N",
                                "SHOW_NUM_PRODUCTS" => "Y",
                                "SHOW_TOTAL_PRICE" => "Y",
                                "SHOW_PRODUCTS" => "Y",
                                "POSITION_FIXED" => "N",
                                "SHOW_AUTHOR" => "N",
                                "HIDE_ON_BASKET_PAGES" => "N",
                                "PATH_TO_REGISTER" => SITE_DIR."login/",
                                "PATH_TO_PROFILE" => Config::get('PERSONAL_PAGE'),
                                "COMPONENT_TEMPLATE" => ".default",
                                "PATH_TO_ORDER" => Config::get('ORDER_PAGE'),
                                "SHOW_EMPTY_VALUES" => "Y",
                                "PATH_TO_AUTHORIZE" => "",
                                "SHOW_REGISTRATION" => "Y",
                                "SHOW_DELAY" => "Y",
                                "SHOW_NOTAVAIL" => "N",
                                "SHOW_IMAGE" => "Y",
                                "SHOW_PRICE" => "Y",
                                "SHOW_SUMMARY" => "Y",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                'IMAGE_FOR_OFFER' => Option::get('IMAGE_FOR_OFFER'),
                            ),
                            false
                        );?>
                    </div>
                </div>
            </div>
        </div>
        <div class="block_main_menu variant">
            <div class="puzzle_block main-container">
                <div class="responsive-hidden-nav-container">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "origami_main_header",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "kit_left",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "3",
                            "MENU_CACHE_GET_VARS" => array(
                            ),
                            'CACHE_SELECTED_ITEMS' => false,
                            "MENU_CACHE_TIME" => "36000000",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "kit_left",
                            "USE_EXT" => "Y",
                            "COMPONENT_TEMPLATE" => "origami_main_header"
                        ),
                        false
                    );?>
                </div>
            </div>
        </div>
    </div>
    <?
    $typeFix = "";
    if(Config::get("HEADER_FIX_DESKTOP")=="Y" && Config::get("HEADER_FIX_MOBILE") == "Y")
        $typeFix = "all";
    elseif(Config::get("HEADER_FIX_DESKTOP")=="Y")
        $typeFix = "desktop";
    elseif(Config::get("HEADER_FIX_MOBILE")=="Y")
        $typeFix = "mobile";
    if($typeFix):
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/theme/headers/1/script.js");
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.fixedHeader('<?=$typeFix?>');
            });
        </script>
    <?endif;?>
</div>
