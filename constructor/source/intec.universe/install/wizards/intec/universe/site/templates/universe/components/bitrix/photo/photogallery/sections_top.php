<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

?>
<div class="intec-content intec-content-visible">
	<div class="intec-content-wrapper">
        <div class="ns-bitrix c-photo c-photo-photogallery">
		<?php $APPLICATION->IncludeComponent(
			"bitrix:photo.sections.top",
			"",
			Array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"SECTION_COUNT" => $arParams["SECTION_COUNT"],
				"ELEMENT_COUNT" => $arParams["TOP_ELEMENT_COUNT"],
				"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
				"SECTION_SORT_FIELD" => $arParams["SECTION_SORT_FIELD"],
				"SECTION_SORT_ORDER" => $arParams["SECTION_SORT_ORDER"],
				"ELEMENT_SORT_FIELD" => $arParams["TOP_ELEMENT_SORT_FIELD"],
				"ELEMENT_SORT_ORDER" => $arParams["TOP_ELEMENT_SORT_ORDER"],
				"FIELD_CODE" => $arParams["TOP_FIELD_CODE"],
				"PROPERTY_CODE" => $arParams["TOP_PROPERTY_CODE"],
				"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
				"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],

                "LAZYLOAD_USE" => $arParams['LAZYLOAD_USE']
			),
			$component
		) ?>
		<div style="clear:both"></div>
        </div>
	</div>
</div>

