<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use \Bitrix\Iblock\Component\ElementList;


class CrosssellComponent extends CBitrixComponent
{

    private $offerBlockId = 0;
    private static $subQueryProps = array();

    private function getOfferBlockId(){
        CModule::IncludeModule("catalog");
        $iBlockId = 0;
        $mxResult = CCatalogSKU::GetInfoByProductIBlock($this->arParams['IBLOCK_ID']);
        if(is_array($mxResult)){
            $iBlockId = $mxResult['IBLOCK_ID'];
        }
        return $iBlockId;
    }

    public function getFilter($rule) {
        Loader::includeModule('iblock');
        CModule::IncludeModule("catalog");

        $conditions = $rule;

        if (is_array($conditions['CHILDREN']) && (count($conditions['CHILDREN']) > 0)){
            $arrFilter = array(
                "INCLUDE_SUBSECTIONS" => "Y",
                array(
                    "LOGIC"=>$conditions["DATA"]['All'],
                )
            );
            //����� ������� �������� �� ����
            $iterationPrice = 0;
            //���������� ������� ��� ���������� �� ����� - ���������� �������� ��� ������������ ��������
            $iterationPriceNumber = 0;
            foreach ($conditions['CHILDREN'] as $index => $child){
                if (strpos($child['CLASS_ID'], 'CondIBPrice') !== false)
                {
                    $iterationPriceNumber++;
                }
            }
        } elseif (is_array($conditions['RULE3']) && (count($conditions['RULE3']) > 0)) {
            $arrFilter = array(
                "INCLUDE_SUBSECTIONS" => "Y",
                array(
                    "LOGIC"=>$conditions["DATA"]['All'],
                )
            );
            array_push($arrFilter[0], array("ID" => 0));
        }

        //���������� ��� ���������� ������� ���������� (�� ������ ����������)
        if ($conditions["DATA"]['True'] == 'True')
        {
            $globalTrue = true;
        } else {
            $globalTrue = false;
        }


        if(CModule::IncludeModule("iblock"))
        {
            $res = CIBlockElement::GetList(
                array(),
                array('ID' => $this->arParams['PRODUCT_ID'])
            );
            if($ob = $res->GetNext()) $sectionId = $ob['IBLOCK_SECTION_ID'];
        }

        foreach ($conditions['CHILDREN'] as $index => $child) {

            //�������� ������� ��� ���������
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

                //���� ��������� �������� �� ����
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
                        $this->insertIdToFilter($arrFilter, $productId['ID'], $isEqual);
                    }
                }

                $logic = $this->convertLogic($child['DATA']['logic'], $globalTrue);
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
//                $arFilter = Array("ID"=>$this->arParams['ELEMENT_ID']);
                $arFilter = Array("ID"=>$this->arParams['PRODUCT_ID']);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                $ob = $res->fetch();

                if ($currentPropertyValue == '') {
                    $currentPropertyValue = $elementPropertyValue;
                }

                $propertyId = array($isEqual."PROPERTY_" . $idProperty => $currentPropertyValue);

//                $res1 = CIBlockProperty::GetByID($idProperty, false, false);
                $res1 = CIBlockProperty::GetList(
                    array(),
                    array('ID' => $idProperty)
                );
                if($ar_res = $res1->GetNext()){
                    $propertyCode = $ar_res['CODE'];
                    $propertyUserType = $ar_res['USER_TYPE'];
                    $propertyType = $ar_res['PROPERTY_TYPE'];
                }

                if ($isOffer){
                    $tblName = $ar_res['USER_TYPE_SETTINGS']['TABLE_NAME'];
                    $highloadItemID = $child['DATA']['value'];

                    if($highloadItemID == ''){
                        //�������� �������� ����������� ��� �������� ������
                        $res = CCatalogSKU::getOffersList(
                            $this->arParams['PRODUCT_ID'], // ������ ID �������
                            '', // ���������� ID ��������� ������ � ��� ������, ����� ���� ������ ������� �� ������ ��������� � �� ��������
                            $skuFilter = array(), // �������������� ������ �����������. �� ��������� ����.
                            $fields = array(),  // ������ ����� �����������. ���� ���� ���� - ������ ID � IBLOCK_ID
                            $propertyFilter = array('ID' => array($idProperty))
                        );
                        $currentProperties = array();
                        if (is_array($res[$this->arParams['PRODUCT_ID']])){
                            foreach ($res[$this->arParams['PRODUCT_ID']] as $sale) {
                                foreach ($sale['PROPERTIES'] as $property) {
                                    if ($property['VALUE'] != '')
                                        array_push($currentProperties, $property['VALUE']);
                                }
                            }
                        }

                    }

                    //���� ������ ������ ���� �� �������� HighLoad �����
                    if ($propertyUserType == 'directory' && $propertyType == 'S')
                    {
                        if ($highloadItemID == ''){
                            if (is_array($currentProperties)){
//                                $ids = array();
                                foreach ($currentProperties as $currentProperty) {
//                                    $arrFilter[0][$isEqual.'ID'] = array();

                                    $arrFilter[0][$isEqual .'ID'] = $this->addSubQuery($propertyCode, $currentProperty, $isEqual, $blockId);
//                                    $arrFilter = $this->pushIdOffer($propertyCode, $currentProperty, $arrFilter, $isEqual, $blockId);
//                                    array_push($ids, $arrFilter[0][$isEqual.'ID']);
                                }
                            }
//                            if (count($ids)>1){
//                                $result = $ids[0];
//                                for ($i = 0; $i < count($ids); $i++){
//                                    $result = array_intersect($result, $ids[$i]);
//                                }
//                                $arrFilter[0][$isEqual.'ID'] = $result;
//                            }
//                            else {
//                                foreach ($ids as $id) {
//                                    $arrFilter[0][$isEqual.'ID'] = $id;
//                                }
//                            }

                        }
                        else {
                            $ufXmlId = $this->getUFXMLID($highloadItemID,$tblName);
//                            $arrFilter = $this->pushIdOffer($propertyCode, $ufXmlId, $arrFilter, $isEqual, $blockId);
                            if(isset($arrFilter[0])) {
                                $arrFilter[0]['ID'] = $this->addSubQuery($propertyCode, $ufXmlId, $isEqual, $blockId);
                            } else {
                                $arrFilter['ID'] = $this->addSubQuery($propertyCode, $ufXmlId, $isEqual, $blockId);
                            }
                        }

                    } else {

//                        $arrFilter = $this->pushIdOffer($propertyCode, $child['DATA']['value'], $arrFilter, $isEqual, $blockId);
                        if(isset($arrFilter[0])) {
                            $arrFilter[0]['ID'] = $this->addSubQuery($propertyCode, $child['DATA']['value'], $isEqual, $blockId);
                        } else {
                            $arrFilter['ID'] = $this->addSubQuery($propertyCode, $child['DATA']['value'], $isEqual, $blockId);
                        }

                    }
                } else {
                    array_push($arrFilter[0], $propertyId);
                }
            }
            elseif ($child['CLASS_ID'] == 'CondGroup') {
                $isTrue = ($child['DATA']['True'] == 'True') ? true : false;
                $childrenOfChild = $this->getChilden($child['CHILDREN'], $isTrue, $ob);

                if(is_object($childrenOfChild)) {
                    $data = array('ID' => $childrenOfChild);
                } else {
                    $data = $childrenOfChild;
                }

                $mergedAr = array_merge(Array(
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "LOGIC"=>$child['DATA']['All'],
                ), $data);
                array_push($arrFilter[0], $mergedAr);
            }
        }

        if(is_array($conditions['RULE3']))
        {
            $arrFilter = array("INCLUDE_SUBSECTIONS" => "Y",
                array(
                    "LOGIC" => "OR",
                    $arrFilter[0]
                ));
            foreach ($conditions['RULE3'] as $conditionId) {
                $parts = explode(":",$conditionId);
                $conditionId = $parts[0];
                $res = CIBlockElement::GetProperty($parts[1], $this->arParams['PRODUCT_ID'], "sort", "asc", array("ID" => $conditionId));
                while ($ob = $res->GetNext())
                {
                    if ($ob['VALUE'])
                        $valuesArray[] = $ob['VALUE'];
                }
            }
            if (is_array($valuesArray) && (count($valuesArray) > 0)){
                array_push($arrFilter[0], array("ID" => $valuesArray));
            }
        }

        return $arrFilter;
    }

    private function insertIdToFilter(&$filter, $idToInsert, $prefix){
        if ($idToInsert){
            $itemPushed = false;
            foreach ($filter[0] as $mainKey => $filterElement) {
                if (is_array($filterElement) && !$itemPushed){
                    foreach ($filterElement as $idKey => $value) {
                        if ($idKey == $prefix . 'ID'){
                            array_push($filter[0][$mainKey][$idKey], $idToInsert);
                            $itemPushed = true;
                        }
                    }
                }
            }
            if (!$itemPushed){
                array_push($filter[0], array($prefix . 'ID' => array($idToInsert)));
            }
        }
    }


    //����������� ���� ������ ���������� � �������
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

    private function subQueryArr($isEqual, $propertyCode, $propertyValue) {
        if(!isset($this->subQueryProps[$isEqual .'PROPERTY_'. $propertyCode])) {
            $this->subQueryProps[$isEqual .'PROPERTY_'. $propertyCode] = array($propertyValue);
        } else if(
            is_array($this->subQueryProps[$isEqual .'PROPERTY_'. $propertyCode]) &&
            array_search($propertyValue, $this->subQueryProps[$isEqual .'PROPERTY_'. $propertyCode]) === false
        ) {
            $this->subQueryProps[$isEqual .'PROPERTY_'. $propertyCode] = array_merge($this->subQueryProps[$isEqual .'PROPERTY_'. $propertyCode], array($propertyValue));
        }

        return $this->subQueryProps;
    }

    private function addSubQuery($propertyCode, $currentProperty, $isEqual, $blockId) {
        return $result['ID'] = CIBlockElement::SubQuery(
            'PROPERTY_CML2_LINK',
            array(
                'IBLOCK_ID' => $blockId,
                $this->subQueryArr($isEqual, $propertyCode, $currentProperty)
            )
        );
    }

    //��������� � ������ ������� id �� �������� ��������� ����������� � ���������� ���� ������
    private function pushIdOffer($propertyCode, $propertyValue, $arrFilter, $isEqual,  $blockId){

        $arFilter = array('ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
            'IBLOCK_ID' => $blockId,
            'PROPERTY_'.$propertyCode => $propertyValue,
        )));
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), array("ID"));

        while($ar = $res -> fetch()){
            $idToInsert[] = $ar['ID'];
        }
        if(is_array($idToInsert) && (count($idToInsert) > 0)){
            if(isset($arrFilter[0])) {
                if(isset($arrFilter[0][$isEqual . 'ID']) && is_array($arrFilter[0][$isEqual . 'ID'])) {
                    $arrTmp = array_merge($arrFilter[0][$isEqual . 'ID'], array_diff ($idToInsert, $arrFilter[0][$isEqual . 'ID']));
                } else {
                    $arrTmp = $idToInsert;
                }
//                array_push($arrFilter[0], array($isEqual . 'ID' => $idToInsert));
                $arrFilter[0][$isEqual . 'ID'] = $arrTmp;
            } else {
                if(isset($arrFilter[$isEqual . 'ID']) && is_array($arrFilter[$isEqual . 'ID'])) {
                    $arrTmp = array_merge($arrFilter[$isEqual . 'ID'], array_diff ($idToInsert, $arrFilter[$isEqual . 'ID']));
                } else {
                    $arrTmp = $idToInsert;
                }
//                $arrFilter = array_merge($arrFilter, array($isEqual . 'ID' => $idToInsert));
                $arrFilter[$isEqual . 'ID'] = $arrTmp;
            }
        }
        return $arrFilter;
    }

    //��������� UF_XML_ID �� ID ��������� - ��� Highload ������
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



    public function getChilden($children, $IsTrue, $ob=array()) {
        CModule::IncludeModule("catalog");
        $iterationPrice = 0;

        //���������� ������� ��� ���������� �� ����� - ���������� �������� ��� ������������ ��������
        $iterationPriceNumber = 0;
        foreach ($children as $child){
            if (strpos($child['CLASS_ID'], 'CondIBPrice') !== false)
            {
                $iterationPriceNumber++;
            }
        }
        Loader::includeModule('iblock');
        $childrenArr = array();
        foreach ($children as $child) {
            //�������� ������� ��� ���������
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

                //���� ��������� �������� �� ����
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

                $blockId = $pieces[1];
                $isOffer = CCatalogSKU::GetInfoByOfferIBlock($blockId);

                $arSelect = Array("PROPERTY_" . $idProperty);
//                $arFilter = Array("ID" => $this->arParams['ELEMENT_ID']);
                $arFilter = Array("ID" => $this->arParams['PRODUCT_ID']);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                $ob = $res->fetch();


//                if ($currentPropertyValue == '') {
//                    $currentPropertyValue = $elementPropertyValue;
//                }

//                $propertyId = array($isEqual."PROPERTY_" . $idProperty => $currentPropertyValue);

//                $res1 = CIBlockProperty::GetByID($idProperty, false, false);
                $res1 = CIBlockProperty::GetList(
                    array(),
                    array('ID' => $idProperty)
                );
                if ($ar_res = $res1->GetNext()) {
                    $propertyCode = $ar_res['CODE'];
                    $propertyUserType = $ar_res['USER_TYPE'];
                    $propertyType = $ar_res['PROPERTY_TYPE'];
                }

                if ($isOffer) {
                    $tblName = $ar_res['USER_TYPE_SETTINGS']['TABLE_NAME'];
                    $highloadItemID = $child['DATA']['value'];

                    if ($highloadItemID == '') {
                        //�������� �������� ����������� ��� �������� ������
                        $res = CCatalogSKU::getOffersList(
                            $this->arParams['PRODUCT_ID'], // ������ ID �������
                            '', // ���������� ID ��������� ������ � ��� ������, ����� ���� ������ ������� �� ������ ��������� � �� ��������
                            $skuFilter = array(), // �������������� ������ �����������. �� ��������� ����.
                            $fields = array(),  // ������ ����� �����������. ���� ���� ���� - ������ ID � IBLOCK_ID
                            $propertyFilter = array('ID' => array($idProperty))
                        );
                        $currentProperties = array();
                        if (is_array($res[$this->arParams['PRODUCT_ID']])) {
                            foreach ($res[$this->arParams['PRODUCT_ID']] as $sale) {
                                foreach ($sale['PROPERTIES'] as $property) {
                                    if ($property['VALUE'] != '')
                                        array_push($currentProperties, $property['VALUE']);
                                }
                            }
                        }

                    }

                    //���� ������ ������ ���� �� �������� HighLoad �����
                    if ($propertyUserType == 'directory' && $propertyType == 'S') {
                        if ($highloadItemID == '') {
                            if (is_array($currentProperties)) {
//                                $ids = array();
                                foreach ($currentProperties as $currentProperty) {
//                                    $childrenArr[0][$isEqual . 'ID'] = array();
//                                    $childrenArr = $this->pushIdOffer($propertyCode, $currentProperty, $childrenArr, $isEqual, $blockId);
                                    $childrenArr = $this->addSubQuery($propertyCode, $currentProperty, $isEqual, $blockId);
//                                    array_push($ids, $childrenArr[0][$isEqual . 'ID']);
                                }
                            }
//                            if (count($ids) > 1) {
//                                $result = $ids[0];
//                                for ($i = 0; $i < count($ids); $i++) {
//                                    $result = array_intersect($result, $ids[$i]);
//                                }
//                                $childrenArr[0][$isEqual . 'ID'] = $result;
//                            } else {
//                                foreach ($ids as $id) {
//                                    $childrenArr[0][$isEqual . 'ID'] = $id;
//                                }
//                            }

                        } else {
                            $ufXmlId = $this->getUFXMLID($highloadItemID, $tblName);
//                            $childrenArr = $this->pushIdOffer($propertyCode, $ufXmlId, $childrenArr, $isEqual, $blockId);
                            $childrenArr['ID'] = $this->addSubQuery($propertyCode, $ufXmlId, $isEqual, $blockId);
                        }

                    } else {

//                        $childrenArr = $this->pushIdOffer($propertyCode, $child['DATA']['value'], $childrenArr, $isEqual, $blockId);
                        $childrenArr['ID'] = $this->addSubQuery($propertyCode, $child['DATA']['value'], $isEqual, $blockId);

                    }
                } else {
                    array_push($childrenArr, array(
                        $isEqual . "PROPERTY_" . $idProperty =>
                            ( $child['DATA']['value'] != '' ? $child['DATA']['value'] :
                                ( !empty($ob['PROPERTY_' . $idProperty . '_VALUE']) ? $ob['PROPERTY_' . $idProperty . '_VALUE'] : 'property_is_not_exist')
                            )
                    ));
                }
            }
            elseif ($child['CLASS_ID'] == 'CondGroup') {
                $isTrue = ($child['DATA']['True'] == 'True') ? true : false;
                $childrenOfChild = $this->getChilden($child['CHILDREN'], $isTrue, $ob);
                $mergedAr = array_merge(Array(
                    "LOGIC"=>$child['DATA']['All'],
                ), $childrenOfChild);
                array_push($childrenArr, $mergedAr);
            }
        }

        return $childrenArr;
    }

    private function filterToId($filter){
        $res = CIBlockElement::GetList(
            array(),
            $filter,
            false,
            array(),
            array('ID')
        );
        $crosssellIds = array();
        while ($idCrosssell = $res->fetch()){
            array_push($crosssellIds,$idCrosssell['ID']);
        }
        return $crosssellIds;
    }

    private function getCategoryList(){
        //getCategories
        $db_crosssell_category = \Kit\Crosssell\Orm\CrosssellCategoryTable::getList(array(
            'select' =>
                array(
                    'ID', 'NAME', 'SORT'
                ),
            'order' =>
                array(
                    'SORT' => 'ASC'
                ),
            'cache' =>
                array(
                    'ttl' => 3600
                ),
        ));
        while ($categories = $db_crosssell_category->fetch()){
            $categoryArray[$categories['ID']] = array(  'NAME' => $categories['NAME'],
                'SORT' => intval($categories['SORT']));
        }
        return $categoryArray;
    }

    private function getCrosssellList($productId, $categoryArray, $crosssellIds){

        //getCrosssells
        $arNum = $arSort = array();

        $prodFilter = '%\"' .strval($productId) . '\"%';

        if(!$this->arParams['PAGE_ELEMENT_COUNT'])
            $arNum = array("NUMBER_PRODUCTS");

        if(!$this->arParams['ELEMENT_SORT_FIELD'])
            $arSort = array("SORT_ORDER", "SORT_BY");

        $db_crosssell = \Kit\Crosssell\Orm\CrosssellTable::getList(array(
            'filter' =>
                array(
                    'TYPE_BLOCK' => 'CROSSSELL',
                    'Active' => 'Y',
                    'ID' => $crosssellIds,
                    'PRODUCTS' => $prodFilter,
                    'SITES' => '%"'.SITE_ID.'\"%'
                ),
            'select' =>
                array_merge(array(
                    'ID', 'NAME', 'CATEGORY_ID', 'RULE2', 'RULE3'
                ), $arNum, $arSort),
            'order' =>
                array(
                    'SORT' => 'ASC',
                    'ID' => 'ASC',
                ),
            'cache' =>
                array(
                    'ttl' => 3600
                ),

        ));

        $crosssells = $db_crosssell->fetchAll();

        $obCond = new \KitCrosssellCatalogCondTree();
        $obCond->Init( BT_COND_MODE_PARSE, BT_COND_BUILD_CATALOG, array () );

        $interruptMode = $this->arParams['INTERRUPT_MODE'];
        if (!in_array($interruptMode,array('Y','N'))){
            $interruptMode = 'Y';
        }

        $categoryCrosssell = array();
        $categoryIds = array();

        foreach ($crosssells as $crosssell)
        {
            if ($interruptMode == 'Y'){
                if (in_array($crosssell['CATEGORY_ID'], $categoryIds)){
                    continue;
                }
            }
            //has parent category
            if ($crosssell['CATEGORY_ID'] > 0)
            {

                if (!isset($categoryCrosssell[$crosssell['CATEGORY_ID']]['NAME']))
                {
                    $categoryCrosssell[$crosssell['CATEGORY_ID']]['NAME'] = $categoryArray[$crosssell['CATEGORY_ID']]['NAME'];
                    $categoryCrosssell[$crosssell['CATEGORY_ID']]['PRODUCT_NUMBER'] = isset($crosssell['NUMBER_PRODUCTS']) ? $crosssell['NUMBER_PRODUCTS'] : $this->arParams['PAGE_ELEMENT_COUNT'];
                    $categoryCrosssell[$crosssell['CATEGORY_ID']]['SORT_ORDER'] = isset($crosssell['SORT_ORDER']) ? $crosssell['SORT_ORDER'] : $this->arParams['ELEMENT_SORT_FIELD'];
                    $categoryCrosssell[$crosssell['CATEGORY_ID']]['SORT_BY'] = isset($crosssell['SORT_BY']) ? $crosssell['SORT_BY'] : $this->arParams['ELEMENT_SORT_ORDER'];
                    $categoryCrosssell[$crosssell['CATEGORY_ID']]['CONDITION'] = array( 'CLASS_ID' => 'CondGroup',
                        'DATA' => array(
                            'All' => 'OR',
                            'True' => 'True'
                        ),
                        'CHILDREN' => array(),
                        'RULE3' => array()
                    );
                }

                $condParsed = $obCond->Parse( unserialize($crosssell['RULE2']) );

                if (is_array($condParsed)){
                    array_push($categoryCrosssell[$crosssell['CATEGORY_ID']]['CONDITION']['CHILDREN'], $condParsed);
                }

                $rule3Array = unserialize($crosssell['RULE3']);
                if (is_array($rule3Array)){
                    foreach ($rule3Array as $rule3) {
                        array_push($categoryCrosssell[$crosssell['CATEGORY_ID']]['CONDITION']['RULE3'], $rule3);
                    }

                }
            } else { // root category
                $condParsed = $obCond->Parse( unserialize($crosssell['RULE2']) );
                $categoryCrosssell[$crosssell['CATEGORY_ID'].'_'.$crosssell['ID']]['NAME'] = $crosssell['NAME'];
                $categoryCrosssell[$crosssell['CATEGORY_ID'].'_'.$crosssell['ID']]['PRODUCT_NUMBER'] = isset($crosssell['NUMBER_PRODUCTS']) ? $crosssell['NUMBER_PRODUCTS'] : $this->arParams['PAGE_ELEMENT_COUNT'];
                $categoryCrosssell[$crosssell['CATEGORY_ID'].'_'.$crosssell['ID']]['SORT_ORDER'] = isset($crosssell['SORT_ORDER']) ? $crosssell['SORT_ORDER'] : $this->arParams['ELEMENT_SORT_FIELD'];
                $categoryCrosssell[$crosssell['CATEGORY_ID'].'_'.$crosssell['ID']]['SORT_BY'] = isset($crosssell['SORT_BY']) ? $crosssell['SORT_BY'] : $this->arParams['ELEMENT_SORT_ORDER'];
                $categoryCrosssell[$crosssell['CATEGORY_ID'].'_'.$crosssell['ID']]['CONDITION'] = $condParsed;
                $categoryCrosssell[$crosssell['CATEGORY_ID'].'_'.$crosssell['ID']]['CONDITION']['RULE3'] = unserialize($crosssell['RULE3']);

            }
            array_push($categoryIds, $crosssell['CATEGORY_ID']);
        }

        return $categoryCrosssell;
    }

    private function getCollectionList($arParametersCollection){

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
                                'TYPE_BLOCK' => 'CROSSSELL',
                                'Active' => 'Y',
                                'CATEGORY_ID' => $catId
                            ),
                        'select' =>
                            array(
                                'ID', 'NAME', 'CATEGORY_ID', 'SORT'
                            ),
                    ));
                    while ($item = $db_collection->fetch())
                    {
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
                                    'TYPE_BLOCK' => 'CROSSSELL',
                                    'Active' => 'Y',
                                    'ID' => $collectionId
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
                }
            }
        }else{
            $db_collection = \Kit\Crosssell\Orm\CrosssellTable::getList(array(
                'filter' =>
                    array(
                        'SITES' => '%"'.SITE_ID.'\"%',
                        'TYPE_BLOCK' => 'CROSSSELL',
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
        foreach ($sorted as $collectionId => $collectionItem)
        {
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


    private function getCrosssell($productId, $crosssellId = false) {
        if(!Loader::includeModule('kit.crosssell')) {
            return;
        }
        $prodFilter = '%\"' .strval($productId) . '\"%';

        if (!$crosssellId){
            $filter = array('TYPE_BLOCK' => 'CROSSSELL', 'Active' => 'Y', 'PRODUCTS' => $prodFilter, 'SITES' => '%"'.SITE_ID.'\"%');
        } else {
            $filter = array('TYPE_BLOCK' => 'CROSSSELL', 'Active' => 'Y', 'PRODUCTS' => $prodFilter, 'ID' => strval($crosssellId), 'SITES' => '%"'.SITE_ID.'\"%');
        }

        $arQuery = \Kit\Crosssell\Orm\CrosssellTable::getList(
            array(
                'select' => array('ID', 'NAME', 'RULE2', 'RULE3', 'SORT_BY', 'SORT_ORDER', 'NUMBER_PRODUCTS'),
                'filter' => $filter,
            )
        );

        $ar = $arQuery->fetch();

        if(count($ar) > 0) {
            return $ar;
        }
        return false;
    }

    private function getProductsAndCheck($productId, $crosssellId = false) {

        $crosssell = $this->getCrosssell($productId, $crosssellId);
        $cond = '';
        if($crosssell != false) {
            $obCond = new \KitCrosssellCatalogCondTree();
            $boolCond = $obCond->Init( BT_COND_MODE_PARSE, BT_COND_BUILD_CATALOG, array () );
            $condParsed = $obCond->Parse( unserialize($crosssell['RULE2']) );
            $condName = $crosssell['NAME'];
            $cond = $condParsed;
            $cond['RULE3'] = unserialize($crosssell['RULE3']);
        } else {
            return false;
        }
        if($cond != '') {
            return array(
                "COND" => $cond,
                "COND_NAME" => $condName,
                'SORT_BY' => $crosssell['SORT_BY'],
                'RULE3' => $crosssell['RULE3'],
                'SORT_ORDER' => $crosssell['SORT_ORDER'],
                'NUMBER_OF_PRODUCTS' => $crosssell['NUMBER_PRODUCTS']
            );
        } else {
            return false;
        }
    }

    public function executeComponent()
    {
        if(!Loader::includeModule('kit.crosssell')) {
            return;
        }
        CModule::IncludeModule("catalog");
        $templateName = 'crosssell_news';
        if(IsModuleInstalled('kit.origami')) {
            $templateName = 'origami_crosssell_news';
        }

        global $USER;

        $cache_id = serialize(array($this->arParams, ($this->arParams['CACHE_GROUPS']==='N'? false: $USER->GetGroups())));
        $obCache = new CPHPCache;

        if ($obCache->InitCache($this->arParams['CACHE_TIME'], $cache_id, 'kit.crosssell'))
        {
            $vars = $obCache->GetVars();
            $this->arResult = $vars['arResult'];
        }
        elseif ($obCache->StartDataCache())
        {
            //if component called from complex component - crosssell.crosssell
            if ($this->arParams['SECTION_MODE'] == 'Y')
            {

                $categoryList = $this->getCategoryList();
                $crosssellList = $this->getCollectionList($this->arParams['CROSSSELL_LIST']);
                $crosssellArray = $this->getCrosssellList($this->arParams['PRODUCT_ID'], $categoryList, $crosssellList['COLLECTION_IDS']);
                $this->arResult['SAFE'] = true;
                if($crosssellArray)
                {
                    foreach ($crosssellArray as $categoryId => $crosssell)
                    {
                        $crosssellArray[$categoryId]['FILTER'] = $this->getFilter($crosssell['CONDITION']);

                        //exclude current element ID
                        $crosssellArray[$categoryId]['FILTER'] = array("INCLUDE_SUBSECTIONS" => "Y",
                            array(
                                "LOGIC" => "AND",
                                $crosssellArray[$categoryId]['FILTER'][0]
                            ));
                        array_push($crosssellArray[$categoryId]['FILTER'][0], array("!ID" => $this->arParams['PRODUCT_ID']));

                        //filtering empty crosssells
                        $res = CIBlockElement::GetList(
                            array(),
                            $crosssellArray[$categoryId]['FILTER'],
                            false,
                            false,
                            array('ID', 'IBLOCK_ID')
                        );

                        $items = array();
                        $isCatalog = '';
                        while($item = $res->fetch()) {
                            if($item && CCatalog::GetByID($item['IBLOCK_ID'])) {
                                $isCatalog = 'Y';
                            }

                            $items['ID'][] = $item['ID'];
                        }

                        if (
                            !$items ||
                            ( $this->getTemplateName() == $templateName && $isCatalog == 'Y' )
                        ){
                            unset($crosssellArray[$categoryId]);
                            continue;
                        }

                        $crosssellArray[$categoryId]['FILTER'] = $items;
                        unset($crosssellArray[$categoryId]['CONDITION']);
                    }
                }

                if (count($crosssellArray) > 0)
                {
                    $this->arResult['CROSSSELL_ARRAY'] = $crosssellArray;
                    $this->arResult['COMPONENT_PATH'] = $this->GetPath();
                } else {
                    //return false;
                }
            } else {
                $condProductsToShow = $this->getProductsAndCheck($this->arParams['PRODUCT_ID']);
                if($condProductsToShow !== false) {
                    $this->offerBlockId = $this->getOfferBlockId();
                    $this->arResult['arFilter'] = $this->getFilter($condProductsToShow['COND']);

                    //exclude current element ID
                    $this->arResult['arFilter'] = array("INCLUDE_SUBSECTIONS" => "Y",
                        array(
                            "LOGIC" => "AND",
                            $this->arResult['arFilter'][0]
                        ));
                    array_push($this->arResult['arFilter'][0], array("!ID" => $this->arParams['PRODUCT_ID']));

                    $cnt = CIBlockElement::GetList(
                        array(),
                        $this->arResult['arFilter'],
                        array(),
                        false,
                        array('ID')
                    );
                    if ($cnt == 0)
                        return false;
                    $this->arResult['COND_NAME'] = $condProductsToShow['COND_NAME'];
                    $this->arResult['NUMBER_OF_PRODUCTS'] = $condProductsToShow['NUMBER_OF_PRODUCTS'];
                    $this->arResult['SORT_ORDER'] = $condProductsToShow['SORT_ORDER'];
                    $this->arResult['SORT_BY'] = $condProductsToShow['SORT_BY'];
                    $this->arResult["IBLOCK_ID"] = $this->arParams['IBLOCK_ID'];
                    $this->arResult['SAFE'] = true;
                } else {
                    $this->arResult['SAFE'] = false;
                }
                $this->arResult['COMPONENT_PATH'] = $this->GetPath();

            }

            $obCache->EndDataCache(array(
                'arResult' => $this->arResult,
            ));

        }
        $this->includeComponentTemplate();
        return $this->arResult;
    }
}

?>
