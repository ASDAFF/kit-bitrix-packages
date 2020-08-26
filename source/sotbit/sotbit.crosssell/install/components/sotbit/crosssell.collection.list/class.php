<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Loader;
use \Bitrix\Iblock\Component\ElementList;

if (!\Bitrix\Main\Loader::includeModule('iblock'))
{
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}

class CollectionComponent extends CBitrixComponent
{
    private $offerBlockId = 0;

    private function getOfferBlockId(){
        CModule::IncludeModule("catalog");
        $iBlockId = 0;
        $mxResult = CCatalogSKU::GetInfoByProductIBlock($this->arParams['IBLOCK_ID']);
        if(is_array($mxResult)){
            $iBlockId = $mxResult['IBLOCK_ID'];
        }
        return $iBlockId;
    }

    private function getCollection() {
        if(!Loader::includeModule('sotbit.crosssell')) {
            return;
        }

        $COLLECTION_ID = $this->arParams['COLLECTION_ID'];

        if($COLLECTION_ID == '') {
            return false;
        }

        return \Sotbit\Crosssell\Orm\CrosssellTable::getList(array('filter' => array(
            'ID' => $COLLECTION_ID,
            'TYPE_BLOCK' => 'COLLECTION',
            'SITES' => '%"'.SITE_ID.'\"%')
        ))->fetch();
    }

    public function getFilter($rule) {
        CModule::IncludeModule("catalog");
        $conditions = unserialize($rule);

        //$iterationPriceNumber - количество условий для фильтрации по ценам - количество проходов для формирования фильтров
        $iterationPrice = 0;
        $iterationPriceNumber = 0;
        foreach ($conditions['CHILDREN'] as $index => $child){
            if (strpos($child['CLASS_ID'], 'CondIBPrice') !== false)
            {
                $iterationPriceNumber++;
            }
        }

        //переменная для глобальных условий фильтрации (по группе параметров)
        if ($conditions["DATA"]['True'] == 'True')
        {
            $globalTrue = true;
        } else {
            $globalTrue = false;
        }

        if(CModule::IncludeModule("iblock"))
        {
            $res = CIBlockElement::GetByID(
                $this->arParams['PRODUCT_ID']
            );
            if($ob = $res->GetNext()) $sectionId = $ob['IBLOCK_SECTION_ID'];
        }

        $arrFilter = array(
            array(
                "LOGIC"=>$conditions["DATA"]['All'],
            )
        );

        foreach ($conditions['CHILDREN'] as $index => $child) {

            //Проверка условия для параметра
            if($child['DATA']['logic']) $isEqual = $child['DATA']['logic'];
            if ($globalTrue){
                if ($isEqual=='Not') {
                    $isEqual = '!';
                } else {
                    $isEqual = '';
                }
            } else {
                if ($isEqual=='Not') {
                    $isEqual = '';
                } else {
                    $isEqual = '!';
                }
            }

            if($child['CLASS_ID'] == 'CondIBSection') {
                array_push($arrFilter[0], array(
                    $isEqual."SECTION_ID" => ($child['DATA']['value'] != '') ? $child['DATA']['value'] : $sectionId,
                    "INCLUDE_SUBSECTIONS" => "Y"
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBIBlock') {
                array_push($arrFilter[0], array(
                    "IBLOCK_ID" => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBXmlID') {

                array_push($arrFilter[0], array(
                    $isEqual."XML_ID" => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBName') {
                array_push($arrFilter[0], array(
                    $isEqual."NAME" => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBElement') {
                array_push($arrFilter[0], array(
                    $isEqual.'ID' => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBCode') {
                array_push($arrFilter[0], array(
                    $isEqual."CODE" => $child['DATA']['value']
                ));
            }
            elseif (strpos($child['CLASS_ID'], 'CondIBPrice') !== false)
            {
                $priceId  = str_replace('CondIBPrice', '', $child['CLASS_ID']);

                ($child['DATA']['logic']!='') ? $logic = $child['DATA']['logic'] : $logic = '';
                $logic = $this->convertLogic($logic, $globalTrue);

                if ($iterationPrice == 0) {
                    $priceCondition = array(
                        "LOGIC" => $conditions['DATA']['All'],
                        array("ACTIVE" => 'Y'),
                        array($logic . "CATALOG_PRICE_" . $priceId => $child['DATA']['value']),
                    );
                    $arFilterPrice = array(
                        "IBLOCK_ID" => $this->offerBlockId,
                        "INCLUDE_SUBSECTIONS" => "Y",
                        $priceCondition,
                    );
                } else {
                    array_push($priceCondition, array($logic . "CATALOG_PRICE_" . $priceId => $child['DATA']['value']));
                    $arFilterPrice = array(
                        "IBLOCK_ID" => $this->offerBlockId,
                        "INCLUDE_SUBSECTIONS" => "Y",
                        $priceCondition,
                    );
                }

                //Если последняя итерация по цене
                if ($iterationPrice == ($iterationPriceNumber-1)){
                    $offerList = CIBlockElement::GetList(
                        array("SORT" => "ASC"),
                        $arFilterPrice,
                        false,
                        array(),
                        array()
                    );

                    $offers = array();
                    $i = 0;
                    while($res = $offerList->GetNext()){
                        $offers[$i]['ID'] = $res['ID'];
                        $i++;
                    }

                    if (!isset($productIds))
                        $productIds = array();

                    foreach ($offers as $offer)
                    {
                        $productId = CCatalogSku::GetProductInfo($offer['ID'],$this->offerBlockId);
                        array_push($productIds, $productId['ID']);
                    }

                    if (!is_array($arrFilter[0][0][$isEqual.'ID'])){
                        $arrFilter[0][0][$isEqual.'ID'] = array();
                    }
                    $arrFilter[0][0][$isEqual.'ID'] = array_merge($arrFilter[0][0][$isEqual.'ID'], $productIds);

                    $arrFilter[0][0][$isEqual.'ID'] = array_unique($arrFilter[0][0][$isEqual.'ID'], SORT_NUMERIC);
                }

                ($child['DATA']['logic']!='') ? $logic = $child['DATA']['logic'] : $logic = '';
                $logic = $this->convertLogic($logic, $globalTrue);
                $propertyId = array($logic . "CATALOG_PRICE_" . $priceId=>$child['DATA']['value']);
                array_push($arrFilter[0], $propertyId);
                $iterationPrice++;
            }
            elseif (strpos($child['CLASS_ID'], 'CondIBProp') !== false) {
                $pieces = explode(":", $child['CLASS_ID']);
                $idProperty =  $pieces[2];
                $blockId = $pieces[1];
                $isOffer = CCatalogSKU::GetInfoByOfferIBlock($blockId);
                $currentPropertyValue = $child['DATA']['value'];
                $db_props = CIBlockElement::GetProperty($blockId, $this->arParams['PRODUCT_ID'], array("sort" => "asc"), array('ID' => $idProperty));
                $elementPropertyValue = array();
                while($ar_props = $db_props->GetNext()){
                    array_push($elementPropertyValue, $ar_props["VALUE"]);
                }

                $arSelect = Array("PROPERTY_" . $idProperty);
                $arFilter = Array("ID"=>$this->arParams['ELEMENT_ID']);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                $ob = $res->fetch();

                if ($currentPropertyValue == '') {
                    $currentPropertyValue = $elementPropertyValue;
                }


//                $propertyId = array($isEqual."PROPERTY_" . $idProperty => ($child['DATA']['value'] != '') ? $child['DATA']['value'] : $ob[$isEqual.'=PROPERTY_' . $idProperty . '_VALUE']);
                $propertyId = array($isEqual."PROPERTY_" . $idProperty => $currentPropertyValue);

                $res1 = CIBlockProperty::GetByID(
                    $idProperty,
                    false,
                    false
                );
                if($ar_res = $res1->GetNext()){
                    $propertyCode = $ar_res['CODE'];
                    $propertyUserType = $ar_res['USER_TYPE'];
                    $propertyType = $ar_res['PROPERTY_TYPE'];
                }

                if ($isOffer){
                    $propertyName = array();
                    $tblName = $ar_res['USER_TYPE_SETTINGS']['TABLE_NAME'];
                    $highloadItemID = $child['DATA']['value'];

                    //Если фильтр должен быть по элементу HighLoad блока
                    if ($propertyUserType == 'directory' && $propertyType == 'S')
                    {
                        $ufXmlId = $this->getUFXMLID($highloadItemID,$tblName);
                        $arrFilter = $this->pushIdOffer($propertyCode, $ufXmlId, $arrFilter, $isEqual, $blockId);
                    } else {
                        $arrFilter = $this->pushIdOffer($propertyCode, $child['DATA']['value'], $arrFilter, $isEqual, $blockId);
                    }
                } else {
                    array_push($arrFilter[0], $propertyId);
                }
            }
            elseif ($child['CLASS_ID'] == 'CondGroup') {
                $isTrue = ($child['DATA']['True'] == 'True') ? true : false;
                $childrenOfChild = $this->getChilden($child['CHILDREN'], $isTrue, $ob);
                $mergedAr = array_merge(Array(
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "LOGIC"=>$child['DATA']['All'],
                ), $childrenOfChild);
                array_push($arrFilter[0], $mergedAr);
            }
        }
        return $arrFilter;
    }

    //Преобразует коды логики фильтрации в символы
    private function convertLogic($logic, $globalTrue){

        switch ($logic) {
            case "Great":
                $logic = '>';
                break;
            case "Less":
                $logic = '<';
                break;
            case "EqGr":
                $logic = '>=';
                break;
            case "EqLs":
                $logic = '<=';
                break;
            case "Equal":
                $logic = '=';
                break;
            case "Not":
                $logic = '!';
                break;
            default:
                $logic = '';
        }

        if (!$globalTrue){
            $logic = '!' . $logic;
        }
        return $logic;
    }


    //добавляет в массив фильтра id по критерию товарного предложения и возвращает этот массив
    private function pushIdOffer($propertyCode, $propertyValue, $arrFilter, $isEqual,  $blockId){

        $arFilter = array('ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
            'IBLOCK_ID' => $blockId,
            'PROPERTY_'.$propertyCode => $propertyValue,
        )));
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), array("ID"));

        if (!is_array($arrFilter[0][0][$isEqual.'ID'])){
            $arrFilter[0][0][$isEqual.'ID'] = array();
        }
        while($ar = $res -> fetch()){
            array_push($arrFilter[0][0][$isEqual.'ID'], $ar['ID']);
        }
        return $arrFilter;
    }

    //Получение UF_XML_ID по ID параметра - для Highload блоков
    private function getUFXMLID($highloadItemID, $tblName)
    {
        \Bitrix\Main\Loader::IncludeModule("highloadblock");
        $select = ['UF_XML_ID'];
        $filter = ['ID'=>$highloadItemID];
        $limit = 1;
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(
            array("filter" => array('TABLE_NAME' => $tblName))
        )->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $Query = new \Bitrix\Main\Entity\Query($entity);
        $Query->setSelect($select);
        $Query->setFilter($filter);
        $Query->setOrder([]);
        $Query->setLimit($limit);
        $result = $Query->exec();
        $result = $result->fetch();
        $ufXmlId = $result['UF_XML_ID'];
        return $ufXmlId;
    }


    public function getChilden($children, $IsTrue,  $ob=array()) {
        CModule::IncludeModule("catalog");
        $childrenArr = array();
        //$iterationPriceNumber - количество условий для фильтрации по ценам - количество проходов для формирования фильтров
        $iterationPrice = 0;
        $iterationPriceNumber = 0;
        foreach ($children as $child){
            if (strpos($child['CLASS_ID'], 'CondIBPrice') !== false)
            {
                $iterationPriceNumber++;
            }
        }
        foreach ($children as $child) {
           //Проверка условия для параметра
            if($child['DATA']['logic']) {
                $isEqual = $child['DATA']['logic'];
            } else {
                $isEqual = '';
            }
            if ($IsTrue){
                ($isEqual=='Not') ? ($isEqual = '!') : ($isEqual = '');
            } else {
                ($isEqual=='Not') ? ($isEqual = '') : ($isEqual = '!');
            }

            if($child['CLASS_ID'] == 'CondIBSection') {
                array_push($childrenArr, array(
                    $isEqual."SECTION_ID" => ($child['DATA']['value'] != '') ? $child['DATA']['value'] : $ob['IBLOCK_SECTION_ID'],
                    "INCLUDE_SUBSECTIONS" => "Y",
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBIBlock') {
                array_push($childrenArr, array(
                    "IBLOCK_ID" => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBXmlID') {
                array_push($childrenArr, array(
                    $isEqual."XML_ID" => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBName') {
                array_push($childrenArr, array(
                    $isEqual."NAME" => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBElement') {
                array_push($childrenArr, array(
                    $isEqual."ID" => $child['DATA']['value']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondIBCode') {
                array_push($childrenArr, array(
                    $isEqual."CODE" => $child['DATA']['value']
                ));
            }
            elseif (strpos($child['CLASS_ID'], 'CondIBPrice') !== false)
            {
                $priceId  = str_replace('CondIBPrice', '', $child['CLASS_ID']);

                ($child['DATA']['logic']!='') ? $logic = $child['DATA']['logic'] : $logic = '';
                $logic = $this->convertLogic($logic, $IsTrue);

                if ($iterationPrice == 0) {
                    $priceCondition = array(
                        "LOGIC" => $child['DATA']['logic'],
                        array("ACTIVE" => 'Y'),
                        array($logic . "CATALOG_PRICE_" . $priceId => $child['DATA']['value']),
                    );
                    $arFilterPrice = array(
                        "IBLOCK_ID" => $this->offerBlockId,
                        "INCLUDE_SUBSECTIONS" => "Y",
                        $priceCondition,
                    );
                } else {
                    array_push($priceCondition, array($logic . "CATALOG_PRICE_" . $priceId => $child['DATA']['value']));
                    $arFilterPrice = array(
                        "IBLOCK_ID" => $this->offerBlockId,
                        "INCLUDE_SUBSECTIONS" => "Y",
                        $priceCondition,
                    );
                }

                //Если последняя итерация по цене
                if ($iterationPrice == ($iterationPriceNumber-1)){
                    $offerList = CIBlockElement::GetList(
                        array("SORT" => "ASC"),
                        $arFilterPrice,
                        false,
                        array(),
                        array()
                    );

                    $offers = array();
                    $i = 0;
                    while($res = $offerList->GetNext()){
                        $offers[$i]['ID'] = $res['ID'];
                        $i++;
                    }

                    if (!isset($productIds))
                        $productIds = array();

                    foreach ($offers as $offer)
                    {
                        $productId = CCatalogSku::GetProductInfo($offer['ID'],$this->offerBlockId);
                        array_push($productIds, $productId['ID']);
                    }

                    if (!is_array($childrenArr[$isEqual.'ID'])){
                        $childrenArr[$isEqual.'ID'] = array();
                    }
                    $childrenArr[$isEqual.'ID'] = array_merge($childrenArr[$isEqual.'ID'], $productIds);
                    $childrenArr[$isEqual.'ID'] = array_unique($childrenArr[$isEqual.'ID'], SORT_NUMERIC);
                }

                $logic = $child['DATA']['logic'];
                $logic = $this->convertLogic($logic, $IsTrue);
                $propertyId = array($logic . "CATALOG_PRICE_" . $priceId=>$child['DATA']['value']);
                array_push($childrenArr, $propertyId);
                $iterationPrice++;
            }
            elseif (strpos($child['CLASS_ID'], 'CondIBProp') !== false) {
                $pieces = explode(":", $child['CLASS_ID']);
                $idProperty = $pieces[2];

                $arSelect = Array("PROPERTY_" . $idProperty);
                $arFilter = Array("ID"=>$this->arParams['ELEMENT_ID']);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                $ob = $res->fetch();

                array_push($childrenArr, array(
                    $isEqual."PROPERTY_" . $idProperty => ($child['DATA']['value'] != '') ? $child['DATA']['value'] : $ob['PROPERTY_' . $idProperty . '_VALUE']
                ));
            }
            elseif ($child['CLASS_ID'] == 'CondGroup') {
                $isTrue = ($child['DATA']['True'] == 'True') ? true : false;
                $childrenOfChild = $this->getChilden($child['CHILDREN'], $isTrue);
                $mergedAr = array_merge(Array(
                    "LOGIC"=>$child['DATA']['All'],
                ), $childrenOfChild);
                array_push($childrenArr, $mergedAr);
            }
        }

        return $childrenArr;
    }

    private function getProducts($filter) {
        $products = array();
        Loader::includeModule('iblock');
        $queryGetElement = CIBlockElement::GetList(
            array(),
            $filter,
            false,
            false,
            array()
        );
        while ($product = $queryGetElement->fetch()) {
            array_push($products, array(
                "ID" => $product['ID'],
                "IBLOCK_ID" => $product['IBLOCK_ID']
            ));
        }
        return $products;
    }

    private function checkFilter($arrFilter)
    {
        $arrFilter = $arResult['COND'];
        $cnt = CIBlockElement::GetList(
            array(),
            $arrFilter,
            array(),
            false,
            array('ID')
        );
        return $cnt;
    }


    public function executeComponent()
    {
        $templateName = 'crosssell_news';
        if(IsModuleInstalled('sotbit.origami')) {
            $templateName = 'origami_crosssell_news';
        }

        CModule::IncludeModule("catalog");
        if(
            !Loader::includeModule('sotbit.crosssell') ||
            ( CCatalog::GetByID($this->arParams['IBLOCK_ID']) && $this->getTemplateName() == $templateName )
        ) {
            return;
        }

        global $USER;

        $cache_id = serialize(array($this->arParams, ($this->arParams['CACHE_GROUPS']==='N'? false: $USER->GetGroups())));
        $obCache = new CPHPCache;

        if ($obCache->InitCache($this->arParams['CACHE_TIME'], $cache_id, 'sotbit.crosssell'))
        {
            $vars = $obCache->GetVars();
            $this->arResult = $vars['arResult'];
        }
        elseif ($obCache->StartDataCache())
        {
            $colData = $this->getCollection();
            if($colData != false)
            {
                $this->offerBlockId = $this->getOfferBlockId();
                $this->arResult['COND'] = $this->getFilter($colData['RULE1']);
                $this->arResult['COND_NAME'] = $colData['NAME'];
                $productPerTab = intval($this->arParams['PAGE_ELEMENT_COUNT']);
                if (is_integer($productPerTab) && ($productPerTab > 0)){
                    $this->arResult['NUMBER_OF_PRODUCTS'] = $productPerTab;
                } else {
                    $this->arResult['NUMBER_OF_PRODUCTS'] = $colData['NUMBER_PRODUCTS'];
                }
                $this->arResult['SECTION_TEMPLATE'] = $this->arParams['SECTION_TEMPLATE'];
                $this->arResult['SORT_BY'] = $this->arParams['SECTION_TEMPLATE'] ? $this->arParams['ELEMENT_SORT_FIELD'] : $colData['SORT_BY'];
                $this->arResult['SORT_ORDER'] = $this->arParams['SECTION_TEMPLATE'] ? $this->arParams['ELEMENT_SORT_ORDER'] : $colData['SORT_ORDER'];
                $this->arResult['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
                if ($this->checkFilter($this->arResult['COND']) > 0){
                    $this->arResult['SAFE'] = true;
                } else {
                    $this->arResult['SAFE'] = false;
                }
            } else {
                $this->arResult['SAFE'] = false;
            }

            $obCache->EndDataCache(array(
                'arResult' => $this->arResult,
            ));

        }
        $this->includeComponentTemplate();
    }
}

?>
