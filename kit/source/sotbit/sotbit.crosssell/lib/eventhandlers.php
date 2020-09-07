<?
namespace Sotbit\Crosssell;

use Sotbit\Crosssell\Helper\Menu;

class EventHandlers
{
    /**
     * @param $arGlobalMenu
     * @param $arModuleMenu
     * @param $content
     */
    public function OnBuildGlobalMenuHandler(&$arGlobalMenu, &$arModuleMenu){
        Menu::getAdminMenu($arGlobalMenu, $arModuleMenu);
    }

}

?>