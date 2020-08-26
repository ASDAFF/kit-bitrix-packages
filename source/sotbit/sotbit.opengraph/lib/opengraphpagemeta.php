<?
namespace Sotbit\Opengraph;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * class for page meta settings
 *
 * @author Evgenij Sidorenko < e.sidorenko@sotbit.ru >
 *
 */
class OpengraphPageMetaTable extends DataManager {
    private $arOgRequired = array('OG_TYPE', 'OG_URL', 'OG_TITLE', 'OG_IMAGE');
    private $arTwRequired = array('TW_CARD', 'TW_TITLE');
    private $errors = array();
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName() {
        return 'b_sotbit_opengraph_page_meta';
    }
    
    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap() {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_ID_FIELD'),
            ),
            'NAME' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_NAME_FIELD'),
            ),
            'ACTIVE_OG' => array(
                'data_type' => 'boolean',
                'values' => array(
                    'N',
                    'Y'
                ),
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_ACTIVE_OG_FIELD'),
            ),
            'OG_IMAGE_TYPE' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_IMAGE_TYPE'),
            ),
            'OG_IMAGE_WIDTH' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_IMAGE_WIDTH'),
            ),
            'OG_IMAGE_HEIGHT' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_IMAGE_HEIGHT'),
            ),
            'OG_IMAGE_SECURE_URL' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_IMAGE_SECURE_URL'),
            ),
            'OG_DESCRIPTION' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_DESCRIPTION'),
            ),
            'OG_TITLE' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_OGP_TITLE'),
            ),
            'OG_URL' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_URL'),
            ),
            'OG_TYPE' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_TYPE'),
            ),
            'OG_IMAGE' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_OG_IMAGE'),
            ),
            'OG_PROPS_ACTIVE' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_OG_PROPS_ACTIVE_FIELD'),
            ),
            'ACTIVE_TW' => array(
                'data_type' => 'boolean',
                'values' => array(
                    'N',
                    'Y'
                ),
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_ACTIVE_TW_FIELD'),
            ),
            'TW_CARD' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_TW_CARD'),
            ),
            'TW_TITLE' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_TW_TITLE'),
            ),
            'TW_SITE' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_TW_SITE'),
            ),
            'TW_DESCRIPTION' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_TW_DESCRIPTION'),
            ),
            'TW_IMAGE_ALT' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_TW_IMAGE_ALT'),
            ),
            'TW_CREATOR' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_TW_CREATOR'),
            ),
            'TW_PROPS_ACTIVE' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_TW_PROPS_ACTIVE_FIELD'),
            ),
            'TW_IMAGE' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_TW_IMAGE'),
            ),
            'TIMESTAMP_X' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'DATE_CREATE' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_ENTITY_DATE_CREATE_FIELD'),
            ),
            'CATEGORY_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_ENTITY_CATEGORY_ID_FIELD'),
            ),
            'SITE_ID' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_SITE_ID_FIELD'),
            ),
            'SORT' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_SORT_FIELD'),
            ),
            'ORDER' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_ORDER_FIELD'),
            )
        );
    }
    
    public static function saveImage($arImg, $case = 'OG_IMAGE') {
        if(empty($arImg))
            return false;
       
        if(is_array($arImg)) {
            $fid = \CFile::SaveFile(\CIBlock::makeFileArray($arImg, false, ""), "sotbit_opengraph");
        }
        else if(is_numeric($arImg)) {
            $fid = $arImg;
        }
        else {
            $fid = \CFile::SaveFile(\CIBlock::makeFileArray($arImg), "sotbit_opengraph");
        }
        
        if(isset($_REQUEST[$case.'_del']) && $_REQUEST[$case.'_del'] == 'Y')
            $fid = false;
        
        return $fid;
    }
    
    public function GetMixedList($arOrder = array("ID"=>"ASC"), $arFilter = array(), $bIncCnt = false, $arSelectedFields = false)
    {
        $arResult = array();
        
        $keys = array_map(function($val) {
            return '';
        }, array_flip(array_keys(self::getMap())));;
        
        $arCategoryFilter = array (
            "SORT"        => '',
            "SITE_ID"     => '',
            "NAME"        => '',
            "PARENT_ID"   => false,
            '>=DATE_CREATE' => '',
            '<=DATE_CREATE' => ''
        );
      
        $arCategoryFilter = array_intersect_key($arFilter, $arCategoryFilter);
     
        if(isset($arFilter['CATEGORY_ID']))
            $arCategoryFilter['PARENT_ID'] = $arFilter['CATEGORY_ID'];
        else if(count($arFilter) < 2)
            $arCategoryFilter['PARENT_ID'] = false;
    
        if(isset($arFilter['NAME']))
            $arCategoryFilter['NAME'] = $arFilter['NAME'];
     
        if(isset($arFilter['FIND']))
            $arCategoryFilter = self::converOr($arCategoryFilter, $arFilter['FIND']);
       
        $obCategory = new OpengraphCategoryTable;
        
        $categoryOrder = array_intersect_key($arOrder, OpengraphCategoryTable::getMap());
        
        if(empty($categoryOrder))
            $categoryOrder = array("ID"=>"ASC");

        $rsCategory = $obCategory->getList(array(
            'order' => $categoryOrder,
            'filter' => $arCategoryFilter,
            'count_total' => $bIncCnt,
            'select' => array("ID", 'NAME', "DATE_CREATE", 'TIMESTAMP_X', 'SITE_ID', 'SORT')
        ));
        while($arCategory = $rsCategory->Fetch())
        {
            $arCategory["TYPE"]="S";
            $arResult[]=$arCategory;
        }
    
        $arElementFilter = array_merge($keys, array(
            '>=DATE_CREATE' => '',
            '<=DATE_CREATE' => ''
        ));
        
        $arElementFilter = array_intersect_key($arFilter, $arElementFilter);
        
        if(isset($arFilter['NAME']))
            $arElementFilter['NAME'] = $arFilter['NAME'];
    
        if(isset($arFilter['CATEGORY_ID']))
            $arElementFilter['CATEGORY_ID'] = $arFilter['CATEGORY_ID'];
//        else if(!isset($_REQUEST['apply_filter']) && !isset($arFilter['FIND']) || (isset($_POST['clear_nav']) && $_POST['clear_nav'] == 'Y' && empty($arFilter['FIND'])))
        else if(count($arFilter) < 2)
            $arElementFilter['CATEGORY_ID'] = false;
    
        if(isset($arFilter['FIND']) && !empty($arFilter['FIND']))
            $arElementFilter = self::converOr($arElementFilter, $arFilter['FIND'], true);
        
        if(!is_array($arSelectedFields))
            $arSelectedFields = array('*');
     
        $obElement = new OpengraphPageMetaTable;
       
        $rsElement = $obElement->GetList(array(
            'order' => $arOrder,
            'filter' => $arElementFilter,
            'select' => $arSelectedFields
        ));
        
        while($arElement = $rsElement->Fetch())
        {
            $arElement["TYPE"]="E";
            $arResult[]=$arElement;
        }
        
        $rsResult = new \CDBResult;
        $rsResult->InitFromArray($arResult);
        
        return $rsResult;
    }
    
    private function converOr($filter, $find, $isProduct = false) {
        if(!$isProduct)
            $keys = array_keys(OpengraphCategoryTable::getMap());
        else
            $keys = array_keys(OpengraphPageMetaTable::getMap());
        
        $result = array(
            self::getFilterLike($keys, $find, $isProduct),
            $filter
        );

        return $result;
    }
    
    private function getFilterLike(array $keys, $findValue, $isProduct = false) {
        $keys = array_flip($keys);
        if(!$isProduct) {
            unset($keys['PARENT']);
            unset($keys['PARENT_ID']);
        } else {
            unset($keys['OG_PROPS_ACTIVE']);
            unset($keys['TW_PROPS_ACTIVE']);
            unset($keys['OG_IMAGE']);
            unset($keys['TW_IMAGE']);
            unset($keys['CATEGORY_ID']);
        }
        
        $keys = array_keys($keys);
        
        $result = array(
            'LOGIC' => 'OR',
        );
        
        foreach($keys as $key) {
            $result[] = array('%='.$key => '%'.$findValue.'%');
        }
        
        return $result;
    }
    
    public function checkRuleToSave(array $arFields) {
    
        $checkFields = array(
            'SITE_ID' => $arFields['SITE_ID']
        );
        
        if(isset($arFields['ACTIVE_OG']) && $arFields['ACTIVE_OG'] == 'Y') {
            $checkFields = array_merge($checkFields, array_intersect_key($arFields, array_flip($this->arOgRequired)));
        }
    
        if(isset($arFields['ACTIVE_TW']) && $arFields['ACTIVE_TW'] == 'Y') {
            $checkFields = array_merge($checkFields, array_intersect_key($arFields, array_flip($this->arTwRequired)));
        }
    
        $checkFields = array_filter($checkFields, function($v, $k) {
            return empty($v);
        }, ARRAY_FILTER_USE_BOTH );
        
        $this->fillErrors($checkFields);
        
        return empty($checkFields);
    }
    
    private function fillErrors(array $arFields) {
        foreach($arFields as $key => $val) {
            $this->errors[$key] = Loc::getMessage('SOTBIT_OPENGRAPH_PAGE_META_ENTITY_REQUIRED_FIELD_ERROR')." [".Loc::getMessage('SOTBIT_OPENGRAPH_'.$key)."]";
        }
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

?>