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
$this->setFrameMode(true);

?>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="block_main_left__canvas_left" id="<?//=$this->GetEditAreaId($arItem['ID']);?>">
		<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
            <img
                class="block_main_left__canvas_left_img"
                border="0"
                src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                />
		<?endif?>
        <?if(!empty($arItem["DISPLAY_PROPERTIES"]["URL_PRODUCT"]["DISPLAY_VALUE"])):?>
            <a class="main_btn button_another sweep-to-right" href="<?=($arItem["DISPLAY_PROPERTIES"]["URL_PRODUCT"]["DISPLAY_VALUE"]);?>"><?=GetMessage("WATCH_PRODUCT");?></a>
        <?endif?>
	</div>
<?endforeach;?>

