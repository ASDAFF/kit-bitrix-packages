<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
    'intec.universe:main.widget',
    'form.3',
    array(
        "WEB_FORM_ID" => "#FORMS_QUESTION_ID#",
        "WEB_FORM_CONSENT_LINK" => "#SITE_DIR#company/consent/",
        "WEB_FORM_TITLE_SHOW" => "Y",
        "WEB_FORM_TITLE_POSITION" => "center",
        "WEB_FORM_DESCRIPTION_SHOW" => "Y",
        "WEB_FORM_DESCRIPTION_POSITION" => "center",
        "WEB_FORM_THEME" => "light",
        "WEB_FORM_BACKGROUND_USE" => "Y",
        "WEB_FORM_BACKGROUND_COLOR" => "theme",
        "WEB_FORM_CONSENT_SHOW" => "parameters",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => 3600000
    ),
    false,
    array()
); ?>
