<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
	die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module = 'kit.origami';
CModule::includeModule('sale');
CModule::includeModule($module);
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$arPersonTypeNames = array();
$dbPerson = CSalePersonType::GetList( array(), array(
		"LID" => WIZARD_SITE_ID
) );
while ( $arPerson = $dbPerson->Fetch() )
{
	$arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];
}


$idFiz = array_search( GetMessage( "WZD_PERSON_TYPE_FIZ" ), $arPersonTypeNames );
$idUr = array_search( GetMessage( "WZD_PERSON_TYPE_UR" ), $arPersonTypeNames );
//$idIp = array_search( GetMessage( "WZD_PERSON_TYPE_IP" ), $arPersonTypeNames );


Option::set($module, "PERSONAL_PERSON_TYPE", $idFiz);
Option::set($module,'SECTION_VIEW','block');



$rs = \Bitrix\Sale\Internals\PersonTypeTable::getList(
	[
		'select' => [
			'ID',
			'NAME',
		],
		'filter' => ['ACTIVE' => 'Y','LID' => WIZARD_SITE_ID],
		'limit' => 1,
	]
);
while ($personalType = $rs->fetch()) {
	\Sotbit\Origami\Config\Option::set('PERSON_TYPE',$personalType['ID'],WIZARD_SITE_ID);
}

$rs = \Bitrix\Sale\Delivery\Services\Table::getList(
	[
		'select' => [
			'ID',
			'NAME',
		],
		'filter' => [
			'ACTIVE'    => 'Y',
			'PARENT_ID' => 0,
		],
		'limit' => 1
	]
);
while ($delivery = $rs->fetch()) {
	\Sotbit\Origami\Config\Option::set('DELIVERY',$delivery['ID'],WIZARD_SITE_ID);
}


$rs = \Bitrix\Sale\Internals\PaySystemActionTable::getList([
	'select' => [
		'ID',
		'NAME',
	],
	'filter' => ['ACTIVE' => 'Y'],
	'limit' => 1
]);
while ($payment = $rs->fetch()) {
	\Sotbit\Origami\Config\Option::set('PAYMENT',$payment['ID'],WIZARD_SITE_ID);
}

$rs = \Bitrix\Sale\Internals\OrderPropsTable::getList(
	[
		'select' => [
			'ID',
			'PERSON_TYPE_ID',
			'NAME',
		],
		'filter' => ['CODE' => 'FIO'],
		'limit' => 1
	]
);
while ($prop = $rs->fetch()) {
	\Sotbit\Origami\Config\Option::set('PROP_NAME',$prop['ID'],WIZARD_SITE_ID);
}

$rs = \Bitrix\Sale\Internals\OrderPropsTable::getList(
	[
		'select' => [
			'ID',
			'PERSON_TYPE_ID',
			'NAME',
		],
		'filter' => ['CODE' => 'PHONE'],
		'limit' => 1
	]
);
while ($prop = $rs->fetch()) {
	\Sotbit\Origami\Config\Option::set('PROP_PHONE',$prop['ID'],WIZARD_SITE_ID);
}

$rs = \Bitrix\Sale\Internals\OrderPropsTable::getList(
	[
		'select' => [
			'ID',
			'PERSON_TYPE_ID',
			'NAME',
		],
		'filter' => ['CODE' => 'EMAIL'],
		'limit' => 1
	]
);
while ($prop = $rs->fetch()) {
	\Sotbit\Origami\Config\Option::set('PROP_EMAIL',$prop['ID'],WIZARD_SITE_ID);
}


/**
 * For update a simple products
 *
 * required to display on the page
 * if  don't update, the products are not displayed
 * 
 */

$iblockId = $_SESSION["WIZARD_CATALOG_IBLOCK_ID"];

if(!$iblockId)
{
    $iblockId = \Sotbit\Origami\Config\Option::get('IBLOCK_ID',WIZARD_SITE_ID);
}
if($iblockId > 0)
{

			$iblock = new CIBlock;
		$arFields = array(
			"FIELDS" => array(
				"PREVIEW_PICTURE" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => array(
						"FROM_DETAIL" => "Y",
						"SCALE" => "Y",
						"WIDTH" => "235",
						"HEIGHT" => "235",
						"IGNORE_ERRORS" => "N",
						"METHOD" => "resample",
						"COMPRESSION" => 75,
						"DELETE_WITH_DETAIL" => "Y",
						"UPDATE_WITH_DETAIL" => "Y",
					),
				),
				"PREVIEW_TEXT_TYPE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => "text",
				),
				"DETAIL_TEXT_TYPE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => "text",
				),
				"CODE" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => array(
						"UNIQUE" => "Y",
						"TRANSLITERATION" => "Y",
						"TRANS_LEN" => 100,
						"TRANS_CASE" => "L",
						"TRANS_SPACE" => "_",
						"TRANS_OTHER" => "_",
						"TRANS_EAT" => "Y",
						"USE_GOOGLE" => "N",
					),
				),
				"SECTION_CODE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => array(
						"UNIQUE" => "Y",
						"TRANSLITERATION" => "Y",
						"TRANS_LEN" => 100,
						"TRANS_CASE" => "L",
						"TRANS_SPACE" => "_",
						"TRANS_OTHER" => "_",
						"TRANS_EAT" => "Y",
						"USE_GOOGLE" => "N",
					),
				),
			),
		);

		$iblock->Update($iblockId, $arFields);


    $el = new CIBlockElement;

    $arLoadProductArray = Array(
        );

    $arFilter = Array(
        "IBLOCK_ID"=>$iblockId,
        );

    $res = CIBlockElement::GetList(Array(""), $arFilter, Array("IBLOCK_ID", "ID",'DETAIL_PICTURE'));
    while($ar_fields = $res->GetNext())
    {
		$arLoadProductArray['DETAIL_PICTURE'] = CFile::makeFileArray($ar_fields['DETAIL_PICTURE']);
        $ress = $el->Update($ar_fields["ID"], $arLoadProductArray, false, true, true);
    }



	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$iblockId,'CODE' => 'CML2_MANUFACTURER'));
	while ($prop_fields = $properties->GetNext())
	{
	  	$ibp = new CIBlockProperty();
		$ibp->Update($prop_fields['ID'], ['SMART_FILTER' => 'Y','IBLOCK_ID' => $iblockId]);
	}

	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$iblockId,'CODE' => 'KHIT'));
	while ($prop_fields = $properties->GetNext())
	{
	  	$ibp = new CIBlockProperty();
		$ibp->Update($prop_fields['ID'], ['SMART_FILTER' => 'Y','IBLOCK_ID' => $iblockId,'HINT'=> '#00B02A']);
	}

	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$iblockId,'CODE' => 'NOVINKA'));
	while ($prop_fields = $properties->GetNext())
	{
	  	$ibp = new CIBlockProperty();
		$ibp->Update($prop_fields['ID'], ['SMART_FILTER' => 'Y','IBLOCK_ID' => $iblockId,'HINT'=> '#0F7EDA']);
	}

	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$iblockId,'CODE' => 'RASPRODAZHA'));
	while ($prop_fields = $properties->GetNext())
	{
	  	$ibp = new CIBlockProperty();
		$ibp->Update($prop_fields['ID'], ['SMART_FILTER' => 'Y','IBLOCK_ID' => $iblockId,'HINT'=> '#F7BE13']);
	}

	$sku = CCatalogSKU::GetInfoByProductIBlock($iblockId);

	if($sku['IBLOCK_ID'] > 0){
		$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$sku['IBLOCK_ID'],'CODE' => 'TSVET'));
		while ($prop_fields = $properties->GetNext())
		{
			$ibp = new CIBlockProperty();
			$ibp->Update($prop_fields['ID'], ['SMART_FILTER' => 'Y','IBLOCK_ID' => $sku['IBLOCK_ID']]);
		}

		$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$sku['IBLOCK_ID'],'CODE' => 'RAZMER'));
		while ($prop_fields = $properties->GetNext())
		{
			$ibp = new CIBlockProperty();
			$ibp->Update($prop_fields['ID'], ['SMART_FILTER' => 'Y','IBLOCK_ID' => $sku['IBLOCK_ID'],'DISPLAY_TYPE' => 'H']);
		}
	}


}

/**
 * For adding a price
 *
 */
$selectBase = $wizard->GetVar("base");
$selectOpt = $wizard->GetVar("opt");
$selectSmallOpt = $wizard->GetVar("small_opt");

$priceAll = array();
$priceSelect = array();

$priceAll = array("BASE", "OPT", "SMALL_OPT");

$priceSelect = array($selectBase, $selectOpt, $selectSmallOpt);

$priceDelete = array_diff($priceAll, $priceSelect);

$ResultPriceList = CCatalogGroup::GetList(Array(), Array(), Array('ID', 'NAME'));

while ($arPriceItem = $ResultPriceList->Fetch()) {
    $arFields = Array();

    if (in_array($arPriceItem["NAME"], $priceDelete)) {

        CCatalogGroup::Delete($arPriceItem["ID"]);

    }
    if (in_array($arPriceItem["NAME"], $priceSelect)) {
        switch ($arPriceItem["NAME"]) {
            case 'BASE':
                $priceTitle = GetMessage('WZD_PRICE_BASE_NAME_RU');
                break;
            case 'OPT':
                $priceTitle = GetMessage('WZD_PRICE_OPT_NAME_RU');
                break;
            case 'SMALL_OPT':
                $priceTitle = GetMessage('WZD_PRICE_OPT_SMALL_NAME_RU');
                break;
        }

        $arFields = Array(
            "USER_LANG" => array(
                "ru" => $priceTitle,
                "en" => ""
            )
        );

        CCatalogGroup::Update($arPriceItem["ID"], $arFields);
    }
}

$dbResultList = CCatalogGroup::GetList(Array(), Array("BASE" =>"Y"));
if(!($arPriceType = $dbResultList->Fetch())) {
    $arFields = Array(
    );

    $rsLanguage = CLanguage::GetList($by, $order, array());
    while ($arLanguage = $rsLanguage->Fetch()) {
        WizardServices::IncludeServiceLang("catalog.php", $arLanguage["ID"]);
        $arFields["USER_LANG"][$arLanguage["ID"]] = GetMessage("WIZ_PRICE_NAME");
    }

    $arFields = Array(
        "BASE" => "Y",
        "SORT" => 100,
        "NAME" => "BASE",
        "USER_GROUP" => Array(1, 2),
        "USER_GROUP_BUY" => Array(1, 2),
        "USER_LANG" => array(
            "ru" => GetMessage('WZD_PRICE_BASE_NAME_RU'),
            "en" => ""
        )
    );

    CCatalogGroup::Add($arFields);
}

$arSiteOrigami = \Bitrix\Main\SiteTemplateTable::getList(array('filter' => array('TEMPLATE' => 'kit_origami')))->fetch();

if(!isset($arSiteOrigami['SITE_ID'])) {
    $arSiteOrigami['SITE_ID'] = 's1';
}

if(CModule::includeModule('kit.origami')) {
    \Sotbit\Origami\Config\Option::set('ACTION_PRODUCTS', serialize(['BUY', 'DELAY', 'COMPARE']), $arSiteOrigami['SITE_ID']);
    \Sotbit\Origami\Config\Option::set('VARIANT_LIST_VIEW', 'template_3', $arSiteOrigami['SITE_ID']);
}

$arIblockAdv = CIBlock::GetList(array(), array(
    'IBLOCK_TYPE_ID' => 'kit_origami_content',
    'CODE' => ['advantages' , 'kit_origami_advantages']
))->fetch();

//add advantages default settings
if(is_array($arIblockAdv)) {
    try {
        \Sotbit\Origami\Internals\OptionsTable::add(array(
            'CODE' => 'IBLOCK_ID_ADVANTAGES',
            'VALUE' => $arIblockAdv['ID'],
            'SITE_ID' => $arIblockAdv['LID']
        ));
        \Sotbit\Origami\Internals\OptionsTable::add(array(
            'CODE' => 'IBLOCK_TYPE_ADVANTAGES',
            'VALUE' => 'kit_origami_content',
            'SITE_ID' => $arIblockAdv['LID']
        ));
    } catch(\Bitrix\Main\DB\SqlQueryException $e) {

    }
}


$arIblockBanner = CIBlock::GetList(array(), array(
    'IBLOCK_TYPE_ID' => 'kit_origami_advertising',
    'CODE' => 'kit_origami_banners'
))->fetch();

if(is_array($arIblockBanner)) {
    try {
        \Sotbit\Origami\Internals\OptionsTable::add(array(
            'CODE' => 'IBLOCK_ID_BANNERS',
            'VALUE' => $arIblockBanner['ID'],
            'SITE_ID' => $arIblockBanner['LID']
        ));
        \Sotbit\Origami\Internals\OptionsTable::add(array(
            'CODE' => 'IBLOCK_TYPE_BANNERS',
            'VALUE' => 'kit_origami_advertising',
            'SITE_ID' => $arIblockBanner['LID']
        ));
    } catch(\Bitrix\Main\DB\SqlQueryException $e) {

    }
}

$arIblockPromotion = CIBlock::GetList(array(), array(
    'IBLOCK_TYPE_ID' => 'kit_origami_content',
    'CODE' => ['kit_origami_promotions', 'promotions']
))->fetch();

if(is_array($arIblockPromotion)) {
    try {
        \Sotbit\Origami\Internals\OptionsTable::add(array(
            'CODE' => 'IBLOCK_ID_PROMOTION',
            'VALUE' => $arIblockPromotion['ID'],
            'SITE_ID' => $arIblockPromotion['LID']
        ));
        \Sotbit\Origami\Internals\OptionsTable::add(array(
            'CODE' => 'IBLOCK_TYPE_PROMOTION',
            'VALUE' => 'kit_origami_content',
            'SITE_ID' => $arIblockPromotion['LID']
        ));
    } catch(\Bitrix\Main\DB\SqlQueryException $e) {

    }
}

if(CModule::includeModule('fileman')) {
    $properties = CFileMan::GetPropstypes($arSiteOrigami['SITE_ID']);
    if(!isset($properties['SHOW_SIDE_BLOCK']))
        $properties['SHOW_SIDE_BLOCK'] = GetMessage("WZD_SHOW_SIDE_BLOCK");

    if(!isset($properties['SHOW_SIDE_BLOCK_SUBSECTION']))
        $properties['SHOW_SIDE_BLOCK_SUBSECTION'] = GetMessage("WZD_SHOW_SIDE_BLOCK_SUBSECTION");

    if(!isset($properties['SHOW_BREADCRUMBS']))
        $properties['SHOW_BREADCRUMBS'] = GetMessage("WZD_SHOW_BREADCRUMBS");

    CFileMan::SetPropstypes($properties, false, $arSiteOrigami['SITE_ID']);
}


Option::set('main','optimize_css_files','N');
Option::set('main','optimize_js_files','N');
Option::set('main','use_minified_assets','N');
Option::set('main','move_js_to_body','N');
Option::set('main','compres_css_js_files','N');

?>