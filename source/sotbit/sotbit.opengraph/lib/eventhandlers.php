<?
namespace Sotbit\Opengraph;
use Bitrix\Main\Localization\Loc;

/**
 * class for interception events
 *
 * @author Evgenij Sidorenko < e.sidorenko@sotbit.ru >
 *
 */
class EventHandlers {
    public function OnBeforeEndBufferContent() {
        if(\CSite::InDir('/bitrix/'))
            return;
    
//        global $APPLICATION;
//        var_dump($APPLICATION->GetProperty("canonical"));
//        exit;

        $opengraph = new \OpengraphMain();
        $opengraph->setUrlMeta(SITE_ID);
        $opengraph->setScriptUrlMeta(SITE_ID);
    }
    
    public function OnBeforeProlog() {
        if(\CSite::InDir('/bitrix/'))
            return;
        
        \OpengraphMain::setMetaOnPageStart();
    }
    
    public function OnBeforeEpilog() {
    }
    
    public function OnAfterEpilog() {
    }
    
    public static function onPanelCreate()
    {
        global $USER, $APPLICATION;
        $POST_RIGHT = $APPLICATION->GetGroupRight( 'sotbit.opengraph' );
        
        if ($POST_RIGHT != "D")
        {
            $href = $APPLICATION->GetPopupLink(array(
                                        "URL"=> "\bitrix\admin\public_sotbit_opengraph_meta_edit.php?lang=ru&site=s1",
                                        "PARAMS"=>array(
                                            "width"=>780,
                                            "height"=>605,
                                            "resizable"=>true,
                                            "min_width"=> 780,
                                            "min_height"=> 605,
                                            'dialog_type' => 'EDITOR',
                                        ),
                                    ));
    
            $Menu[] = array (
                'TEXT' => Loc::getMessage( 'SOTBIT_OPENGRAPH_CHANGE_META', array (
                    '#ID#' => 'panel'
                ) ),
                'ACTION' => $href,
            );
    
            $APPLICATION->AddPanelButton(
                array(
                "HREF"=> "javascript:".$href,
                "TYPE" => "BIG",
                'SRC' => '/bitrix/images/sotbit.opengraph/icon.png',
                "ALT"=>Loc::getMessage( 'SOTBIT_OPENGRAPH_PANEL_BUTTON_ALT'),
                'TEXT' => Loc::getMessage( 'SOTBIT_OPENGRAPH_PANEL_BUTTON_TITLE'),
                "MAIN_SORT"=>"1500",
                "SORT"=>10,
                "MENU"=> $Menu,
            ));
        }
    }
}

?>