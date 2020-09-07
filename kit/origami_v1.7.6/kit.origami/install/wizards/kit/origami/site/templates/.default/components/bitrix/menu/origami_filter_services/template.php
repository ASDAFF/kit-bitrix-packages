<?php
/**
 * Copyright (c) 26/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

$page = $APPLICATION->GetCurPage(false);
?>
<? if ($arResult)
{
    ?>
    <ul class="block_main_left_menu__list">
    <?
    foreach ($arResult as $li)
    {
    ?>
        <li class="block_main_left_menu__list_item fonts__main_comment <?if(isset($li["CHILD_SELECTED"])):?>current<?endif;?> <?if($li["SELECTED"]):?>active<?endif;?>">
            <? if($li["LINK"] != $page): ?>
                <a class="block_main_left_menu__link" title="<?= $li["TEXT"] ?>" href="<?= $li["LINK"] ?>">
            <?else:?>
                <div class="block_main_left_menu__link">
            <?endif?>
                <?if(isset($li["PICTURE"])):?>
                    <div class="block_main_left_menu__link-wrapper">
                        <img class="block_main_left_menu__link-img" src="<?=$li["PICTURE"]["src"]?>" width="<?=$li["PICTURE"]["width"]?>" height="<?=$li["PICTURE"]["height"]?>" alt="<?= $li["TEXT"] ?>" title="<?= $li["TEXT"] ?>">
                    </div>
                <?endif?>
                <?= $li['TEXT'] ?>
                <?if($li['CHILDREN']):?>
                    <span class="block_main_left_menu__link-icon">
                        <svg class="block_main_left_menu__link-arrow" width="8" height="12">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                        </svg>
                    </span>
                <?endif;?>
            <? if($li["LINK"] != $page): ?>
                </a>
            <?else:?>
                </div>
            <?endif;?>
            <?
            if ($li['CHILDREN']):
            ?>
            <ul class="block_main_left_menu__list_child">
                <?foreach ($li['CHILDREN'] as $li2): ?>
                    <li <?if(isset($li2["CHILD_SELECTED"])):?>class="current"<?endif;?> <?if($li2["SELECTED"]):?>class="active"<?endif;?>>
                        <? if($li2["LINK"] != $page): ?>
                            <a class="block_main_left_menu__list_child_link" href="<?= $li2["LINK"] ?>" onclick=""  title="<?= $li["TEXT"] ?>">
                        <?else:?>
                            <div class="block_main_left_menu__list_child_link" onclick="">
                        <?endif?>
                            <?if(isset($li2["PICTURE"])):?>
                                <div class="block_main_left_menu__link-wrapper">
                                    <img class="block_main_left_menu__link-img" src="<?=$li2["PICTURE"]["src"]?>" width="<?=$li2["PICTURE"]["width"]?>" height="<?=$li2["PICTURE"]["height"]?>" alt="<?= $li2["TEXT"] ?>" title="<?= $li2["TEXT"] ?>">
                                </div>
                            <?endif?>
                            <?= $li2['TEXT'] ?>
                            <?if($li2['CHILDREN']):?>
                                <span class="block_main_left_menu__link-icon">
                                    <svg class="block_main_left_menu__link-arrow" width="8" height="12">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                                    </svg>
                                </span>
                            <?endif;?>
                        <? if($li2["LINK"] != $page): ?>
                            </a>
                        <?else:?>
                            </div>
                        <?endif;?>
                        <?if($li2['CHILDREN']):?>
                            <ul class="block_main_left_menu__list_child">
                                <?
                                foreach ($li2['CHILDREN'] as $li3):?>
                                    <li <?if($li3["SELECTED"]):?>class="active"<?endif;?>>
                                        <? if($li3["LINK"] != $page): ?>
                                            <a class="block_main_left_menu__list_child_link" href="<?= $li3["LINK"] ?>" onclick=""  title="<?= $li3["TEXT"] ?>">
                                        <?else:?>
                                            <div class="block_main_left_menu__list_child_link" onclick="">
                                        <?endif?>
                                                <?if(isset($li3["PICTURE"])):?>
                                                    <div class="block_main_left_menu__link-wrapper">
                                                        <img class="block_main_left_menu__link-img" src="<?=$li3["PICTURE"]["src"]?>" width="<?=$li3["PICTURE"]["width"]?>" height="<?=$li3["PICTURE"]["height"]?>" alt="<?= $li3["TEXT"] ?>" title="<?= $li3["TEXT"] ?>">
                                                    </div>
                                                <?endif?>
                                                <?= $li3['TEXT'] ?>
                                                <?if($li3['CHILDREN']):?>
                                                    <span class="block_main_left_menu__link-icon">
                                                        <svg class="block_main_left_menu__link-arrow" width="8" height="12">
                                                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                                                        </svg>
                                                    </span>
                                                <?endif;?>
                                        <? if($li3["LINK"] != $page): ?>
                                            </a>
                                        <?else:?>
                                            </div>
                                        <?endif;?>

                                    </li>

                                <?endforeach;?>
                            </ul>
                        <?endif;?>
                    </li>
                    <?endforeach;?>
            </ul>
                <?endif;?>
        </li>
    <?

    }
    ?>
    </ul>

    <?
}
?>
