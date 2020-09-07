<?
IncludeModuleLangFile(__FILE__);

$iModuleID = "sotbit.opengraph";

if ($APPLICATION->GetGroupRight($iModuleID) != "D") {
    
    $rsSites = CSite::GetList($by="sort", $order="ASC", Array("ACTIVE"=>"Y"));
    while ($arSite = $rsSites->Fetch())
    {
        $Sites[]=$arSite;
    }
    
    $arDefSites = CSite::GetList($by="sort", $order="desc", Array("ACTIVE"=>"Y", 'DEFAULT' => 'Y')) ->fetch();
    
    unset($rsSites);
    unset($arSite);
    
    $Paths=array('settings'=>'.php');
    if(count($Sites)==1)//If one site
    {
        $Site = current($Sites);
        $Settings[$key]=array(
            "text" => "[$Site[LID]] ".GetMessage("MENU_OPENGRAPH_SETTING_TEXT"),
            "items_id" => "menu_sotbit.opengraph_settings_" . $Site['LID'],
            "dynamic" => true,
            "items" =>  array(
                array(
                    "text" => GetMessage("MENU_OPENGRAPH_LIST_PAGE"),
                    "url" => "sotbit.opengraph_url_list.php?lang=".LANGUAGE_ID.'&site='.$Site['LID'],
                    "title" => $Site['NAME'],
                    'more_url' => array(
                        "sotbit.opengraph_url_edit.php?lang=ru&site=".$Site['LID'],
                        "sotbit.opengraph_category_edit.php?lang=ru&site=".$Site['LID']
                    ),
                ),
                array(
                    "text" => GetMessage("MENU_OPENGRAPH_SETTINGS_TEXT"),
                    "url" => "sotbit.opengraph_settings.php?lang=".LANGUAGE_ID.'&site='.$Site['LID'],
                    "title" => $Site['NAME']
                )
            ),
            "title" => GetMessage("MENU_OPENGRAPH_" . strtoupper($key) . "_TEXT")
        );
    }
    else//If some site
    {
        $Items=array();
        foreach($Paths as $key=>$Path)
        {
            foreach($Sites as $Site)
            {
                $Settings[] = array(
                    "text" => "[$Site[LID]] ".GetMessage("MENU_OPENGRAPH_SETTING_TEXT"),
                    "items_id" => "menu_sotbit.opengraph_settings_" . $Site['LID'],
                    "dynamic" => true,
                    "items" =>  array(
                        array(
                            "text" => GetMessage("MENU_OPENGRAPH_LIST_PAGE"),
                            "url" => "sotbit.opengraph_url_list.php?lang=".LANGUAGE_ID.'&site='.$Site['LID'],
                            "title" => $Site['NAME'],
                            'more_url' => array(
                                "sotbit.opengraph_url_edit.php?lang=ru&site=".$Site['LID'],
                                "sotbit.opengraph_category_edit.php?lang=ru&site=".$Site['LID']
                            ),
                        ),
                        array(
                            "text" => GetMessage("MENU_OPENGRAPH_SETTINGS_TEXT"),
                            "url" => "sotbit.opengraph_settings.php?lang=".LANGUAGE_ID.'&site='.$Site['LID'],
                            "title" => $Site['NAME']
                        )
                    ),
                    "title" => GetMessage("MENU_OPENGRAPH_" . strtoupper($key) . "_TEXT")
                );
            }
        }
        
    }
    
    $parent = 'global_menu_marketing';
    if(\Bitrix\Main\Loader::includeModule('sotbit.missshop') && $APPLICATION->GetGroupRight('sotbit.missshop') != "D")
    {
        $parent = 'global_menu_missshop';
    }
    if(\Bitrix\Main\Loader::includeModule('sotbit.mistershop') && $APPLICATION->GetGroupRight('sotbit.mistershop') != "D")
    {
        $parent = 'global_menu_mistershop';
    }
    if(\Bitrix\Main\Loader::includeModule('sotbit.b2bshop') && $APPLICATION->GetGroupRight('sotbit.b2bshop') != "D")
    {
        $parent = 'global_menu_b2bshop';
    }

    $aMenu = array(
        "parent_menu" => $parent,//"global_menu_b24connector",
        "section" => 'sotbit.opengraph',
        "sort" => 2000,
        "text" => GetMessage("MENU_OPENGRAPH_TEXT"),
        "title" => GetMessage("MENU_OPENGRAPH_TITLE"),
        "url" => (!empty($arDefSites['LID'])) ? "sotbit.opengraph_settings.php?lang=" . LANGUAGE_ID. "&site=".$arDefSites['LID'] : '',
        "icon" => "opengraph_menu_icon",
        "page_icon" => "opengraph_page_icon",
        "items_id" => "menu_sotbit.opengraph",
        'more_url' => array(
            "sotbit.opengraph_url_edit.php",
        ),
        "dynamic" => true,
        'items' => $Settings,
    );
    return $aMenu;
}

return false;
?>