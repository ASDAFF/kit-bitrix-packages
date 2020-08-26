<?php
/**
 * Copyright (c) 25/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Kit\Origami\Helper\Config;
?>
<div class="block_main_left_menu">
    <?
    include \Kit\Origami\Helper\Config::getChunkPath('side');
    ?>
    <div class="block_main_left_menu__content">
        <div class="row personal_block_component">
            <div class="col-sm-12">
                <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:breadcrumb',
                    'origami_default',
                    [
                        "START_FROM" => "0",
                        "PATH"       => "",
                        "SITE_ID"    => "-",
                    ],
                    false,
                    [
                        'HIDE_ICONS' => 'N',
                    ]
                );
                ?>
            </div>
            <div class="col-sm-12 personal_title_block">
                <h1 class="personal_title fonts__middle_title">
                    <?php $APPLICATION->ShowTitle(false); ?>
                </h1>
            </div>
        </div>
        <?
        switch ($template)
        {
            case 'sections_1':
                $template = "origami_default";
                break;
            case 'sections_2':
                $template = "origami_default_2";
                break;
        }


        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            $template,
            [
                "IBLOCK_TYPE"        => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID"          => $arParams["IBLOCK_ID"],
                "CACHE_TYPE"         => $arParams["CACHE_TYPE"],
                "CACHE_TIME"         => $arParams["CACHE_TIME"],
                "CACHE_GROUPS"       => $arParams["CACHE_GROUPS"],
                "COUNT_ELEMENTS"     => "N",
                "TOP_DEPTH"          => 1,
                "COUNT_ELEMENTS_CURRENT"     => $arParams["SECTION_COUNT_ELEMENTS"],
                "TOP_DEPTH_CURRENT"          => $arParams["SECTION_TOP_DEPTH"],

                "SECTION_URL"        => $arResult["FOLDER"]
                    .$arResult["URL_TEMPLATES"]["section"],
                "VIEW_MODE"          => $arParams["SECTIONS_VIEW_MODE"],
                "SHOW_PARENT_NAME"   => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                "HIDE_SECTION_NAME"  => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"])
                    ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"])
                    ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
            ],
            $component,
            ($arParams["SHOW_TOP_ELEMENTS"] !== "N" ? ["HIDE_ICONS" => "Y"]
                : [])
        );
        ?>

    </div>
</div>
