<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\Html;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var array $block
 * @var array $data
 * @var string $page
 * @var Path $path
 * @global CMain $APPLICATION
 */

?>
<?= Html::beginTag('div') ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.widget",
        "contact.1",
        array(
            "SETTINGS_USE" => "Y",
            "MAP_VENDOR" => "yandex",
            "INIT_MAP_TYPE" => "MAP",
            "MAP_MAP_DATA" => "",
            "BLOCK_SHOW" => "Y",
            "BLOCK_TITLE" => "Наши контакты",
            "ADDRESS_SHOW" => "Y",
            "ADDRESS_CITY" => "#SITE_ADDRESS#",
            "ADDRESS_STREET" => "",
            "PHONE_SHOW" => "Y",
            "PHONE_VALUES" => array(
                "#SITE_PHONE#",
            ),
            "FORM_SHOW" => "Y",
            "FORM_ID" => "#FORMS_CALL_ID#",
            "FORM_TEMPLATE" => ".default",
            "FORM_TITLE" => "Заказать звонок",
            "FORM_BUTTON_TEXT" => "Заказать звонок",
            "EMAIL_SHOW" => "Y",
            "EMAIL_VALUES" => array(
                "#SITE_MAIL#",
            ),
            "CONSENT_URL" => "#SITE_DIR#company/consent/",
            "MAP_OVERLAY" => "Y",
            "WIDE" => "Y",
            "BLOCK_VIEW" => "over",
            "MAP_CONTROLS" => array(
                "ZOOM",
                "SMALLZOOM",
                "MINIMAP",
                "TYPECONTROL",
                "SCALELINE"
            ),
            "MAP_OPTIONS" => array(
                "ENABLE_SCROLL_ZOOM",
                "ENABLE_DBLCLICK_ZOOM",
                "ENABLE_RIGHT_MAGNIFIER",
                "ENABLE_DRAGGING",
            ),
            "MAP_MAP_ID" => ""
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>