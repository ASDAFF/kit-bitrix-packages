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
        "contacts.1",
        array(
            "IBLOCK_TYPE" => "#CONTENT_CONTACTS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_CONTACTS_IBLOCK_ID#",
            "SETTINGS_USE" => "Y",
            "NEWS_COUNT" => "20",
            "MAIN" => "#CONTENT_CONTACTS_CONTACT_ID#",
            "PROPERTY_CODE" => [
                "ADDRESS",
                "CITY",
                "PHONE",
                "MAP"
            ],
            "PROPERTY_ADDRESS" => "ADDRESS",
            "PROPERTY_CITY" => "CITY",
            "PROPERTY_PHONE" => "PHONE",
            "PROPERTY_MAP" => "MAP",
            "MAP_VENDOR" => "yandex",
            "WEB_FORM_TEMPLATE" => ".default",
            "WEB_FORM_ID" => "#FORMS_FEEDBACK_ID#",
            "WEB_FORM_NAME" => "Задать вопрос",
            "WEB_FORM_CONSENT_URL" => "#SITE_DIR#company/consent/",
            "FEEDBACK_BUTTON_TEXT" => "Написать",
            "FEEDBACK_TEXT" => "Связаться с руководителем",
            "FEEDBACK_IMAGE" => "#TEMPLATE_PATH#/images/face.png",
            "ADDRESS_SHOW" => "Y",
            "PHONE_SHOW" => "Y",
            "SHOW_FORM" => "Y",
            "FEEDBACK_TEXT_SHOW" => "Y",
            "FEEDBACK_IMAGE_SHOW" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>