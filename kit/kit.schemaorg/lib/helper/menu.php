<?php

namespace Kit\Schemaorg\Helper;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class Menu
{
    public static function getAdminMenu(
        &$arGlobalMenu,
        &$arModuleMenu
    ) {

        $sites = Site::getList();
        $settings = [];
        $develop = [];
        foreach ($sites as $lid => $name) {
            $settings[$lid] = [
                "text"  => ' ['.$lid.'] '.$name,
                "url"   => '/bitrix/admin/kit_origami_settings.php?lang='
                    .LANGUAGE_ID.'&SITE_ID='.$lid,
                "title" => ' ['.$lid.'] '.$name,
            ];
            $develop[$lid] = [
                "text"  => ' ['.$lid.'] '.$name,
                "url"   => '/bitrix/admin/kit_origami_develop.php?lang='
                    .LANGUAGE_ID.'&SITE_ID='.$lid,
                "title" => ' ['.$lid.'] '.$name,
            ];
        }

        if (!isset($arGlobalMenu['global_menu_kit'])) {
            $arGlobalMenu['global_menu_kit'] = [
                'menu_id'   => 'kit',
                'text'      => Loc::getMessage(
                    \KitSchema::MODULE_ID.'_GLOBAL_MENU'
                ),
                'title'     => Loc::getMessage(
                    \KitSchema::MODULE_ID.'_GLOBAL_MENU'
                ),
                'sort'      => 1000,
                'items_id'  => 'global_menu_kit_items',
                "icon"      => "",
                "page_icon" => "",
            ];
        }

        $iModuleID = "kit.schemaorg";
        global $APPLICATION;
        if ($APPLICATION->GetGroupRight($iModuleID) != "D") {

            $rsSites = \CSite::GetList($by="sort", $order="ASC", Array("ACTIVE"=>"Y"));
            while ($arSite = $rsSites->Fetch())
            {
                $Sites[]=$arSite;
            }

            unset($rsSites);
            unset($arSite);

            $Paths=array('settings'=>'.php');
            if(count($Sites)==1)//If one site
            {
                $Site = current($Sites);
                $Settings[$key]=array(
                    "text" => "[$Site[LID]] ".GetMessage("MENU_SCHEMA_SETTING_TEXT"),
                    "items_id" => "menu_kit.schema_settings_" . $Site['LID'],
                    "dynamic" => true,
                    "items" =>  array(
                        array(
                            "text" => GetMessage("MENU_SCHEMA_URL_LIST_TEXT"),
                            "url" => "kit.schema_url_list.php?lang=".LANGUAGE_ID.'&SITE_ID='.$Site['LID'],
                            "title" => $Site['NAME'],
                            'more_url' => array(
                                "kit.schema_category_edit.php?lang=ru&SITE_ID=".$Site['LID'],
                                "kit.schema_edit.php?lang=ru&SITE_ID=".$Site['LID']
                            ),
                        ),
                        array(
                            "text" => GetMessage("MENU_SCHEMA_SETTINGS_TEXT"),
                            "url" => "kit.schema_settings.php?lang=".LANGUAGE_ID.'&SITE_ID='.$Site['LID'],
                            "title" => $Site['NAME'],
                            'more_url' => array(
                                "kit.schema_category_edit.php?lang=ru&SITE_ID=".$Site['LID'],

                            ),
                        )
                    ),
                    "title" => GetMessage("MENU_SCHEMA_" . strtoupper($key) . "_TEXT")
                );
            }
            else//If some site
            {
                foreach ($Paths as $key => $Path) {
                    foreach ($Sites as $Site) {
                        $Settings[] = array(
                            "text" => "[$Site[LID]] " . GetMessage("MENU_SCHEMA_EDIT_TEXT"),
                            "items_id" => "menu_kit.schema_settings_" . $Site['LID'],
                            "dynamic" => true,
                            "items" => array(
                                array(
                                    "text" => GetMessage("MENU_SCHEMA_URL_LIST_TEXT"),
                                    "url" => "kit.schema_url_list.php?lang=" . LANGUAGE_ID . '&SITE_ID=' . $Site['LID'],
                                    "title" => $Site['NAME'],
                                    'more_url' => array(
                                        "kit.schema_category_edit.php?lang=ru&SITE_ID=".$Site['LID'],
                                        "kit.schema_edit.php?lang=ru&SITE_ID=".$Site['LID']
                                    ),
                                ),
                                array(
                                    "text" => GetMessage("MENU_SCHEMA_SETTINGS_TEXT"),
                                    "url" => "kit.schema_settings.php?lang=" . LANGUAGE_ID . '&SITE_ID=' . $Site['LID'],
                                    "title" => $Site['NAME'],
                                    'more_url' => array(
                                        "kit.schema_category_edit.php?lang=ru&SITE_ID=".$Site['LID']
                                    ),
                                )
                            ),
                            "title" => GetMessage("MENU_SCHEMA_" . strtoupper($key) . "_TEXT")
                        );
                    }
                }
            }

            $aMenu = array(
                "parent_menu" => 'global_menu_kit',
                "section" => 'kit.schemaorg',
                "sort" => 800,
                "text" => GetMessage("MENU_SCHEMA_TEXT"),
                "title" => GetMessage("MENU_SCHEMA_TITLE"),
                "icon" => "schema_menu_icon",
                "page_icon" => "schema_page_icon",
                "items_id" => "menu_kit.schema",
                "dynamic" => true,
                'items' => $Settings,
            );

            $arGlobalMenu['global_menu_kit']['items']['kit.schemaorg'] = $aMenu;
        }
    }
}

?>