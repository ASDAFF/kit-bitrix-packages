<?
global $settings;

$APPLICATION->IncludeComponent(
	"kit:instagram",
	"origami_insta",
	array(
		"LOGIN" => $settings['fields']['login']['value'],
		"IMG_COUNT" => "4",
		"TITLE" => $settings['fields']['title']['value'],
        "IMG_DEFAULT" => 'Y',
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
		"COMPONENT_TEMPLATE" => "kit_instagram",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);
?>