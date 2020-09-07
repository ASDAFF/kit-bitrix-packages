<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
global $APPLICATION;

$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
?>
<div class="footer-block">
    <div class="puzzle_block main-container">
        <div class="row footer-block__menu">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <p class="footer-block__menu_title fonts__middle_text">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/catalog_title.php"));?>
                </p>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "kit_bottom_menu",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "kit_left",
                        "USE_EXT" => "Y",
                        "COMPONENT_TEMPLATE" => "kit_bottom_menu",
                        "CHILD_MENU_TYPE" => "kit_left",
                        "MAX_ITEMS" => "7"
                    ),
                    false
                );?>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <p class="footer-block__menu_title fonts__middle_text">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/help_title.php"));?>
                </p>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "kit_bottom_menu",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "kit_bottom1",
                        "USE_EXT" => "N",
                        "COMPONENT_TEMPLATE" => "kit_bottom_menu",
                        "CHILD_MENU_TYPE" => "kit_left",
                        "MAX_ITEMS" => ""
                    ),
                    false
                );?>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <p class="footer-block__menu_title fonts__middle_text">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/about_title.php"));?>
                </p>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "kit_bottom_menu",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "kit_bottom2",
                        "USE_EXT" => "N",
                        "COMPONENT_TEMPLATE" => "kit_bottom_menu",
                        "CHILD_MENU_TYPE" => "kit_left",
                        "MAX_ITEMS" => ""
                    ),
                    false
                );?>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <div class="footer-block__contact_block">
                    <p class="footer-block__contact_block_title">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/contacts_title.php"));?>
                    </p>
                    <div class="footer-block__contact_block_content">
                        <div class="footer-block__contact_block_content_item content_item--point ">
                            <div class="footer-block__contact_block_content_item-wrapper">
                                <i class="point-item_icon" aria-hidden="true" style="display: none;"></i>
                                <div class="footer-block__contacts_icon">
                                    <svg class="footer-block__icon_send_filled" width="13" height="13">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_send_filled"></use>
                                    </svg>
                                </div>
                                <?
                                if(
                                    \Bitrix\Main\Loader::includeModule('kit.regions') &&
                                    \KitOrigami::isUseRegions() &&
                                    is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.data')
                                ):
                                    $APPLICATION->IncludeComponent(
                                        "kit:regions.data",
                                        "origami_address",
                                        [
                                            "CACHE_TIME"    => "36000000",
                                            "CACHE_TYPE"    => "A",
                                            "REGION_FIELDS" => ['UF_ADDRESS'],
                                            "REGION_ID"     => $_SESSION['KIT_REGIONS']['ID']
                                        ]
                                    );
                                else:?>
                                    <div class='container_menu__contact_item_wrapper'>
                                        <?
                                        $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH"           =>
                                                SITE_DIR."include/kit_origami/contacts_address.php",
                                        ]);
                                        ?>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>

                        <div class="footer-block__contact_block_content_item content_item--mail">
                            <svg class="footer-block__contact_block_mail-icon" width="14" height="14">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_mail_filled"></use>
                            </svg>
                            <?
                            if(
                                \Bitrix\Main\Loader::includeModule('kit.regions') &&
                                \KitOrigami::isUseRegions() &&
                                is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.data')
                            ):
                                $APPLICATION->IncludeComponent(
                                    "kit:regions.data",
                                    "origami_footer_email",
                                    [
                                        "CACHE_TIME"    => "36000000",
                                        "CACHE_TYPE"    => "A",
                                        "REGION_FIELDS" => ['UF_EMAIL'],
                                        "REGION_ID"     => $_SESSION['KIT_REGIONS']['ID']
                                    ]
                                );
                            else:
                                ?>
                                <div class="footer-link__email fonts__middle_comment">
                                    <div class="main_element_wrapper">
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH"           => SITE_DIR
                                                ."include/kit_origami/contacts_email.php",
                                        ]);?>
                                    </div>
                                </div>
                            <?endif;?>
                        </div>

                        <div class="footer-block__contact_block_content_item content_item--phone">
                            <svg class="footer-block__contact_block_phone-icon" width="14" height="14">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
                            </svg>
                            <?
                            if(
                                \Bitrix\Main\Loader::includeModule('kit.regions') &&
                                \KitOrigami::isUseRegions() &&
                                is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.data')
                            ):
                                $APPLICATION->IncludeComponent(
                                    "kit:regions.data",
                                    "origami_footer_phone",
                                    [
                                        "CACHE_TIME"    => "36000000",
                                        "CACHE_TYPE"    => "A",
                                        "REGION_FIELDS" => ['UF_PHONE'],
                                        "REGION_ID"     => $_SESSION['KIT_REGIONS']['ID']
                                    ]
                                );
                            else:
                                ?>
                                <div class="footer-link__phone fonts__middle_comment">
                                    <div class="main_element_wrapper">
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                            "AREA_FILE_SHOW" => "file",
                                            "PATH"           => SITE_DIR
                                                ."include/kit_origami/contacts_phone.php",
                                        ]);?>
                                    </div>
                                </div>
                            <?endif;?>
                        </div>

                    </div>
                </div>
                <?if(Config::get('FOOTER_CALL') == 'Y'):?>
                    <span class="main_btn button_call sweep-to-right" onclick="callbackPhone('<?=SITE_DIR?>', '<?=SITE_ID?>',this)"><?=GetMessage('CALLBACKPHONE');?></span>
                <?endif;?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="footer-block__follow">
                    <div class="footer-block__follow_title fonts__middle_text">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/subscribe_title.php"));?>
                    </div>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:sender.subscribe",
                        "kit_sender_subscribe_campaign",
                        array(
                            "AJAX_MODE" => "Y",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "CACHE_TIME" => "3600",
                            "CACHE_TYPE" => "A",
                            "COMPONENT_TEMPLATE" => "kit_sender_subscribe_campaign",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "CONFIRMATION" => "N",
                            "FORM_SUBSCRIBE_TITLE" => "",
                            "HIDE_MAILINGS" => "N",
                            "MAILING_LISTS" => array(
                            ),
                            "SET_TITLE" => "N",
                            "SHOW_HIDDEN" => "Y",
                            "USER_CONSENT" => "N",
                            "USER_CONSENT_ID" => "0",
                            "USER_CONSENT_IS_CHECKED" => "Y",
                            "USER_CONSENT_IS_LOADED" => "N",
                            "USE_PERSONALIZATION" => "Y"
                        ),
                        false
                    ); ?>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="footer-block__social">
                    <div class="footer-block__social_title fonts__middle_text">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/socnet_title.php"));?>
                    </div>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:eshop.socnet.links",
                        "kit_socnet_links",
                        Array(
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "FACEBOOK" => \Kit\Origami\Helper\Config::get('FB'),
                            "VKONTAKTE" => \Kit\Origami\Helper\Config::get('VK'),
                            "TWITTER" => \Kit\Origami\Helper\Config::get('TW'),
                            "GOOGLE" => \Kit\Origami\Helper\Config::get('GOOGLE'),
                            "INSTAGRAM" => \Kit\Origami\Helper\Config::get('INST'),
                            "YOUTUBE" => \Kit\Origami\Helper\Config::get('YOUTUBE'),
                            "ODNOKLASSNIKI" => \Kit\Origami\Helper\Config::get('OK'),
                            "TELEGRAM" => \Kit\Origami\Helper\Config::get('TELEGA'),
                        )
                    );?>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-12">
                <div class="footer-block__payment">
                    <div class="footer-block__payment_title fonts__middle_text">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/payment_title.php"));?>
                    </div>
                    <div class="footer-block__payment_img">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/payment_images.php"));?>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-12">
                <div id="bx-composite-banner">

                </div>
            </div>
        </div>
    </div>
    <div class="copy_text_block">
        <div class="puzzle_block no-padding main-container">
            <div class="copy_text_block__content">
                <div class="copy_text_block__item fonts__middle_comment copy_text_block-dev">
                    <a class="copy_text_block__item_img" target="_blank" href="https://www.kit.ru">
                        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/main_logo_kit.png" alt="">
                    </a>
                    <?=Loc::getMessage("KIT_FOOTER_ABOUT_COMPANY");?>
                </div>
                <div class="copy_text_block__item fonts__middle_comment copy_text_block-company">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."include/kit_origami/copyright.php"
                        )
                    );?>
                </div>
            </div>
        </div>
    </div>
</div>
