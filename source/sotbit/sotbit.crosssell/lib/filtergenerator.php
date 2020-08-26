<?php

namespace Sotbit\Crosssell;

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


class FilterGenerator extends \CBitrixComponent
{
    private $offerBlockId = 0;

    public function getFilter($rule) {
        Loader::includeModule('iblock');

        $conditions = unserialize($rule);
        $arrFilter = array(
            "INCLUDE_SUBSECTIONS" => "Y",
            array(
                "LOGIC"=>$conditions['DATA']['All'],
            )
        );
        $iterationPrice = 0;
        $iterationPriceNumber = 0;
        foreach ($conditions['CHILDREN'] as $index => $child){
            if (strpos($child['CLASS_ID'], 'CondIBPrice') !== false)
            {
                $iterationPriceNumber++;
            }
        }

        if ($conditions["DATA"]['True'] == 'True')
        {
            $globalTrue = true;
        } else {
            $globalTrue = false;
        }


        if(\CModule::IncludeModule("iblock"))
        {
            $res = \CIBlockElement::GetByID( $this->arParams['PRODUCT_ID'] );
            if($ob = $res->GetNext()) $sectionId = $ob['IBLOCK_SECTION_ID'];
        }

        foreach ($conditions['CHILDREN'] as $index => $child) {

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
                if (strlen($child['DATA']['value']) > 0) {
                    $iblockId = intval($child['DATA']['value']);
                    $promoIds = array();
                    $rs = \CIBlockElement::GetList(
                        [],
                        [
                            'ACTIVE' => 'Y',
                            'IBLOCK_ID' => $iblockId,
                            '!PROPERTY_LINK_PRODUCTS' => false
                        ],
                        false,
                        false,
                        ['PROPERTY_LINK_PRODUCTS']
                    );
                    while ($el = $rs->Fetch()) {
                        if ($el['PROPERTY_LINK_PRODUCTS_VALUE'] > 0) {
                            $promoIds[] = $el['PROPERTY_LINK_PRODUCTS_VALUE'];
                        }
                    }

                    if (!is_array($arrFilter[0][0][$isEqual . 'ID'])) {
                        $arrFilter[0][0][$isEqual . 'ID'] = array();
                    }
                    $arrFilter[0][0][$isEqual . 'ID'] = array_merge($arrFilter[0][0][$isEqual . 'ID'], $promoIds);

                    $arrFilter[0][0][$isEqual . 'ID'] = array_unique($arrFilter[0][0][$isEqual . 'ID'], SORT_NUMERIC);
                }

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

                if ($iterationPrice == ($iterationPriceNumber-1)){
                    $offerList = \CIBlockElement::GetList(
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
                        $productId = \CCatalogSku::GetProductInfo($offer['ID'],$this->offerBlockId);
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
                $isOffer = \CCatalogSKU::GetInfoByOfferIBlock($blockId);
                $currentPropertyValue = $child['DATA']['value'];
                $db_props = \CIBlockElement::GetProperty($blockId, $this->arParams['PRODUCT_ID'], array("sort" => "asc"), array('ID' => $idProperty));
                $elementPropertyValue = array();
                while($ar_props = $db_props->GetNext()){
                    array_push($elementPropertyValue, $ar_props["VALUE"]);
                }

                $arSelect = Array("PROPERTY_" . $idProperty);
                $arFilter = Array("ID"=>$this->arParams['ELEMENT_ID']);
                $res = \CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                $ob = $res->fetch();

                if ($currentPropertyValue == '') {
                    $currentPropertyValue = $elementPropertyValue;
                }

                $propertyId = array($isEqual."PROPERTY_" . $idProperty => $currentPropertyValue);

                $res1 = \CIBlockProperty::GetByID($idProperty, false, false);
                if($ar_res = $res1->GetNext()){
                    $propertyCode = $ar_res['CODE'];
                    $propertyUserType = $ar_res['USER_TYPE'];
                    $propertyType = $ar_res['PROPERTY_TYPE'];
                }

                if ($isOffer){
                    $propertyName = array();
                    $tblName = $ar_res['USER_TYPE_SETTINGS']['TABLE_NAME'];
                    $highloadItemID = $child['DATA']['value'];

                    if($highloadItemID == ''){
                        $res = \CCatalogSKU::getOffersList(
                            $this->arParams['PRODUCT_ID'],
                            '',
                            $skuFilter = array(),
                            $fields = array(),
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

                    if ($propertyUserType == 'directory' && $propertyType == 'S')
                    {
                        if ($highloadItemID == ''){
                            if (is_array($currentProperties)){
                                $ids = array();
                                foreach ($currentProperties as $currentProperty) {
                                    $arrFilter[0][0][$isEqual.'ID'] = array();
                                    $arrFilter = $this->pushIdOffer($propertyCode, $currentProperty, $arrFilter, $isEqual, $blockId);
                                    array_push($ids, $arrFilter[0][0][$isEqual.'ID']);
                                }
                            }
                            if (count($ids)>1){
                                $result = $ids[0];
                                for ($i = 0; $i < count($ids); $i++){
                                    $result = array_intersect($result, $ids[$i]);
                                }
                                $arrFilter[0][0][$isEqual.'ID'] = $result;
                            }
                            else {
                                foreach ($ids as $id) {
                                    $arrFilter[0][0][$isEqual.'ID'] = $id;
                                }
                            }

                        }
                        else {
                            $ufXmlId = $this->getUFXMLID($highloadItemID,$tblName);
                            $arrFilter = $this->pushIdOffer($propertyCode, $ufXmlId, $arrFilter, $isEqual, $blockId);
                        }

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


        if(is_array($conditions['RULE3']))
        {
            if (!is_array($arrFilter[0][0][$isEqual.'ID']))
                $arrFilter[0][0][$isEqual.'ID'] = array();

            foreach ($conditions['RULE3'] as $conditionId) {
                $parts = explode(":",$conditionId);
                $conditionId = $parts[0];
                $res = \CIBlockElement::GetProperty($this->arParams['IBLOCK_ID'], $this->arParams['PRODUCT_ID'], "sort", "asc", array("ID" => $conditionId));
                while ($ob = $res->GetNext())
                {
                    array_push($arrFilter[0][0][$isEqual.'ID'], $ob['VALUE']);
                }
                if (!is_array($this->arParams['LINK_IBLOCK_ID']))
                    $this->arParams['LINK_IBLOCK_ID'] = array();
                array_push($this->arParams['LINK_IBLOCK_ID'], $parts[1]);
            }
        }
        return $arrFilter;
    }

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

    private function pushIdOffer($propertyCode, $propertyValue, $arrFilter, $isEqual,  $blockId){

        $arFilter = array('ID' => \CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
            'IBLOCK_ID' => $blockId,
            'PROPERTY_'.$propertyCode => $propertyValue,
        )));
        $res = \CIBlockElement::GetList(Array(), $arFilter, false, Array(), array("ID"));

        if (!is_array($arrFilter[0][0][$isEqual.'ID'])){
            $arrFilter[0][0][$isEqual.'ID'] = array();
        }
        while($ar = $res -> fetch()){
            array_push($arrFilter[0][0][$isEqual.'ID'], $ar['ID']);
        }
        return $arrFilter;
    }

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

        $iterationPrice = 0;

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
                if (strlen($child['DATA']['value']) > 0) {
                    $iblockId = intval($child['DATA']['value']);
                    $promoIds = array();
                    $rs = \CIBlockElement::GetList(
                        [],
                        [
                            'ACTIVE' => 'Y',
                            'IBLOCK_ID' => $iblockId,
                            '!PROPERTY_LINK_PRODUCTS' => false
                        ],
                        false,
                        false,
                        ['PROPERTY_LINK_PRODUCTS']
                    );
                    while ($el = $rs->Fetch()) {
                        if ($el['PROPERTY_LINK_PRODUCTS_VALUE'] > 0) {
                            $promoIds[] = $el['PROPERTY_LINK_PRODUCTS_VALUE'];
                        }
                    }

                    if (!is_array($childrenArr[0][0][$isEqual . 'ID'])) {
                        $childrenArr[0][0][$isEqual . 'ID'] = array();
                    }
                    $childrenArr[0][0][$isEqual . 'ID'] = array_merge($childrenArr[0][0][$isEqual . 'ID'], $promoIds);

                    $childrenArr[0][0][$isEqual . 'ID'] = array_unique($childrenArr[0][0][$isEqual . 'ID'], SORT_NUMERIC);
                }

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

                if ($iterationPrice == ($iterationPriceNumber-1)){
                    $offerList = \CIBlockElement::GetList(
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
                        $productId = \CCatalogSku::GetProductInfo($offer['ID'],$this->offerBlockId);
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
                $idProperty =  $pieces[2];

                $arSelect = Array("PROPERTY_" . $idProperty);
                $arFilter = Array("ID"=>$this->arParams['ELEMENT_ID']);
                $res = \CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                $ob = $res->fetch();

                array_push($childrenArr, array(
                    $isEqual."PROPERTY_" . $idProperty => ($child['DATA']['value'] != '') ? $child['DATA']['value'] : $ob['PROPERTY_' . $idProperty . '_VALUE']
                ));
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
}