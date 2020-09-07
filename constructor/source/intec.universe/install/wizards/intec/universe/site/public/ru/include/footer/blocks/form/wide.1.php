<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
    'intec.universe:widget',
    'web.form.2',
    array(
        "GRAB_DATA" => "N",
        "WEB_FORM_ID" => "#FORMS_FEEDBACK_ID#",
        "WEB_FORM_TEMPLATE" => ".default",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => 3600000,
        "CONSENT_URL" => "#SITE_DIR#company/consent/"
    ),
    false,
    array()
); ?>