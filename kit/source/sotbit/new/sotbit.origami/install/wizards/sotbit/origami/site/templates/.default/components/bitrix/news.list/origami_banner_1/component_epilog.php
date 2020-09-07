<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs(SITE_DIR . "local/templates/sotbit_origami/assets/plugin/swiper5.2.0/js/swiper.js");
Asset::getInstance()->addJs(SITE_DIR . "local/templates/sotbit_origami/assets/js/custom-slider.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/sotbit_origami/assets/plugin/swiper5.2.0/css/swiper.min.css");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/sotbit_origami/assets/css/style-swiper-custom.css");
?>
