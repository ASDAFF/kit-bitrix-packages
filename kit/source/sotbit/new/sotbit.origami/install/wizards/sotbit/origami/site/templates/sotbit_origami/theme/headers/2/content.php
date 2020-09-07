<?php
global $APPLICATION;
global $USER;
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Sotbit\Origami\Config\Option;
use Bitrix\Main\Page\Asset;
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
$page = $APPLICATION->GetCurPage(false);
Loc::loadMessages(__FILE__);
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "origami_mobile_menu",
    array(
        "ALLOW_MULTI_SELECT" => "N",
        "CHILD_MENU_TYPE" => "sotbit_left",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "DELAY" => "N",
        "MAX_LEVEL" => "4",
        "MENU_CACHE_GET_VARS" => array(
        ),

        "MENU_CACHE_TIME" => "36000000",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "sotbit_left",
        "USE_EXT" => "Y",
        'CACHE_SELECTED_ITEMS' => false,
        "COMPONENT_TEMPLATE" => ""
    ),
    false
);
?>


<div class="header-two" id="header-two">
    <div class="header-two__main-wrapper">
        <div class="header-two__main">
            <a class="header-two__main-mobile" id="menu_link" href="#menu">
                <svg width="24" height="16">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_menu"></use>
                </svg>
            </a>
            <? if($page != '/'):?>
                <a href="<?=SITE_DIR?>" class="header-two__logo">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."include/sotbit_origami/logo.php"
                        )
                    );?>
                </a>
            <?else:?>
                <span class="header-two__logo">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/sotbit_origami/logo.php"
                            )
                        );?>
                    </span>
            <?endif;?>
            <div class="header-two__btn-fixed-menu">
                <svg class="header-two__btn-fixed-menu-icon" width="18" height="18">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_menu_1"></use>
                </svg>
                <p class="header-two__btn-fixed-menu-content"><?=Loc::getMessage('HEADER_2_MENU_FIXED')?></p>
                <svg class="header-two__btn-fixed-menu-icon header-two__btn-fixed-menu-icon--arrow" width="12" height="6">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                </svg>
            </div>
            <div class="header-two__city">
                <svg width="18" height="18">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_location"></use>
                </svg>
                <p><?= Loc::getMessage('YOUR_CITY')?></p>
                <?
                if(\SotbitOrigami::isUseRegions())
                {
                    $template = Config::get('REGION_TEMPLATE');
                    if(!$template)
                        $template = 'origami_location';
                    $APPLICATION->IncludeComponent(
                        "sotbit:regions.choose",
                        $template,
                        array('FROM_LOCATION' => 'Y'),
                        false
                    );
                }
                else
                {
                    ?>
                    <span>
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/sotbit_origami/contacts_city.php"));?>
                        </span>
                    <?
                }
                ?>
            </div>
            <div class="header-two__search">
                <?
                echo Option::get('IBLOCK');
                $APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"origami_header_2",
	array(
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"PAGE" => SITE_DIR."catalog/",
		"CATEGORY_0_TITLE" => "РўРѕРІР°СЂС‹",
        "CATEGORY_0" => array(
            0 => "iblock_sotbit_origami_catalog",
        ),
        "CATEGORY_0_iblock_catalog" => array(
            0 => Option::get("IBLOCK_ID"),
        ),
		"CATEGORY_OTHERS_TITLE" => "РџСЂРѕС‡РµРµ",
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "search",
		"PRICE_CODE" => \SotbitOrigami::GetComponentPrices(["BASE","OPT","SMALL_OPT"]),
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "Y",
		"COMPONENT_TEMPLATE" => "origami_header_2",
		"ORDER" => "date",
		"USE_LANGUAGE_GUESS" => "N",
		"PRICE_VAT_INCLUDE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"CURRENCY_ID" => "RUB",
		"CATEGORY_0_iblock_sotbit_origami_catalog" => array(
			0 => "all",
		)
	),
	false
);?>
            </div>
            <div class="header-two__contact">
                <?
                if(
                    \Bitrix\Main\Loader::includeModule('sotbit.regions') &&
                    \SotbitOrigami::isUseRegions() &&
                    is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/sotbit/regions.data')
                ):
                    $APPLICATION->IncludeComponent(
                        "sotbit:regions.data",
                        "origami_header_2",
                        [
                            "CACHE_TIME"    => "36000000",
                            "CACHE_TYPE"    => "A",
                            "REGION_FIELDS" => ['UF_ADDRESS','UF_PHONE','UF_WORKTIME','UF_EMAIL'],
                            "REGION_ID"     => $_SESSION['SOTBIT_REGIONS']['ID']
                        ]
                    );
                else:?>
                    <div class="header-two__contact-phone-link">
                        <svg class="header-two__contact-phone-link-icon" width="18" height="18">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone"></use>
                        </svg>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/sotbit_origami/contacts_phone.php")
                        );
                        ?>
                        <svg class="header-two__contact-arrow" width="18" height="18">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                        </svg>
                    </div>
                        <?if(Config::get("HEADER_CALL") == "Y" && \Bitrix\Main\Loader::includeModule('sotbit.orderphone')):?><!-- <a href="javascript:callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>')"> -->
                            <span rel="nofollow" class="header-two__contact-arrow-link" onclick="callbackPhone('<?=SITE_DIR?>', '<?=SITE_ID?>', this)">
                                <?=Loc::getMessage('HEADER_2_CALL_PHONE')?>
                            </span>
                        <?endif;?>
                    <div class="header-two__drop-down">
                        <div class="header-two__drop-down-item header-two__drop-down-item--phone">
                            <svg width="18" height="18">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone"></use>
                            </svg>
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR."include/sotbit_origami/contacts_phone.php")
                            );
                            ?>
                        </div>
                        <div class="header-two__drop-down-item">
                            <svg width="18" height="20">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_time"></use>
                            </svg>
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR."include/sotbit_origami/contacts_worktime.php")
                                );
                                ?>
                            </p>
                        </div>

                        <div class="header-two__drop-down-item">
                            <svg width="18" height="20">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mail"></use>
                            </svg>
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR."include/sotbit_origami/contacts_email.php")
                            );
                            ?>
                        </div>
                        <div class="header-two__drop-down-item">
                            <svg width="18" height="20">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_pin"></use>
                            </svg>
                            <p>
                                <?
                                $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH"           =>
                                        SITE_DIR."include/sotbit_origami/contacts_address.php",
                                ]);
                                ?>
                            </p>
                        </div>
                        <?if(Config::get("HEADER_CALL") == "Y" && \Bitrix\Main\Loader::includeModule('sotbit.orderphone')):?>
                        <!-- <a class="header-two__drop-down-btn" href="javascript:callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>')"> -->
                        <p class="header-two__drop-down-btn" onclick="callbackPhone('<?=SITE_DIR?>', '<?=SITE_ID?>' ,this)">
                            <?=Loc::getMessage('HEADER_2_CALL_PHONE')?>
                        </p>
                        <?endif?>
                    </div>
                <?endif;?>
            </div>
            <div class="header-two__personal <?=(Config::get('BASKET_TYPE') == 'origami_top_without_basket')? "right-panel--show" : "";?>">
                <?if($USER->IsAuthorized()):?>
                    <a href="<?=Config::get('PERSONAL_PAGE')?>">
                        <svg width="18" height="20">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_login"></use>
                        </svg>
                        <?=$USER->GetLogin()?>
                    </a>
                <?else:?>
                    <? include $_SERVER["DOCUMENT_ROOT"] . SITE_DIR . "auth/index.php";?>
                <?endif;?>
                <p><?=Loc::getMessage('HEADER_2_PERSONAL')?></p>

            </div>
            <div class="header-two__basket <?=(Config::get('BASKET_TYPE') == 'origami_top_without_basket')? "right-panel--show" : "";?>">
                <?
                if (Config::get('BASKET_TYPE') == 'origami_top_without_basket') {
                    $templateBasket = 'origami_top_without_basket';
                } else {
                    $templateBasket = 'origami_basket_top';
                }

                ?>
                <?$APPLICATION->IncludeComponent(
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
    <div class="header-two__nav">

        <div class="header-two__main-nav load">
            <!-- <div class="header-two__"> -->

            <?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "origami_main_header_2",
                array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "sotbit_left",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "3",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "MENU_CACHE_TIME" => "36000000",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "sotbit_left",
                    "USE_EXT" => "Y",
                    'CACHE_SELECTED_ITEMS' => false,
                    "COMPONENT_TEMPLATE" => "origami_main_header_2"
                ),
                false
            );?>

            <!-- </div> -->
            <div class="header-two__main-navigation">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "origami_top_header_2",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "left",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "3",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "sotbit_top",
                        "USE_EXT" => "Y",
                        'CACHE_SELECTED_ITEMS' => false,
                        "COMPONENT_TEMPLATE" => "origami_top_header_2",
                        "MENU_THEME" => "site"
                    ),
                    false
                );?>
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
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/theme/headers/2/script.js");
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.fixedHeader('<?=$typeFix?>');
        });
    </script>
    <?endif;?>
</div>
