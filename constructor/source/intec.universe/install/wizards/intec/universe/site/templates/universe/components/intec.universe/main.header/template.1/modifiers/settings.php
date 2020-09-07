<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

global $APPLICATION;

if (
    Loader::includeModule('intec.constructor') ||
    Loader::includeModule('intec.constructorlite')
) {
    if (!defined('EDITOR')) {
        $properties = Properties::getCollection();

        /** Общие параметры */
        if ($properties->get('template-images-lazyload-use')) {
            $arParams['MENU_MAIN_LAZYLOAD_USE'] = 'Y';
            $arParams['BANNER_LAZYLOAD_USE'] = 'Y';
        }

        $arParams['MENU_MAIN_UPPERCASE'] = $properties->get('header-menu-uppercase-use') ? 'Y' : 'N';
        $arParams['MENU_MAIN_OVERLAY_USE'] = $properties->get('header-menu-overlay-use') ? 'Y' : 'N';
        $arParams['REGIONALITY_USE'] = $properties->get('base-regionality-use') ? 'Y' : 'N';
        $arParams['SEARCH_MODE'] = $properties->get('base-search-mode');

        /** Параметры корзины */
        $arParams['BASKET_POPUP'] = $properties->get('header-basket-popup-show') ? 'Y' : 'N';

        if ($properties['basket-position'] !== 'header') {
            $arParams['DELAY_SHOW'] = 'N';
            $arParams['DELAY_SHOW_FIXED'] = 'N';
            $arParams['DELAY_SHOW_MOBILE'] = 'N';
            $arParams['COMPARE_SHOW'] = 'N';
            $arParams['COMPARE_SHOW_FIXED'] = 'N';
            $arParams['COMPARE_SHOW_MOBILE'] = 'N';
            $arParams['BASKET_SHOW'] = 'N';
            $arParams['BASKET_SHOW_FIXED'] = 'N';
            $arParams['BASKET_SHOW_MOBILE'] = 'N';
        }

        if (!$properties['basket-delay-use']) {
            $arParams['DELAY_SHOW'] = 'N';
            $arParams['DELAY_SHOW_FIXED'] = 'N';
            $arParams['DELAY_SHOW_MOBILE'] = 'N';
            $arParams['MENU_POPUP_DELAY_SHOW'] = 'N';
        }

        if (!$properties['basket-compare-use']) {
            $arParams['COMPARE_SHOW'] = 'N';
            $arParams['COMPARE_SHOW_FIXED'] = 'N';
            $arParams['COMPARE_SHOW_MOBILE'] = 'N';
            $arParams['MENU_POPUP_COMPARE_SHOW'] = 'N';
        }

        if (!$properties['basket-use']) {
            $arParams['BASKET_SHOW'] = 'N';
            $arParams['BASKET_SHOW_FIXED'] = 'N';
            $arParams['BASKET_SHOW_MOBILE'] = 'N';
            $arParams['DELAY_SHOW'] = 'N';
            $arParams['DELAY_SHOW_FIXED'] = 'N';
            $arParams['DELAY_SHOW_MOBILE'] = 'N';
            $arParams['MENU_POPUP_BASKET_SHOW'] = 'N';
            $arParams['MENU_POPUP_DELAY_SHOW'] = 'N';
        }

        /** Параметры шапки */
        $arParams['TRANSPARENCY'] = 'N';

        switch ($properties['header-template']) {
            case 1: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'bottom';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'N';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 2: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'N';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'Y';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 3: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'N';
                $arParams['EMAIL_SHOW'] = 'N';
                $arParams['AUTHORIZATION_SHOW'] = 'N';
                $arParams['TAGLINE_SHOW'] = 'N';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'N';
                $arParams['SEARCH_SHOW'] = 'N';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 4: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'N';
                $arParams['DESKTOP'] = 'template.3';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_MAIN_ROOT'] = 'catalog';
                $arParams['MENU_MAIN_CHILD'] = 'catalog';
                $arParams['MENU_INFO_SHOW'] = 'N';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'N';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 5: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'N';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'Y';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 6: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'bottom';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'Y';
                $arParams['MENU_MAIN_ROOT'] = 'catalog';
                $arParams['MENU_MAIN_CHILD'] = 'catalog';
                $arParams['MENU_INFO_SHOW'] = 'N';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 7: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'bottom';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'Y';
                $arParams['MENU_MAIN_ROOT'] = 'catalog';
                $arParams['MENU_MAIN_CHILD'] = 'catalog';
                $arParams['MENU_INFO_SHOW'] = 'N';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 8: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'N';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'top';
                $arParams['SOCIAL_SHOW'] = 'Y';
                $arParams['SOCIAL_POSITION'] = 'center';

                break;
            }
            case 9: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'N';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'Y';
                $arParams['SOCIAL_POSITION'] = 'left';

                break;
            }
            case 10: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'N';
                $arParams['DESKTOP'] = 'template.1';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'Y';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'left';

                break;
            }
            case 11: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'N';
                $arParams['DESKTOP'] = 'template.2';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'Y';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'left';

                break;
            }
            case 12: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'N';
                $arParams['DESKTOP'] = 'template.2';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_POSITION'] = 'top';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_INFO_SHOW'] = 'Y';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
                $arParams['PHONES_POSITION'] = 'bottom';
                $arParams['SOCIAL_SHOW'] = 'N';
                $arParams['SOCIAL_POSITION'] = 'left';

                break;
            }
            case 13: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = $properties['basket-position'] === 'header' || !$properties['basket-use'] ? 'Y' : 'N';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.4';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';

                break;
            }
            case 14: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = $properties['basket-position'] === 'header' || !$properties['basket-use'] ? 'Y' : 'N';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.5';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';

                break;
            }
            case 15: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = $properties['basket-position'] === 'header' || !$properties['basket-use'] ? 'Y' : 'N';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.6';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';

                break;
            }
            case 16: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = $properties['basket-position'] === 'header' || !$properties['basket-use'] ? 'Y' : 'N';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.7';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['FORMS_CALL_SHOW'] = 'Y';

                break;
            }
            case 17: {
                $arParams['DESKTOP'] = 'template.8';
                $arParams['AUTHORIZATION_SHOW'] = $properties['basket-position'] === 'header' || !$properties['basket-use'] ? 'Y' : 'N';
                $arParams['MENU_MAIN_SECTION_VIEW'] = 'images';

                break;
            }
            case 18: {
                $arParams['DESKTOP'] = 'template.9';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['MENU_MAIN_SECTION_VIEW'] = 'information';
                $arParams['MENU_MAIN_SUBMENU_VIEW'] = 'simple.2';
                $arParams['SOCIAL_SHOW'] = 'N';

                break;
            }
            case 19: {
                $arParams['LOGOTYPE_SHOW'] = 'Y';
                $arParams['PHONES_SHOW'] = 'Y';
                $arParams['ADDRESS_SHOW'] = 'Y';
                $arParams['EMAIL_SHOW'] = 'Y';
                $arParams['AUTHORIZATION_SHOW'] = 'Y';
                $arParams['TAGLINE_SHOW'] = 'Y';
                $arParams['DESKTOP'] = 'template.10';
                $arParams['MENU_MAIN_SHOW'] = 'Y';
                $arParams['MENU_MAIN_TRANSPARENT'] = 'N';
                $arParams['MENU_MAIN_DELIMITERS'] = 'N';
                $arParams['SEARCH_SHOW'] = 'Y';
                $arParams['MENU_MAIN_DELIMITERS'] = 'N';
                $arParams['FORMS_CALL_SHOW'] = 'Y';
            }
        }

        switch ($properties['header-menu-popup-template']) {
            case 1: {
                $arParams['MENU_POPUP_TEMPLATE'] = '1';

                break;
            }
            case 2: {
                $arParams['MENU_POPUP_TEMPLATE'] = '2';

                break;
            }
            case 3: {
                $arParams['MENU_POPUP_TEMPLATE'] = '3';
                $arParams['MENU_POPUP_MODE'] = 'simple';

                break;
            }
            case 4: {
                $arParams['MENU_POPUP_TEMPLATE'] = '3';
                $arParams['MENU_POPUP_MODE'] = 'extended';

                break;
            }
        }

        /** Параметры фиксированной шапки */
        $arParams['FIXED'] = 'template.1';

        if (!$properties['header-fixed-use'])
            $arParams['FIXED'] = null;

        if (!$properties['header-fixed-menu-popup-show'])
            $arParams['FIXED_MENU_POPUP_SHOW'] = 'N';

        /** Параметры мобильной шапки */
        $arParams['AUTHORIZATION_SHOW_MOBILE'] = 'N';
        $arParams['MOBILE_FIXED'] = 'N';
        $arParams['MOBILE_HIDDEN'] = $properties->get('header-mobile-hidden') ? 'Y' : 'N';
        $arParams['MOBILE_FILLED'] = 'N';
        $arParams['MOBILE_SEARCH_TYPE'] = $properties->get('header-mobile-search-type');

        if ($properties['header-mobile-fixed'])
            $arParams['MOBILE_FIXED'] = 'Y';

        switch ($properties['header-mobile-template']) {
            case 'white': {
                break;
            }
            case 'colored': {
                $arParams['MOBILE_FILLED'] = 'Y';

                break;
            }
            case 'white-with-icons': {
                $arParams['AUTHORIZATION_SHOW_MOBILE'] = 'Y';

                break;
            }
            case 'colored-with-icons': {
                $arParams['MOBILE_FILLED'] = 'Y';
                $arParams['AUTHORIZATION_SHOW_MOBILE'] = 'Y';

                break;
            }
        }

        switch ($properties['header-mobile-menu-template']) {
            case 1: {
                $arParams['MOBILE'] = 'template.1';

                break;
            };
            case 2: {
                $arParams['MOBILE'] = 'template.2';
                $arParams['MOBILE_MENU_BORDER_SHOW'] = 'Y';
                $arParams['MOBILE_MENU_INFORMATION_VIEW'] = 'view.2';
                $arParams['AUTHORIZATION_SHOW_MOBILE'] = 'Y';

                break;
            }
            case 3: {
                $arParams['MOBILE'] = 'template.2';
                $arParams['MOBILE_MENU_BORDER_SHOW'] = 'Y';
                $arParams['MOBILE_MENU_INFORMATION_VIEW'] = 'view.1';
                $arParams['AUTHORIZATION_SHOW_MOBILE'] = 'Y';

                break;
            }
            case 4: {
                $arParams['MOBILE'] = 'template.2';
                $arParams['MOBILE_MENU_BORDER_SHOW'] = 'N';
                $arParams['MOBILE_MENU_INFORMATION_VIEW'] = 'view.1';
                $arParams['AUTHORIZATION_SHOW_MOBILE'] = 'Y';

                break;
            }
        }

        /** Параметры баннера */

        if ($properties['pages-main-blocks']['banner']['active']) {
            if (
                $APPLICATION->GetCurPage(false) === SITE_DIR &&
                $properties->get('pages-main-template') === 'narrow.left' &&
                ArrayHelper::isIn($properties['pages-main-blocks']['banner']['template'], [2, 3, 4, 10])
            ) $arParams['BANNER'] = null;
        } else {
            $arParams['BANNER'] = null;
        }

        if (!empty($arParams['BANNER'])) {
            switch ($properties['pages-main-blocks']['banner']['template']) {
                case 1: {
                    $arParams['BANNER'] = 'template.1';
                    $arParams['BANNER_HEIGHT'] = '600';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '5';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '5';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '4';
                    $arParams['BANNER_IMAGE_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_SHOW'] = 'N';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'N';

                    break;
                }
                case 2: {
                    $arParams['BANNER'] = 'template.2';
                    $arParams['BANNER_HEIGHT'] = '500';
                    $arParams['BANNER_ROUNDED'] = 'Y';
                    $arParams['BANNER_BLOCKS_USE'] = 'Y';
                    $arParams['BANNER_BLOCKS_ELEMENTS_COUNT'] = '4';
                    $arParams['BANNER_BLOCKS_POSITION'] = 'both';
                    $arParams['BANNER_BLOCKS_INDENT'] = 'Y';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '1';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '1';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'N';

                    break;
                }
                case 3: {
                    $arParams['BANNER'] = 'template.2';
                    $arParams['BANNER_HEIGHT'] = '500';
                    $arParams['BANNER_ROUNDED'] = 'Y';
                    $arParams['BANNER_BLOCKS_USE'] = 'Y';
                    $arParams['BANNER_BLOCKS_ELEMENTS_COUNT'] = '4';
                    $arParams['BANNER_BLOCKS_POSITION'] = 'right';
                    $arParams['BANNER_BLOCKS_INDENT'] = 'Y';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '1';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '1';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'N';

                    break;
                }
                case 4: {
                    $arParams['BANNER'] = 'template.2';
                    $arParams['BANNER_HEIGHT'] = '500';
                    $arParams['BANNER_ROUNDED'] = 'Y';
                    $arParams['BANNER_BLOCKS_USE'] = 'Y';
                    $arParams['BANNER_BLOCKS_ELEMENTS_COUNT'] = '2';
                    $arParams['BANNER_BLOCKS_POSITION'] = 'right';
                    $arParams['BANNER_BLOCKS_INDENT'] = 'Y';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '1';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '1';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'N';

                    break;
                }
                case 5: {
                    $arParams['BANNER'] = 'template.1';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '2';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '2';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '1';
                    $arParams['BANNER_IMAGE_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'Y';

                    break;
                }
                case 6: {
                    $arParams['BANNER'] = 'template.1';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '3';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '3';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '1';
                    $arParams['BANNER_IMAGE_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '2';
                    $arParams['TRANSPARENCY'] = 'Y';

                    break;
                }
                case 7: {
                    $arParams['BANNER'] = 'template.1';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '3';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '3';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '2';
                    $arParams['BANNER_IMAGE_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'Y';

                    break;
                }
                case 8: {
                    $arParams['BANNER'] = 'template.1';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '4';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '3';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '3';
                    $arParams['BANNER_IMAGE_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'Y';

                    break;
                }
                case 9: {
                    $arParams['BANNER'] = 'template.3';
                    $arParams['BANNER_HEIGHT'] = '600';
                    $arParams['BANNER_ROUNDED'] = 'Y';
                    $arParams['BANNER_BLOCKS_USE'] = 'Y';
                    $arParams['BANNER_BLOCKS_POSITION'] = 'right';
                    $arParams['BANNER_BLOCKS_ELEMENTS_COUNT'] = '2';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '5';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '4';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '4';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['BANNER_WIDE'] = 'Y';
                    $arParams['TRANSPARENCY'] = 'N';

                    break;
                }
                case 10: {
                    $arParams['BANNER'] = 'template.3';
                    $arParams['BANNER_HEIGHT'] = '600';
                    $arParams['BANNER_ROUNDED'] = 'Y';
                    $arParams['BANNER_BLOCKS_USE'] = 'Y';
                    $arParams['BANNER_BLOCKS_POSITION'] = 'right';
                    $arParams['BANNER_BLOCKS_ELEMENTS_COUNT'] = '2';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '5';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '4';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '4';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['BANNER_WIDE'] = 'N';
                    $arParams['TRANSPARENCY'] = 'N';

                    break;
                }
                case 11: {
                    $arParams['BANNER'] = 'template.1';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '4';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '1';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '1';
                    $arParams['BANNER_ADDITIONAL_SHOW'] = 'Y';
                    $arParams['BANNER_ADDITIONAL_VIEW'] = '2';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['TRANSPARENCY'] = 'Y';

                    break;
                }
                case 12: {
                    $arParams['BANNER'] = 'template.3';
                    $arParams['BANNER_ROUNDED'] = 'Y';
                    $arParams['BANNER_BLOCKS_USE'] = 'Y';
                    $arParams['BANNER_BLOCKS_POSITION'] = 'bottom';
                    $arParams['BANNER_BLOCKS_ELEMENTS_COUNT'] = '4';
                    $arParams['BANNER_HEADER_SHOW'] = 'Y';
                    $arParams['BANNER_HEADER_VIEW'] = '5';
                    $arParams['BANNER_DESCRIPTION_SHOW'] = 'Y';
                    $arParams['BANNER_DESCRIPTION_VIEW'] = '4';
                    $arParams['BANNER_HEADER_OVER_SHOW'] = 'N';
                    $arParams['BANNER_BUTTON_VIEW'] = '4';
                    $arParams['BANNER_SLIDER_NAV_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_NAV_VIEW'] = '1';
                    $arParams['BANNER_SLIDER_DOTS_SHOW'] = 'Y';
                    $arParams['BANNER_SLIDER_DOTS_VIEW'] = '1';
                    $arParams['BANNER_WIDE'] = 'Y';
                    $arParams['TRANSPARENCY'] = 'Y';

                    break;
                }
                default: {
                    $arParams['BANNER'] = null;

                    break;
                }
            }
        }
    }
}