<?
global $settings;

$APPLICATION->IncludeComponent(
    "sotbit:instagram",
    "origami_insta_1",
    array(
        "LOGIN" => $settings['fields']['login']['value'],
        "IMG_COUNT" => $settings['fields']['count']['value'],
        "TITLE" => $settings['fields']['title']['value'],
        "TITLE_TEXT" => $settings['fields']['title_text']['value'],
        "TEXT" => $settings['fields']['text']['value'],
        "IMG_DEFAULT" => 'Y',
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "COMPONENT_TEMPLATE" => "origami_insta_1",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO"
    ),
    false
);
?>
