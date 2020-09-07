<?
namespace Sotbit\Opengraph\Helper;

use \Sotbit\Opengraph\OpengraphPageMetaTable;
use \Sotbit\Opengraph\OpengraphCategoryTable;

class OpengraphMetaAction {
    public static function simpleAction ($ID, $action) {
        switch($action) {
            case 'delete' :
                OpengraphPageMetaTable::delete($ID);
                break;
            case 'active_og':
                OpengraphPageMetaTable::update($ID, array('ACTIVE_OG' => 'Y'));
                break;
            case 'active_tw':
                OpengraphPageMetaTable::update($ID, array('ACTIVE_TW' => 'Y'));
                break;
            case 'deactive_og':
                OpengraphPageMetaTable::update($ID, array('ACTIVE_OG' => 'N'));
                break;
            case 'deactive_tw':
                OpengraphPageMetaTable::update($ID, array('ACTIVE_TW' => 'N'));
                break;
        }
    }
    
    public static function groupAction ($idList, $action) {
        file_put_contents(dirname(__FILE__).'/log.log', print_r($idList, true), FILE_APPEND);
        if($action != 'edit' && $action != 'delete')
            $idList = array_map(function($v){
                return preg_replace('/[a-zAA-Z]+/ui', '', $v);
            }, $idList);
        
        file_put_contents(dirname(__FILE__).'/log.log', print_r($idList, true), FILE_APPEND);
        
        switch($action) {
            case 'delete' :
                foreach($idList as $id)  {
    
                    if(strpos($id, "E") !== false) {
                        $entity = '\Sotbit\Opengraph\OpengraphPageMetaTable';
                    }else {
                        $entity = '\Sotbit\Opengraph\OpengraphCategoryTable';
                    }
    
                    $id = str_replace(array('E', 'S'), array('',''), $id);
    
                    $entity::delete($id);
                }
                break;
            case 'active_og':
                foreach($idList as $id)
                    OpengraphPageMetaTable::update($id, array('ACTIVE_OG' => 'Y'));
                break;
            case 'active_tw':
                foreach($idList as $id)
                    OpengraphPageMetaTable::update($id, array('ACTIVE_TW' => 'Y'));
                break;
            case 'deactive_og':
                foreach($idList as $id)
                    OpengraphPageMetaTable::update($id, array('ACTIVE_OG' => 'N'));
                break;
            case 'deactive_tw':
                foreach($idList as $id)
                    OpengraphPageMetaTable::update($id, array('ACTIVE_TW' => 'N'));
                break;
            case 'edit':
                foreach($idList as $id => $arFields) {
                    unset($arFields['DATE_CREATE']);
                    unset($arFields['TIMESTAMP_X']);

                    if(strpos($id, "E") !== false) {
                        $entity = '\Sotbit\Opengraph\OpengraphPageMetaTable';
                    }else {
                        $entity = '\Sotbit\Opengraph\OpengraphCategoryTable';
                    }
                    
                    $id = str_replace(array('E', 'S'), array('',''), $id);
                    
                    $entity::update($id, $arFields);
                }
                break;
        }
    }
    
    
}

?>