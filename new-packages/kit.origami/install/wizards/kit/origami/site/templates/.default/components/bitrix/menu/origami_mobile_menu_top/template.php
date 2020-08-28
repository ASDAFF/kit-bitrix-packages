<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $APPLICATION;
$page = $APPLICATION->GetCurPage(false);
if (!empty($arResult)):?>
    <?
    $previousLevel = 0;
    foreach($arResult as $arItem):?>

    <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
        <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
    <?endif?>

    <?if ($arItem["IS_PARENT"]):?>

        <?if ($arItem["DEPTH_LEVEL"] == 1):?>
        <li class="container_menu_mobile__list_li">
            <?if($arItem['LINK'] != $page):?>
            <a href="<?=$arItem["LINK"]?>" class="container_menu_mobile__list_link"  title="<?=$arItem["TEXT"]?>"><?=$arItem["TEXT"]?></a>
            <?else:?>
            <span class="menu-catalog__section-title"><?=$arItem['TEXT']?></span>
            <?endif?>
            <ul class="root-item">
        <?else:?>
                <li class="container_menu_mobile__list_li">
                    <?if($arItem['LINK'] != $page):?>
                        <a href="<?=$arItem["LINK"]?>" class="container_menu_mobile__list_link"  title="<?=$arItem["TEXT"]?>"><?=$arItem["TEXT"]?></a>
                    <?else:?>
                        <span class="menu-catalog__section-title"><?=$arItem['TEXT']?></span>
                    <?endif?>
                    <ul>
        <?endif?>

    <?else:?>

        <?if ($arItem["PERMISSION"] > "D"):?>

            <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                <li class="container_menu_mobile__list_li">
                    <?if($arItem['LINK'] != $page):?>
                        <a href="<?=$arItem["LINK"]?>" class="container_menu_mobile__list_link"  title="<?=$arItem["TEXT"]?>"><?=$arItem["TEXT"]?></a>
                    <?else:?>
                        <span class="menu-catalog__section-title"><?=$arItem['TEXT']?></span>
                    <?endif?>
                </li>
            <?else:?>
                <li class="container_menu_mobile__list_li">
                    <?if($arItem['LINK'] != $page):?>
                        <a href="<?=$arItem["LINK"]?>" class="container_menu_mobile__list_link"  title="<?=$arItem["TEXT"]?>"><?=$arItem["TEXT"]?></a>
                    <?else:?>
                        <span class="menu-catalog__section-title"><?=$arItem['TEXT']?></span>
                    <?endif?>
                </li>
            <?endif?>

        <?else:?>

            <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                <li class="container_menu_mobile__list_li"><a class="container_menu_mobile__list_link" href=""  title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
            <?else:?>
                <li class="container_menu_mobile__list_li"><a class="container_menu_mobile__list_link" href="" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
            <?endif?>

        <?endif?>

    <?endif?>

    <?$previousLevel = $arItem["DEPTH_LEVEL"];?>

    <?endforeach?>

    <?if ($previousLevel > 1)://close last item tags?>
        <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
    <?endif?>
<?endif;?>
