<?php

namespace Sotbit\Crosssell\Helper;

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
            $moduleInclude = Loader::includeModule('sotbit.crosssell');
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
                "url"   => '/bitrix/admin/sotbit_crosssell_list.php?lang='
                    .LANGUAGE_ID.'&site='.$lid,
                "title" => Loc::getMessage('LIST_CONDITION'),
                "more_url" => array(
                    "sotbit_crosssell_list.php",
                    "sotbit_crosssell.php",
                    "sotbit_crosssell_category.php"
                ),
            ];
        }
        foreach ($sites as $lid => $name) {
            array_push($crosssellSettingsSites,
                [
                    "text"  => '[' .$lid. '] ' . $name,
                    "url"   => '/bitrix/admin/sotbit_crosssell_settings.php?lang='
                        .LANGUAGE_ID.'&site='.$lid,
                    "title" => '[' .$lid. '] ' . $name,
                    "more_url" => array(
                        "sotbit_crosssell_settings.php"
                    ),
                ]
            );
        }

        if (!isset($arGlobalMenu['global_menu_sotbit'])) {
            $arGlobalMenu['global_menu_sotbit'] = [
                'menu_id'   => 'sotbit',
                'text'      => Loc::getMessage(
                    \SotbitCrosssell::moduleId.'_GLOBAL_MENU'
                ),
                'title'     => Loc::getMessage(
                    \SotbitCrosssell::moduleId.'_GLOBAL_MENU'
                ),
                'sort'      => 1000,
                'items_id'  => 'global_menu_sotbit_items',
                "icon"      => "",
                "page_icon" => "",
            ];
        }

        $menu = [];
        if ($GLOBALS['APPLICATION']->GetGroupRight(\SotbitCrosssell::moduleId) >= 'R') {
            $menu = [
                "section"   => "sotbit_crosssell",
                "menu_id"   => "sotbit_crosssell",
                "sort"      => 425,
                'id'        => "crosssell",
                "text"      => Loc::getMessage(
                    \SotbitCrosssell::moduleId.'_GLOBAL_MENU_CROSSSELL'
                ),
                "title"     => Loc::getMessage(
                    \SotbitCrosssell::moduleId.'_GLOBAL_MENU_CROSSSELL'
                ),
                "icon"      => "sotbit_crosssell_menu_icon",
                "page_icon" => "",
                "items_id"  => "global_menu_sotbit_crosssell",
                "items"     => array($crosssellList),
            ];
        }
        $arGlobalMenu['global_menu_sotbit']['items']['sotbit.crosssell'] = $menu;
    }
}

?>
