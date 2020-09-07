<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$page = $APPLICATION->GetCurPage(false);
?>

<?if(!empty($arResult)):?>
<nav class="top-bar responsive-hidden-nav category-menu" data-responsive-hidden-nav="">
    <button class="responsive-hidden-button hidden fonts__small_text origami_icons_button">
        <?=Loc::getMessage("KIT_TOP_MENU_MORE");?>
    </button>
    <ul class="visible-links">

    <?
    $previousLevel = 0;
    foreach($arResult as $arItem):?>

        <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
            <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
        <?endif?>

        <?if($arItem["IS_PARENT"]):?>

            <?if($arItem["DEPTH_LEVEL"] == 1):?>
                <li class="visible-links__item <?if($arItem["SELECTED"]):?>active<?endif;?> <?if(isset($arItem["CHILD_SELECTED"])):?>current<?endif;?>">
                    <a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>" class="fonts__small_text visible-links__item_href origami_icons_button">
                        <?=$arItem["TEXT"]?>
                    </a>
                    <ul class="category_link__active_content">
            <?else:?>
                <li class="category_link__active_content_item <?if($arItem["SELECTED"]):?>active<?endif;?> <?if(isset($arItem["CHILD_SELECTED"])):?>current<?endif;?>">
                    <a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>" class="category_link__active_content_item_link">
                        <?=$arItem["TEXT"]?>
                    </a>
                    <ul class="category_link__active_content children">
            <?endif?>

        <?else:?>

            <?if($arItem["PERMISSION"] > "D"):?>

                <?if($arItem["DEPTH_LEVEL"] == 1):?>
                    <li class="visible-links__item <?if($arItem["SELECTED"]):?>active<?endif;?> <?if(isset($arItem["CHILD_SELECTED"]) || $item["SELECTED"]):?>current<?endif;?>">
                        <? if($arItem["LINK"] != $page): ?>
                            <a href="<?= $arItem["LINK"] ?>" title="<?=$arItem["TEXT"]?>" class="visible-links__item_href fonts__small_text">
                                <?=$arItem["TEXT"]?>
                            </a>
                        <? else: ?>
                            <span class="visible-links__item_span">
                                  <?=$arItem["TEXT"]?>
                            </span>
                        <? endif ?>
                    </li>
                <?else:?>
                    <li class="category_link__active_content_item <?if($arItem["SELECTED"]):?>active<?endif;?> <?if(isset($arItem["CHILD_SELECTED"])):?>current<?endif;?>">
                        <? if($arItem["LINK"] != $page): ?>
                            <a href="<?= $arItem["LINK"] ?>" title="<?=$arItem["TEXT"]?>" class="category_link__active_content_item_link">
                                <?=$arItem["TEXT"]?>
                            </a>
                        <? else: ?>
                            <span class="visible-links__item_span">
                                  <?=$arItem["TEXT"]?>
                            </span>
                        <? endif ?>
                    </li>
                <?endif?>

            <?else:?>

                <?if($arItem["DEPTH_LEVEL"] == 1):?>
                    <li><a href="" title="<?=Loc::getMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
                <?else:?>
                    <li><a href="" title="<?=Loc::getMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
                <?endif?>

            <?endif?>

        <?endif?>

        <?$previousLevel = $arItem["DEPTH_LEVEL"];?>

    <?endforeach?>

    <?if($previousLevel > 1): //close last item tags?>
        <?=str_repeat("</ul></li>", ($previousLevel - 1) );?>
    <?endif?>

    </ul>
    <ul class="hidden-links"></ul>
</nav>
<?endif?>
