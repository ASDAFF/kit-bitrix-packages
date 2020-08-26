<?
namespace Kit\Crosssell\Helper;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Kit\Crosssell\Orm\CrosssellTable;
use \Kit\Crosssell\Orm\CrosssellCategoryTable;
Loader::includeModule('iblock');


class CrosssellAction
{
    public static function simpleAction ($ID, $action, $type) {
        switch($action)
        {
            case 'delete' :
                if($type == 'E')
                    CrosssellTable::delete($ID);
                if($type == "S")
                {
                    CrosssellCategoryTable::delete($ID);
                    $rs = CrosssellTable::getList(array(
                        'select' => array('ID'),
                        'filter' => array('CATEGORY_ID' => $ID)
                    ));
                    if(count($rs) > 0) {
                        while($ar = $rs->fetch())
                            CrosssellTable::delete($ar['ID']);
                    }
                }

                break;
        }
    }
    
    public static function groupAction ($idList, $action)
    {

        if($action != 'edit' && $action != 'delete')
            $idList = array_map(function($v){
                return preg_replace('/[a-zAA-Z]+/ui', '', $v);
            }, $idList);
        
        switch($action) {
            case 'delete' :
                foreach($idList as $id)  {
    
                    if(strpos($id, "E") !== false) {
                        $entity = '\Kit\Crosssell\Orm\CrosssellTable';
                    } else {
                        $catId = str_replace(array('S'), array(''), $id);
                        $entity = '\Kit\Crosssell\Orm\CrosssellCategoryTable';
                        $rs = CrosssellTable::getList(array(
                            'select' => array('ID'),
                            'filter' => array('CATEGORY_ID' => $catId)
                        ));
                        if(count($rs) > 0) {
                            while($ar = $rs->fetch())
                                CrosssellTable::delete($ar['ID']);
                        }
                    }

                    $id = str_replace(array('E', 'S'), array('',''), $id);
                    $entity::delete($id);
                }
                break;
            case 'edit':
                foreach($idList as $id => $arFields) {
                    unset($arFields['DATE_CREATE']);
                    unset($arFields['TIMESTAMP_X']);

                    if(strpos($id, "E") !== false) {
                        $entity = '\Kit\Crosssell\Orm\CrosssellTable';
                    } else {
                        $entity = '\Kit\Crosssell\Orm\CrosssellCategoryTable';
                    }
                    $id = str_replace(array('E', 'S'), array('',''), $id);
                    if($arFields['Active'] == Loc::getMessage('KIT_CROSSSELL_ACTION_YES')) {
                        $arFields['Active'] = 'Y';
                    } else {
                        $arFields['Active'] = 'N';
                    }

                    $entity::update($id, $arFields);
                }
                break;
        }
    }
    
    
}

?>