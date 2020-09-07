<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

CModule::includeModule('iblock');
CModule::includeModule('catalog');

$module = 'sotbit.crosssell';
CModule::includeModule($module);

use Sotbit\Crosssell\Orm\CrosssellCategoryTable;
use Sotbit\Crosssell\Orm\CrosssellTable;

$arFields['SITE_ID'] = WIZARD_SITE_ID;
$arFields['SORT'] = 100;
$arFields['SYMBOL_CODE'] = 'pokhozhie_tovary';
$arFields['NAME'] = GetMessage('WZD_CROSS_pokhozhie_tovary');
$result = CrosssellCategoryTable::Add($arFields);
$catAnalogID = $result->getId();
$arFields['SYMBOL_CODE'] = 'tovary_brenda';
$arFields['NAME'] = GetMessage('WZD_CROSS_tovary_brenda');
$result = CrosssellCategoryTable::Add($arFields);
$catBrandID = $result->getId();
$arFields['NAME'] = GetMessage('WZD_CROSS_aksessuary');
$arFields['SYMBOL_CODE'] = 'aksessuary';
CrosssellCategoryTable::Add($arFields);
$arFields['NAME'] = GetMessage('WZD_CROSS_soputstvuyushchie_tovary');
$arFields['SYMBOL_CODE'] = 'soputstvuyushchie_tovary';
$result = CrosssellCategoryTable::Add($arFields);
$catSoputID = $result->getId();
$arFields['NAME'] = GetMessage('WZD_CROSS_predlozheniya_magazina');
$arFields['SYMBOL_CODE'] = 'predlozheniya_magazina';
$result = CrosssellCategoryTable::Add($arFields);
$catOffersID = $result->getId();

$iblockID = $_SESSION["WIZARD_CATALOG_IBLOCK_ID"];

if(!$iblockId)
{
    $iblockID = \Sotbit\Origami\Config\Option::get('IBLOCK_ID',WIZARD_SITE_ID);
}

$arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"KHIT"))->Fetch();
if($arProp)
{
    $propID = $arProp['ID'];

    $arPropVal = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"KHIT", 'XML_ID' => 'true'))->Fetch();
    $propV = $arPropVal["ID"];

    $arSer = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBProp:'.$iblockID.':'.$propID,
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $propV,
                            ),
                    ),
            ),
    );

    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_KHIT'),
        "SYMBOL_CODE" => 'khity',
        "RULE1" => serialize($arSer),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 5,
        "TYPE_BLOCK" => 'COLLECTION',
    );

    $result = CrosssellTable::add($arFields);
}

$arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"NOVINKA"))->Fetch();
if($arProp)
{
    $propID = $arProp['ID'];

    $arPropVal = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"NOVINKA", 'XML_ID' => 'true'))->Fetch();
    $propV = $arPropVal["ID"];

    $arSer = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBProp:'.$iblockID.':'.$propID,
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $propV,
                            ),
                    ),
            ),
    );

    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_NOVINKA'),
        "SYMBOL_CODE" => 'novinka',
        "RULE1" => serialize($arSer),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 5,
        "TYPE_BLOCK" => 'COLLECTION',
    );

    $result = CrosssellTable::add($arFields);
}

$arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"RASPRODAZHA"))->Fetch();
if($arProp)
{
    $propID = $arProp['ID'];

    $arPropVal = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"RASPRODAZHA", 'XML_ID' => 'true'))->Fetch();
    $propV = $arPropVal["ID"];

    $arSer = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBProp:'.$iblockID.':'.$propID,
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $propV,
                            ),
                    ),
            ),
    );

    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_RASPRODAZHA'),
        "SYMBOL_CODE" => 'rasprodazha',
        "RULE1" => serialize($arSer),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 5,
        "TYPE_BLOCK" => 'COLLECTION',
    );

    $result = CrosssellTable::add($arFields);
}
if($catAnalogID)
{
    $arRule1 = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBIBlock',
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $iblockID,
                            ),
                    ),
            ),
    );

    $arRule2 = array (
        0 =>
            array (
                'controlId' => 'CondGroup',
                'aggregator' => 'AND',
                'value' => 'True',
            ),
        '0__0' =>
            array (
                'controlId' => 'CondIBXmlID',
                'logic' => 'Equal',
                'value' => 'none',
            ),
    );

    $arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"ANALOG_PRODUCTS"))->Fetch();

    $arRule3 = array (
        0 => $arProp['ID'].':'.$iblockID,
    );

    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_ANALOGI'),
        "SYMBOL_CODE" => 'analogi',
        "RULE1" => serialize($arRule1),
        "RULE2" => serialize($arRule2),
        "RULE3" => serialize($arRule3),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 10,
        "TYPE_BLOCK" => 'CROSSSELL',
        "CATEGORY_ID" => $catAnalogID,
    );

    $result = CrosssellTable::add($arFields);
}

if($catBrandID)
{
    $arRule1 = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBIBlock',
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $iblockID,
                            ),
                    ),
            ),
    );

    $arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"BRANDS"))->Fetch();

    $arRule2 = array (
        0 =>
            array (
                'controlId' => 'CondGroup',
                'aggregator' => 'AND',
                'value' => 'True',
            ),
        '0__0' =>
            array (
                'controlId' => 'CondIBProp:'.$iblockID.':'.$arProp['ID'],
                'logic' => 'Equal',
                'value' => '',
            ),
    );

    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_BRANDS'),
        "SYMBOL_CODE" => 'tovary_pokhozhego_brenda',
        "RULE1" => serialize($arRule1),
        "RULE2" => serialize($arRule2),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 10,
        "TYPE_BLOCK" => 'CROSSSELL',
        "CATEGORY_ID" => $catBrandID,
    );

    $result = CrosssellTable::add($arFields);
}

if($catOffersID)
{
    $arRule1 = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBIBlock',
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $iblockID,
                            ),
                    ),
            ),
    );

    $arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"KHIT"))->Fetch();
    $propID = $arProp['ID'];

    $arPropVal = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"KHIT", 'XML_ID' => 'true'))->Fetch();
    $propV = $arPropVal["ID"];

    $arRule2 = array (
        0 =>
            array (
                'controlId' => 'CondGroup',
                'aggregator' => 'AND',
                'value' => 'True',
            ),
        '0__0' =>
            array (
                'controlId' => 'CondIBProp:'.$iblockID.':'.$propID,
                'logic' => 'Equal',
                'value' => $propV,
            ),
    );


    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_KHIT'),
        "SYMBOL_CODE" => 'khity',
        "RULE1" => serialize($arRule1),
        "RULE2" => serialize($arRule2),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 10,
        "TYPE_BLOCK" => 'CROSSSELL',
        "CATEGORY_ID" => $catOffersID,
    );

    $result = CrosssellTable::add($arFields);

    $arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"NOVINKA"))->Fetch();
    $propID = $arProp['ID'];

    $arPropVal = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"NOVINKA", 'XML_ID' => 'true'))->Fetch();
    $propV = $arPropVal["ID"];

    $arRule2 = array (
        0 =>
            array (
                'controlId' => 'CondGroup',
                'aggregator' => 'AND',
                'value' => 'True',
            ),
        '0__0' =>
            array (
                'controlId' => 'CondIBProp:'.$iblockID.':'.$propID,
                'logic' => 'Equal',
                'value' => $propV,
            ),
    );


    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_NOVINKA'),
        "SYMBOL_CODE" => 'novinka',
        "RULE1" => serialize($arRule1),
        "RULE2" => serialize($arRule2),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 10,
        "TYPE_BLOCK" => 'CROSSSELL',
        "CATEGORY_ID" => $catOffersID,
    );

    $result = CrosssellTable::add($arFields);

    $arProp = CIBlock::GetProperties($iblockID, Array(), Array("CODE"=>"RASPRODAZHA"))->Fetch();
    $propID = $arProp['ID'];

    $arPropVal = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"RASPRODAZHA", 'XML_ID' => 'true'))->Fetch();
    $propV = $arPropVal["ID"];

    $arRule2 = array (
        0 =>
            array (
                'controlId' => 'CondGroup',
                'aggregator' => 'AND',
                'value' => 'True',
            ),
        '0__0' =>
            array (
                'controlId' => 'CondIBProp:'.$iblockID.':'.$propID,
                'logic' => 'Equal',
                'value' => $propV,
            ),
    );


    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_RASPRODAZHA'),
        "SYMBOL_CODE" => 'rasprodazha',
        "RULE1" => serialize($arRule1),
        "RULE2" => serialize($arRule2),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 10,
        "TYPE_BLOCK" => 'CROSSSELL',
        "CATEGORY_ID" => $catOffersID,
    );

    $result = CrosssellTable::add($arFields);
}

if($catSoputID)
{
    $newsIblockID = $_SESSION["WIZARD_NEWS_IBLOCK_ID"];

    if(!$newsIblockID)
    {
        $newsIblockID = \Sotbit\Origami\Config\Option::get('IBLOCK_ID_NEWS',WIZARD_SITE_ID);
    }
    $arRule1 = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBIBlock',
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $newsIblockID,
                            ),
                    ),
            ),
    );

    $arRule2 = array (
        0 =>
            array (
                'controlId' => 'CondGroup',
                'aggregator' => 'AND',
                'value' => 'True',
            ),
        '0__0' =>
            array (
                'controlId' => 'CondIBName',
                'logic' => 'Equal',
                'value' => 'none',
            ),
    );

    $arProp = CIBlock::GetProperties($newsIblockID, Array(), Array("CODE"=>"LINK_PRODUCTS"))->Fetch();
    $propID = $arProp['ID'];

    $arRule3 = array (
        0 => $propID.':'.$newsIblockID,
    );

    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_SOP_NOVOSTI'),
        "SYMBOL_CODE" => 'soputstvuyushchie_tovary_novosti',
        "RULE1" => serialize($arRule1),
        "RULE2" => serialize($arRule2),
        "RULE3" => serialize($arRule3),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 10,
        "TYPE_BLOCK" => 'CROSSSELL',
        "CATEGORY_ID" => $catSoputID,
    );

    $result = CrosssellTable::add($arFields);

    $blogIdlockID = $_SESSION["WIZARD_BLOG_IBLOCK_ID"];

    if(!$blogIdlockID)
    {
        $blogIdlockID = \Sotbit\Origami\Config\Option::get('IBLOCK_ID_BLOG',WIZARD_SITE_ID);
    }
    $arRule1 = array (
        'CLASS_ID' => 'CondGroup',
        'DATA' =>
            array (
                'All' => 'AND',
                'True' => 'True',
            ),
        'CHILDREN' =>
            array (
                0 =>
                    array (
                        'CLASS_ID' => 'CondIBIBlock',
                        'DATA' =>
                            array (
                                'logic' => 'Equal',
                                'value' => $blogIdlockID,
                            ),
                    ),
            ),
    );

    $arRule2 = array (
        0 =>
            array (
                'controlId' => 'CondGroup',
                'aggregator' => 'AND',
                'value' => 'True',
            ),
        '0__0' =>
            array (
                'controlId' => 'CondIBName',
                'logic' => 'Equal',
                'value' => 'none',
            ),
    );

    $arProp = CIBlock::GetProperties($blogIdlockID, Array(), Array("CODE"=>"LINK_PRODUCTS"))->Fetch();
    $propID = $arProp['ID'];

    $arRule3 = array (
        0 => $propID.':'.$blogIdlockID,
    );

    $arFields = Array (
        "Active" => 'Y',
        "SITES" => serialize(array(WIZARD_SITE_ID)),
        "SORT" => 100,
        "NAME" => GetMessage('WZD_CROSS_SOP_BLOG'),
        "SYMBOL_CODE" => 'soputstvuyushchie_tovary_blog',
        "RULE1" => serialize($arRule1),
        "RULE2" => serialize($arRule2),
        "RULE3" => serialize($arRule3),
        "SORT_BY" => 'rand',
        "SORT_ORDER" => 'rand',
        'NUMBER_PRODUCTS' => 10,
        "TYPE_BLOCK" => 'CROSSSELL',
        "CATEGORY_ID" => $catSoputID,
    );

    $result = CrosssellTable::add($arFields);


}

$crossell = new \SotbitCrosssell();
$crossell->generateCondition();

?>