<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){
    die();
}

$this->setFrameMode(true);
$page = $APPLICATION->GetCurPage(false);
?>


<?
if(!empty($arResult)):?>
<ul class="footer-block__item">

<?
if($arParams["MAX_ITEMS"])
{
    $i = 0;
    $maxItems = $arParams["MAX_ITEMS"];
}

foreach($arResult as $arItem)
{
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
        continue;
?>
	<?if($arItem["SELECTED"]):?>
        <li class="footer-block__item_name">
            <span class="footer-block__item_name_link fonts__small_text selected"><?=$arItem["TEXT"]?></span>
        </li>
	<?else:?>
        <li class="footer-block__item_name">
            <? if($arItem["LINK"] != $page): ?>
                <a class="footer-block__item_name_link fonts__small_text" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
            <? else: ?>
                <span class="footer-block__item_name_link fonts__small_text"><?=$arItem["TEXT"]?></span>
            <? endif ?>
        </li>
	<?endif?>
<?
    if($arParams["MAX_ITEMS"])
    {
        $i++;
        if($i == $maxItems) break;
    }
}
?>

</ul>

<?endif?>
