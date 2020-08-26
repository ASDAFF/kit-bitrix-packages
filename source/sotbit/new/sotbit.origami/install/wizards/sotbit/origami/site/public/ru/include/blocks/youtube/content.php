<?
global $settings;
$APPLICATION->IncludeComponent(
	"sotbit:youtube",
	"origami_youtube",
	array(
        "CHANEL_ID" => $settings['fields']['chanelId']['value'],
        "API_KEY" => $settings['fields']['apiKey']['value'],
        "VIDEO_COUNT" => $settings['fields']['count']['value'],
		"TITLE" => $settings['fields']['title']['value'],
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "COMPONENT_TEMPLATE" => "origami_youtube",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	)
);
?>