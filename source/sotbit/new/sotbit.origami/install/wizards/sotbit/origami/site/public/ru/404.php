<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
define("HIDE_SIDEBAR", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?><div class="origami_404">
	<div class="origami_404_block">
		<div class="origami_404_block__main">
			 404
		</div>
		<div class="origami_404_block__text">
			<div class="origami_404_block__text_title">
				 Страница не найдена
			</div>
			<div class="origami_404_block__text_comment">
				 К сожалению, вы перешли по неправильной ссылке.
			</div>
		</div>
	</div>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"origami_search_errors",
	Array(
		"CATEGORY_0" => array("iblock_catalog"),
		"CATEGORY_0_TITLE" => "Товары",
		"CATEGORY_0_iblock_catalog" => array("all"),
		"CHECK_DATES" => "N",
		"CONTAINER_ID" => "title-search",
		"INPUT_ID" => "title-search-input",
		"NUM_CATEGORIES" => "1",
		"ORDER" => "date",
		"PAGE" => SITE_DIR."catalog/",
		"PREVIEW_HEIGHT" => "80",
		"PREVIEW_WIDTH" => "80",
		"PRICE_CODE" => array("BASE"),
		"SHOW_INPUT" => "Y",
		"SHOW_OTHERS" => "N",
		"SHOW_PREVIEW" => "Y",
		"TOP_COUNT" => "5",
		"USE_LANGUAGE_GUESS" => "Y"
	)
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>