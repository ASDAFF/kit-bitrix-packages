<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Loader;
use \Bitrix\Iblock\Component\ElementList;
use Kit\Crosssell\FilterGenerator;

if (!\Bitrix\Main\Loader::includeModule('iblock'))
{
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}


class ComplexCollectionComponent extends CBitrixComponent
{

    private function getCollectionList($arParametersCollection)
    {

        $collections = array();
        $collectionNames = array();
        if($arParametersCollection)
        {
            foreach ($arParametersCollection as $id => $parameterId)
            {
                if (strpos($parameterId,'s') !== false)
                {
                    $catId = str_replace('s',"",$parameterId);
                    $db_collection = \Kit\Crosssell\Orm\CrosssellTable::getList(array(
                        'filter' =>
                            array(
                                'SITES' => '%"'.SITE_ID.'\"%',
                                'TYPE_BLOCK' => 'COLLECTION',
                                'Active' => 'Y',
                                'CATEGORY_ID' => $catId
                            ),
                        'select' =>
                            array(
                                'ID', 'NAME', 'CATEGORY_ID', 'SORT'
                            ),
                    ));
                    while ($item = $db_collection->fetch()){
                        $collectionNames[$item['ID']] = array('ID' => $item['ID'], 'NAME' => $item['NAME'], 'SORT' => $item['SORT']);
                        array_push($collections, $item['ID']);
                    }

                } elseif (strpos($parameterId,'e') !== false)
                {
                    $collectionId = str_replace('e',"",$parameterId);
                    if (!in_array($collectionId, $collections)){
                        array_push($collections, $collectionId);
                        $db_collection = \Kit\Crosssell\Orm\CrosssellTable::getList(array(
                            'filter' =>
                                array(
                                    'SITES' => '%"'.SITE_ID.'\"%',
                                    'TYPE_BLOCK' => 'COLLECTION',
                                    'Active' => 'Y',
                                    'ID' => $collectionId
                                ),
                            'select' =>
                                array(
                                    'ID', 'NAME', 'SORT'
                                ),
                        ));
                        while ($item = $db_collection->fetch()){
                            $collectionNames[$item['ID']] = array('ID' => $item['ID'], 'NAME' => $item['NAME'], 'SORT' => $item['SORT']);
                        }
                    }
                }
            }
        }else{
            $db_collection = \Kit\Crosssell\Orm\CrosssellTable::getList(array(
                'filter' =>
                    array(
                        'SITES' => '%"'.SITE_ID.'\"%',
                        'TYPE_BLOCK' => 'COLLECTION',
                        'Active' => 'Y',
                    ),
                'select' =>
                    array(
                        'ID', 'NAME', 'SORT'
                    ),
            ));
            while ($item = $db_collection->fetch())
            {
                $collectionNames[$item['ID']] = array('ID' => $item['ID'], 'NAME' => $item['NAME'], 'SORT' => $item['SORT']);
            }
        }

        $sorted = $this->array_msort($collectionNames, array('SORT'=>SORT_ASC));
        $collections = array();
        $collectionNames = array();
        foreach ($sorted as $collectionId => $collectionItem) {
            $collectionNames[$collectionId] = $collectionItem['NAME'];
            array_push($collections, $collectionId);
        }
        $result = array();
        $result['COLLECTION_IDS'] = $collections;
        $result['COLLECTION_NAMES'] = $collectionNames;
        return $result;

    }

    private function  array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\''.$col.'\'],'.$order.',';
        }
        $eval = substr($eval,0,-1).');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k,1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;

    }

    //delete empty collections
    private function deleteEmptyCollections($collections = false){
        $templateName = 'crosssell_news';
        if(IsModuleInstalled('kit.origami')) {
            $templateName = 'origami_crosssell_news';
        }

        CModule::IncludeModule("catalog");
        //get collection object
        $filterGenerator = new FilterGenerator();

        if ($collections && is_array($collections))
        {

            $i = 0;
            foreach ($collections['COLLECTION_IDS'] as $collectionId)
            {
                //get collection
                $colData = \Kit\Crosssell\Orm\CrosssellTable::getList(array('filter' => array(
                    'ID' => $collectionId,
                    'TYPE_BLOCK' => 'COLLECTION',
                    'SITES' => '%"'.SITE_ID.'\"%'
                )
                ))->fetch();

                //get filter for collection
                $filter = $filterGenerator->getFilter($colData['RULE1']);

                //check for count
                $item = CIBlockElement::GetList(
                    array(),
                    $filter,
                    false,
                    false,
                    array('ID', 'IBLOCK_ID')
                )->fetch();

                //delete if no elements to display
                if (
                    !$item ||
                    ( $this->getTemplateName() == $templateName && CCatalog::GetByID($item['IBLOCK_ID']) )
                )
                {
                    unset($collections['COLLECTION_NAMES'][$collections['COLLECTION_IDS'][$i]]);
                    unset($collections['COLLECTION_IDS'][$i]);
                }
                $i++;
            }
        }
        return $collections;
    }

    public function executeComponent()
    {
        if(!Loader::includeModule('kit.crosssell')) {
            return;
        }

        global $USER;

        $cache_id = serialize(array($this->arParams, ($this->arParams['CACHE_GROUPS']==='N'? false: $USER->GetGroups())));
        $obCache = new CPHPCache;
        if ($obCache->InitCache($this->arParams['CACHE_TIME'], $cache_id, 'kit.crosssell'))
        {
            $vars = $obCache->GetVars();
            $this->arResult = $vars['arResult'];
            $this->arParams['FROM_COMPLEX'] = $this->arResult['FROM_COMPLEX'];
        }
        elseif ($obCache->StartDataCache())
        {
            $collections = $this->getCollectionList($this->arParams['COLLECTION_LIST']);
            $collections = $this->deleteEmptyCollections($collections);
            $this->arResult['COLLECTION_LIST'] = $collections['COLLECTION_IDS'];
            if (is_integer($this->arParams['PRODUCT_PER_TAB']) && ($this->arParams['PRODUCT_PER_TAB']>0)){
                $this->arResult['NUMBER_OF_PRODUCTS'] = $this->arParams['PRODUCT_PER_TAB'];
            } else {
                $this->arResult['NUMBER_OF_PRODUCTS'] = $collections['NUMBER_OF_PRODUCTS'];
            }
            $this->arResult['FROM_COMPLEX'] = $this->arParams['FROM_COMPLEX'] = true;
            if(IsModuleInstalled('kit.origami')) {
                $this->arResult['PRICE_CODE'] = \KitOrigami::GetComponentPrices(["OPT","SMALL_OPT","BASE"]);
            }
            $this->arResult['COMPONENT_PATH'] = $this->GetPath();
            $this->arResult['COLLECTION_LIST_NAMES'] = $collections['COLLECTION_NAMES'];
            $this->arResult['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];

            $obCache->EndDataCache(array(
                'arResult' => $this->arResult,
            ));
        }
        $this->includeComponentTemplate();

        return $this->arResult;
    }
}

?>
