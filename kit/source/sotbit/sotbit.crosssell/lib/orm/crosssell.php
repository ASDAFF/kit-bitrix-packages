<?
namespace Sotbit\Crosssell\Orm;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages ( __FILE__ );

class CrosssellTable extends \DataManagerCrosssell
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'sotbit_crosssell_options';
    }

    public static function getMap()
    {
        return array (
            new Entity\IntegerField ( 'ID', array (
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\TextField('SITES', array (
                'required' => true,
            )),
            new Entity\IntegerField ( 'SORT', array (
                'required' => true,
            )),
            new Entity\StringField('NAME'),
            new Entity\StringField('Active'),
            new Entity\StringField('SYMBOL_CODE'),
            new Entity\StringField('FOREIGN_CODE'),
            new Entity\TextField('FIRST_IMG'),
            new Entity\TextField('SECOND_IMG'),
            new Entity\TextField('FIRST_IMG_DESC'),
            new Entity\TextField('SECOND_IMG_DESC'),
            //new Entity\TextField('EXTRA_SETTINGS'),
            new Entity\TextField('RULE1'),
            new Entity\TextField('RULE2'),
            new Entity\TextField('RULE3'),
            new Entity\StringField('SORT_ORDER'),
            new Entity\StringField('SORT_BY'),
            new Entity\IntegerField ('NUMBER_PRODUCTS'),
            new Entity\DatetimeField ('DATE_CREATE'),
            new Entity\IntegerField ('CATEGORY_ID'),
            new Entity\StringField('TYPE_BLOCK'),
            new Entity\TextField('PRODUCTS'),
            new Entity\IntegerField('NUMBER_OF_MATCHED_PRODUCTS'),
        );
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
            ">=DATE_CREATE" => '',
            "<=DATE_CREATE" => ''
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

        $obCategory = new CrosssellCategoryTable;

        $categoryOrder = array_intersect_key($arOrder, CrosssellCategoryTable::getMap());

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
        if(isset($arFilter['TYPE_BLOCK'])) {
            if($arFilter['TYPE_BLOCK'] == Loc::loadMessages("TYPE_COLLECTION")) {
                $arElementFilter['TYPE_BLOCK'] = 'COLLECTION';
            } elseif($arFilter['TYPE_BLOCK'] == Loc::loadMessages("TYPE_CROSSSELL")) {
                $arElementFilter['TYPE_BLOCK'] = 'CROSSSELL';
            } else {
                $arElementFilter['TYPE_BLOCK'] = $arFilter['TYPE_BLOCK'];
            }
        }

        if(isset($arFilter['>=ID']))
            $arElementFilter['>=ID'] = $arFilter['>=ID'];
        if(isset($arFilter['<=ID']))
            $arElementFilter['<=ID'] = $arFilter['<=ID'];
        if(isset($arFilter['<ID']))
            $arElementFilter['<ID'] = $arFilter['<ID'];
        if(isset($arFilter['>ID']))
            $arElementFilter['>ID'] = $arFilter['>ID'];
        if(isset($arFilter['Active']))
            $arElementFilter['Active'] = $arFilter['Active'];
        if(isset($arFilter['NUMBER_PRODUCTS']))
            $arElementFilter['NUMBER_PRODUCTS'] = $arFilter['NUMBER_PRODUCTS'];
        if(isset($arFilter['SORT']))
            $arElementFilter['SORT'] = $arFilter['SORT'];
        if(isset($arFilter['FIND']))
            $arElementFilter['NAME'] =  "%" . $arFilter['FIND'] ."%" ;
        if(isset($arFilter['SITES']))
            $arElementFilter['SITES'] = '%\"' .strval($arFilter['SITES']) . '\"%'; ;
        if(isset($arFilter['CATEGORY_ID']))
            $arElementFilter['CATEGORY_ID'] = $arFilter['CATEGORY_ID'];
        else if(count($arFilter) < 2)
            $arElementFilter['CATEGORY_ID'] = false;

        if(!is_array($arSelectedFields))
            $arSelectedFields = array('*');

        $obElement = new CrosssellTable;
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
            $keys = array_keys(CrosssellCategoryTable::getMap());
        else
            $keys = array_keys(CrosssellTable::getMap());

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
}
?>