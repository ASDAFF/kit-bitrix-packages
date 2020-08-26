<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<?
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
	<div id="<?echo $CONTAINER_ID?>" class="block_search_404">
        <h6 class="block_search_404__title"><?=GetMessage("CT_BST_SEARCH_TITLE");?></h6>

        <form action="<?echo $arResult["FORM_ACTION"]?>" class="block_search_404__form">
            <input class="block_search_404__line" id="<?echo $INPUT_ID?>" type="text" name="q" value="" size="40" maxlength="50" autocomplete="off" placeholder="<?=GetMessage("CT_BST_SEARCH_BUTTON");?>" />
            <button class="block_search_404__btn main_btn sweep-to-right btn-sm" name="s" type="submit">
                <span><?=GetMessage("CT_BST_SEARCH_BUTTON");?></span>
                <svg class="site-navigation__item-icon" width="21" height="21">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_search"></use>
                </svg>
            </button>
        </form>
	</div>
<?endif?>
<script>
	BX.ready(function(){
		new JCTitleSearch({
			'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
			'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
			'INPUT_ID': '<?echo $INPUT_ID?>',
			'MIN_QUERY_LEN': 2
		});
	});
</script>
