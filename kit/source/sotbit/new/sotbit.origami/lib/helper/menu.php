<?php

namespace Sotbit\Origami\Helper;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

/**
 * Class Menu
 *
 * @package Sotbit\Origami\Helper
 * @author  Sergey Danilkin <s.danilkin@sotbit.ru>
 */
class Menu
{
    public $iArCur = array();
    public $depthLevel = 1;

    /**
     * @param $arGlobalMenu
     * @param $arModuleMenu
     */
    public static function getAdminMenu(
        &$arGlobalMenu,
        &$arModuleMenu
    ) {
        $moduleInclude = false;
        try {
            $moduleInclude = Loader::includeModule('sotbit.origami');
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $sites = Config::getSites();
        $settings = [];
        $develop = [];
        foreach ($sites as $lid => $name) {
            $settings[$lid] = [
                "text"  => ' ['.$lid.'] '.$name,
                "url"   => '/bitrix/admin/sotbit_origami_settings.php?lang='
                    .LANGUAGE_ID.'&site='.$lid,
                "title" => ' ['.$lid.'] '.$name,
            ];
            $develop[$lid] = [
                "text"  => ' ['.$lid.'] '.$name,
                "url"   => '/bitrix/admin/sotbit_origami_develop.php?lang='
                    .LANGUAGE_ID.'&site='.$lid,
                "title" => ' ['.$lid.'] '.$name,
            ];
        }

        if (!isset($arGlobalMenu['global_menu_sotbit'])) {
            $arGlobalMenu['global_menu_sotbit'] = [
                'menu_id'   => 'sotbit',
                'text'      => Loc::getMessage(
                    \SotbitOrigami::moduleId.'_GLOBAL_MENU'
                ),
                'title'     => Loc::getMessage(
                    \SotbitOrigami::moduleId.'_GLOBAL_MENU'
                ),
                'sort'      => 1000,
                'items_id'  => 'global_menu_sotbit_items',
                "icon"      => "",
                "page_icon" => "",
            ];
        }

        $menu = [];
        if ($moduleInclude) {
            if ($GLOBALS['APPLICATION']->GetGroupRight(\SotbitOrigami::moduleId)
                >= 'R'
            ) {
                $menu = [
                    "section"   => "sotbit_origami",
                    "menu_id"   => "sotbit_origami",
                    "sort"      => 1,
                    'id'        => 'origami',
                    "text"      => Loc::getMessage(
                        \SotbitOrigami::moduleId.'_GLOBAL_MENU_ORIGAMI'
                    ),
                    "title"     => Loc::getMessage(
                        \SotbitOrigami::moduleId.'_GLOBAL_MENU_ORIGAMI'
                    ),
                    "icon"      => "sotbit_origami_menu_icon",
                    "page_icon" => "",
                    "items_id"  => "global_menu_sotbit_origami",
                    "items"     => [
                        [
                            'text'      => Loc::getMessage(
                                \SotbitOrigami::moduleId.'_SETTINGS'
                            ),
                            'title'     => Loc::getMessage(
                                \SotbitOrigami::moduleId.'_SETTINGS'
                            ),
                            'sort'      => 10,
                            'icon'      => '',
                            'page_icon' => '',
                            "items_id"  => "settings",
                            'items'     => $settings,
                        ],
                        [
                            'text'      => Loc::getMessage(
                                \SotbitOrigami::moduleId.'_DEVELOP'
                            ),
                            'title'     => Loc::getMessage(
                                \SotbitOrigami::moduleId.'_DEVELOP'
                            ),
                            'sort'      => 20,
                            'icon'      => '',
                            'page_icon' => '',
                            "items_id"  => "develop",
                            'items'     => $develop,
                        ],
                    ],
                ];
            }
        }
        $arGlobalMenu['global_menu_sotbit']['items']['sotbit.origami'] = $menu;
    }

    /**
     * @param       $key
     * @param array $arResult
     *
     * @return array
     */
    public function findMenuChildren($key, $arResult = [])
    {
        $return = [];
        $bool = false;
        if (!$arResult[$key]['IS_PARENT']) {
            //return $return;
        }
        $level = $arResult[$key]['DEPTH_LEVEL'];
        $cnt = count($arResult);
        for ($i = $key + 1; $i < $cnt; ++$i) {
            if ($arResult[$i]['DEPTH_LEVEL'] == $level + 1) {

                if($arResult[$i]['IS_PARENT'])
                {
                    $children = $this->findMenuChildren($i, $arResult);
                    $arResult[$i]['CHILDREN'] = $children;
                }

                $return[] = $arResult[$i];
                $bool = true;
                if($arResult[$i]['IS_PARENT'] && $arResult[$i]['DEPTH_LEVEL'] == $arResult[$i+1]['DEPTH_LEVEL'])
                    break;
            }

            if ($arResult[$i]['DEPTH_LEVEL'] < $level) {
                break;
            }

            if($arResult[$i]['DEPTH_LEVEL'] == $arResult[$key]['DEPTH_LEVEL'])
                break;

            if($arResult[$i]['DEPTH_LEVEL'] == $level && $arResult[$key]['IS_PARENT'])
            {
                $return[] = $arResult[$i];
            }
        }

        return $return;
    }

    public function getMenuRootCatalog($arResult = array(), $bWasSelected = false, $iblockID = 0)
    {
        if(!$iblockID)
            return false;

        global $APPLICATION;

        $page = $APPLICATION->GetCurPage(false);
        $tootPage = "";
        $active = false;
        $arRootCatalog = array();

        $obCache = new \CPHPCache();
        if ($obCache->InitCache(36000, serialize(array($arResult, $iblockID)), "/sotbit.origami/root_menu"))
        {
            $rootPage = $obCache->GetVars();
        }elseif ($obCache->StartDataCache()){
            $dbBlock = \CIBlock::GetList(
                array("SORT"=>"ASC"),
                array("ID" => $iblockID),
                false
            );

            while($arBlock = $dbBlock->GetNext())
            {
                $rootPage = str_replace("#SITE_DIR#/", SITE_DIR, $arBlock["LIST_PAGE_URL"]);
            }

            $obCache->EndDataCache($rootPage);
        }


        if($rootPage == $page)
            $active = true;

        $arRootCatalog = array(
            "TEXT" => Loc::getMessage('SOTBIT_MAIN_MENU_CATALOG'),
            "DEPTH_LEVEL" => 0,
            "LINK" => $rootPage,
            "SELECTED" => $active
        );
        if($bWasSelected)
            $arRootCatalog["CHILD_SELECTED"] = 1;

        if(!empty($arRootCatalog))
            array_unshift($arResult, $arRootCatalog);

        return $arResult;
    }

    public function findMenuChildren1(&$arResult = [])
    {
        foreach ($arResult as $key => $arItem) {

            if ($arItem['DEPTH_LEVEL'] == 1) {

                $depthCur = $arItem["DEPTH_LEVEL"];
                $keyCur = $key;

                if($arItem["IS_PARENT"])
                    $arItem["CHILDREN"] = $this->findMenuChildren2($arResult, $depthCur, $keyCur+1);

                $arResult[$key] = $arItem;

            }
        }

        $arResult = array_values($arResult);

        return $arResult;
    }

    public function findMenuChildren2(&$arResult = [], $depth, $key, &$arChildren = array())
    {
        $j = count($arChildren);
        $this->depthLevel = $depth;

        foreach($arResult as $i => $arMenu)
        {

            if(isset($this->iArCur[$arMenu["LINK"]])) continue;


            if($i < $key)
                continue 1;

            if($i == $key)
            {
                $arChildren[$j] = $arMenu;
                $depthCur = $arMenu["DEPTH_LEVEL"];
                unset($arResult[$i]);
            }
            if($i > $key && $arMenu["DEPTH_LEVEL"] == $depthCur)
            {
                $arChildren[$j] = $arMenu;

                $depthCur = $arMenu["DEPTH_LEVEL"];
                unset($arResult[$i]);

            }
            if($i > $key && $arMenu["DEPTH_LEVEL"] < $depthCur)
            {
                return $arChildren;
            }
            if($i > $key && $arMenu["DEPTH_LEVEL"] > $depthCur)
            {

                $depthCur = $depthPrev;

                $arChildren[$j-1]["CHILDREN"] = $this->findMenuChildren2($arResult, $depthCur, $i);
            }

            $arOldMenu = $arMenu;
            $j++;
            $depthPrev = $arMenu["DEPTH_LEVEL"];
            $this->iCar = $i;
            $this->iArCur[$arMenu["LINK"]] = 1;
        }

        return $arChildren;
    }
}
?>