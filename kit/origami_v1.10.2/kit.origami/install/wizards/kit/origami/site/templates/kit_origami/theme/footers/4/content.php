<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;

global $APPLICATION;

$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;

?>
<div class="footer-block" <?if (Config::get('FOOTER_IMG')):?> style='background-image: url("<?=CFile::GetPath(Config::get('FOOTER_IMG'));?>");'<?endif;?> >
    <div class="puzzle_block main-container">

        <div class="footer-block__menu">
            <div class="footer-block__menu--column footer-column">
                <div class="footer-column__logo">
                    <? if ($page != '/'): ?>
                        <a href="<?= SITE_DIR ?>" class="footer-column__logo--footer-logo">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR . "include/kit_origami/logo.php"
                                )
                            ); ?>
                        </a>
                    <? else: ?>
                        <span class="header-two__logo">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR . "include/kit_origami/logo.php"
                                )
                            ); ?>
                        </span>
                    <? endif; ?>
                </div>
                <div class="footer-column__description">
                    <span>
                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                            "AREA_FILE_SHOW" => "file",
                            "PATH" =>
                                SITE_DIR . "include/kit_origami/footers_description.php",
                        ]);
                        ?>
                    </span>
                </div>

                <div class="footer-column__contact_block footer-contacts">
                    <div class="footer-contacts__content_item footer-point">
                        <div class="footer-block__contacts_icon">
                            <svg class="footer-contacts__icons" width="10" height="12">
                                <use
                                    xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_location_filled_small"></use>
                            </svg>
                        </div>
                        <? if (
                            \Bitrix\Main\Loader::includeModule('kit.regions') &&
                            \KitOrigami::isUseRegions() &&
                            is_dir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/kit/regions.data')
                        ):
                            $APPLICATION->IncludeComponent(
                                "kit:regions.data",
                                "origami_address",
                                [
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_TYPE" => "A",
                                    "REGION_FIELDS" => ['UF_ADDRESS'],
                                    "REGION_ID" => $_SESSION['KIT_REGIONS']['ID']
                                ]
                            );
                        else:?>
                            <div class='container_menu__contact_item_wrapper'>
                                <?
                                $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" =>
                                        SITE_DIR . "include/kit_origami/contacts_address.php",
                                ]);
                                ?>
                            </div>
                        <? endif; ?>

                    </div>

                    <div class="footer-contacts__content_item footer-mail">
                        <svg class="footer-contacts__icons" width="12" height="8">
                            <use
                                xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_mail_filled_small"></use>
                        </svg>
                        <?
                        if (
                            \Bitrix\Main\Loader::includeModule('kit.regions') &&
                            \KitOrigami::isUseRegions() &&
                            is_dir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/kit/regions.data')
                        ):
                            $APPLICATION->IncludeComponent(
                                "kit:regions.data",
                                "origami_footer_email",
                                [
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_TYPE" => "A",
                                    "REGION_FIELDS" => ['UF_EMAIL'],
                                    "REGION_ID" => $_SESSION['KIT_REGIONS']['ID']
                                ]
                            );
                        else:
                            ?>
                            <div class="main_element_wrapper">
                                <? $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR
                                        . "include/kit_origami/contacts_email.php",
                                ]); ?>
                            </div>
                        <? endif; ?>
                    </div>

                    <div class="footer-contacts__content_item footer-phone">
                        <svg class="footer-contacts__icons" width="12" height="12">
                            <use
                                xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_phone_filled_small"></use>
                        </svg>
                        <?
                        if (
                            \Bitrix\Main\Loader::includeModule('kit.regions') &&
                            \KitOrigami::isUseRegions() &&
                            is_dir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/kit/regions.data')
                        ):
                            $APPLICATION->IncludeComponent(
                                "kit:regions.data",
                                "origami_footer_phone",
                                [
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_TYPE" => "A",
                                    "REGION_FIELDS" => ['UF_PHONE'],
                                    "REGION_ID" => $_SESSION['KIT_REGIONS']['ID']
                                ]
                            );
                        else:
                            ?>

                            <div class="main_element_wrapper">
                                <? $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR
                                        . "include/kit_origami/contacts_phone.php",
                                ]); ?>
                            </div>

                        <? endif; ?>
                    </div>

                </div>
                <?if(Config::get('FOOTER_CALL') == 'Y'):?>
                    <span class="main_btn button_call sweep-to-right"
                          onclick="callbackPhone('<?= SITE_DIR ?>',
                              '<?= SITE_ID ?>',this)"><?= GetMessage('CALLBACKPHONE'); ?>
                </span>
                <?endif;?>
            </div>
            <div class="footer-block__menu--column footer-column">
                <p class="footer-column__title">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/kit_origami/catalog_title.php")); ?>
                </p>
                <p class="footer-column__title">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/kit_origami/help_title.php")); ?>
                </p>
                <p class="footer-column__title">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/kit_origami/about_title.php")); ?>
                </p>
            </div>
            <div class="footer-block__menu--column footer-column">
                <div class="footer-column__follow footer-follow">
                    <div class="footer-column__block-title">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/kit_origami/subscribe_title.php")); ?>
                    </div>
                    <div class="footer-follow__input">
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
                <div class="footer-block__social">
                    <div class="footer-column__block-title">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/kit_origami/socnet_title.php")); ?>
                    </div>
                    <? $APPLICATION->IncludeComponent(
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
                    ); ?>
                </div>
            </div>
        </div>

    </div>
    <div class="copy_text_block">
        <div class="puzzle_block main-container">
            <div class="copy_text_block__content">
                <div class="copy_text_block__item fonts__middle_comment copy_text_block-dev">
                    <a class="copy_text_block__item_img" target="_blank" href="https://www.kit.ru">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/main_logo_kit.png" alt="">
                    </a>
                    <?= Loc::getMessage("KIT_FOOTER_ABOUT_COMPANY"); ?>
                </div>
                <div class="footer-block__payment_img">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/kit_origami/payment_images.php")); ?>
                </div>
            </div>
        </div>
    </div>
</div>
