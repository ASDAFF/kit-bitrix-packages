<?php

namespace Kit\Crosssell\Helper;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class Menu
{
    public static function getAdminMenu(&$arGlobalMenu, &$arModuleMenu) {
        $moduleInclude = false;
        try {
            $moduleInclude = Loader::includeModule('kit.crosssell');
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $sites = Config::getSites();
        $crosssellPage = [];
        $crosssellList = [];
        $crosssellSettingsSites = [];

        foreach ($sites as $lid => $name) {
            $crosssellList = [
                "text"  => Loc::getMessage('LIST_CONDITION'),
                "url"   => '/bitrix/admin/kit_crosssell_list.php?lang='
                    .LANGUAGE_ID.'&site='.$lid,
                "title" => Loc::getMessage('LIST_CONDITION'),
                "more_url" => array(
                    "kit_crosssell_list.php",
                    "kit_crosssell.php",
                    "kit_crosssell_category.php"
                ),
            ];
        }
        foreach ($sites as $lid => $name) {
            array_push($crosssellSettingsSites,
                [
                    "text"  => '[' .$lid. '] ' . $name,
                    "url"   => '/bitrix/admin/kit_crosssell_settings.php?lang='
                        .LANGUAGE_ID.'&site='.$lid,
                    "title" => '[' .$lid. '] ' . $name,
                    "more_url" => array(
                        "kit_crosssell_settings.php"
                    ),
                ]
            );
        }

        if (!isset($arGlobalMenu['global_menu_kit'])) {
            $arGlobalMenu['global_menu_kit'] = [
                'menu_id'   => 'kit',
                'text'      => Loc::getMessage(
                    \KitCrosssell::moduleId.'_GLOBAL_MENU'
                ),
                'title'     => Loc::getMessage(
                    \KitCrosssell::moduleId.'_GLOBAL_MENU'
                ),
                'sort'      => 1000,
                'items_id'  => 'global_menu_kit_items',
                "icon"      => "",
                "page_icon" => "",
            ];
        }

        $menu = [];
        if ($GLOBALS['APPLICATION']->GetGroupRight(\KitCrosssell::moduleId) >= 'R') {
            $menu = [
                "section"   => "kit_crosssell",
                "menu_id"   => "kit_crosssell",
                "sort"      => 425,
                'id'        => "crosssell",
                "text"      => Loc::getMessage(
                    \KitCrosssell::moduleId.'_GLOBAL_MENU_CROSSSELL'
                ),
                "title"     => Loc::getMessage(
                    \KitCrosssell::moduleId.'_GLOBAL_MENU_CROSSSELL'
                ),
                "icon"      => "kit_crosssell_menu_icon",
                "page_icon" => "",
                "items_id"  => "global_menu_kit_crosssell",
                "items"     => array($crosssellList),
            ];
        }
        $arGlobalMenu['global_menu_kit']['items']['kit.crosssell'] = $menu;
    }
}

?>