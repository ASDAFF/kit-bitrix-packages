<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();


if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
	return;

if(COption::GetOptionString("kit.origami", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

//update iblocks, demo discount and precet
$shopLocalization = $wizard->GetVar("shopLocalization");

$IBLOCK_CATALOG_ID = (isset($_SESSION["WIZARD_CATALOG_IBLOCK_ID"]) ? (int)$_SESSION["WIZARD_CATALOG_IBLOCK_ID"] : 0);
$IBLOCK_OFFERS_ID = (isset($_SESSION["WIZARD_OFFERS_IBLOCK_ID"]) ? (int)$_SESSION["WIZARD_OFFERS_IBLOCK_ID"] : 0);
//reference update

if ($IBLOCK_OFFERS_ID)
{
	$iblockCodeOffers = "kit_origami_offers_"  . WIZARD_SITE_ID;
    $iblockXmlIDOffers = "kit_origami_offers_"  . WIZARD_SITE_ID;
	//IBlock fields
	$iblock = new CIBlock;
	$arFields = Array(
		"ACTIVE" => "Y",
		"FIELDS" => array (
			'IBLOCK_SECTION' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'ACTIVE' => array (
			    'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'Y',
                ),
            'ACTIVE_FROM' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'ACTIVE_TO' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'SORT' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'NAME' => array (
			    'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                ),
			'PREVIEW_PICTURE' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'FROM_DETAIL' => 'N',
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    'DELETE_WITH_DETAIL' => 'N',
                    'UPDATE_WITH_DETAIL' => 'N',
                    ),
                ),
			'PREVIEW_TEXT_TYPE' => array (
			    'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',
                ),
            'PREVIEW_TEXT' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'DETAIL_PICTURE' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    ),
                ),
			'DETAIL_TEXT_TYPE' => array (
			    'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',
                ),
			'DETAIL_TEXT' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'XML_ID' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'CODE' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'UNIQUE' => 'Y',
                    'TRANSLITERATION' => 'Y',
                    'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L',
                    'TRANS_SPACE' => '_',
                    'TRANS_OTHER' => '_',
                    'TRANS_EAT' => 'Y',
                    'USE_GOOGLE' => 'Y',
                    ),
                ),
			'TAGS' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'SECTION_NAME' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'SECTION_PICTURE' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'FROM_DETAIL' => 'N',
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    'DELETE_WITH_DETAIL' => 'N',
                    'UPDATE_WITH_DETAIL' => 'N',
                    ),
                ),
			'SECTION_DESCRIPTION_TYPE' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => 'text', ),
			'SECTION_DESCRIPTION' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '', ),
            'SECTION_DETAIL_PICTURE' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    ),
                ),
			'SECTION_XML_ID' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
			'SECTION_CODE' => array (
			    'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'UNIQUE' => 'Y',
                    'TRANSLITERATION' => 'Y',
                    'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L',
                    'TRANS_SPACE' => '_',
                    'TRANS_OTHER' => '_',
                    'TRANS_EAT' => 'Y',
                    'USE_GOOGLE' => 'Y',
                    ),
                ),
            ),
		"CODE" => $iblockCodeOffers,
		"XML_ID" => $iblockXmlIDOffers
	);
	$iblock->Update($IBLOCK_OFFERS_ID, $arFields);
}

if ($IBLOCK_CATALOG_ID)
{
	$iblockCode = "kit_origami_catalog_"  . WIZARD_SITE_ID;
    $iblockXmlID = "kit_origami_catalog_"  . WIZARD_SITE_ID;
	//IBlock fields
	$iblock = new CIBlock;
	$arFields = Array(
		"ACTIVE" => "Y",
		"FIELDS" => array (
		    'IBLOCK_SECTION' => array (
		        'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                ),
            'ACTIVE' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'Y',
                ),
            'ACTIVE_FROM' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'ACTIVE_TO' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'SORT' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'NAME' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                ),
            'PREVIEW_PICTURE' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'FROM_DETAIL' => 'N',
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    'DELETE_WITH_DETAIL' => 'N',
                    'UPDATE_WITH_DETAIL' => 'N',
                    ),
                ),
            'PREVIEW_TEXT_TYPE' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',
                ),
            'PREVIEW_TEXT' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'DETAIL_PICTURE' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    ),
                ),
            'DETAIL_TEXT_TYPE' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',
                ),
            'DETAIL_TEXT' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'XML_ID' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'CODE' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => array (
                    'UNIQUE' => 'Y',
                    'TRANSLITERATION' => 'Y',
                    'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L',
                    'TRANS_SPACE' => '_',
                    'TRANS_OTHER' => '_',
                    'TRANS_EAT' => 'Y',
                    'USE_GOOGLE' => 'Y',
                    ),
                ),
            'TAGS' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'SECTION_NAME' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',
                ),
            'SECTION_PICTURE' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'FROM_DETAIL' => 'N',
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    'DELETE_WITH_DETAIL' => 'N',
                    'UPDATE_WITH_DETAIL' => 'N',
                    ),
                ),
            'SECTION_DESCRIPTION_TYPE' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',
                ),
            'SECTION_DESCRIPTION' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'SECTION_DETAIL_PICTURE' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array (
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    ),
                ),
            'SECTION_XML_ID' => array (
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',
                ),
            'SECTION_CODE' => array (
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => array (
                    'UNIQUE' => 'Y',
                    'TRANSLITERATION' => 'Y',
                    'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L',
                    'TRANS_SPACE' => '_',
                    'TRANS_OTHER' => '_',
                    'TRANS_EAT' => 'Y',
                    'USE_GOOGLE' => 'Y',
                    ),
                ),
            ),
		"CODE" => $iblockCode,
		"XML_ID" => $iblockXmlID
	);
	$iblock->Update($IBLOCK_CATALOG_ID, $arFields);


	$ID_SKU = CCatalog::LinkSKUIBlock($IBLOCK_CATALOG_ID, $IBLOCK_OFFERS_ID);

	if ($IBLOCK_OFFERS_ID)
	{
		$rsCatalogs = CCatalog::GetList(
			array(),
			array('IBLOCK_ID' => $IBLOCK_OFFERS_ID),
			false,
			false,
			array('IBLOCK_ID')
		);
		if ($arCatalog = $rsCatalogs->Fetch())
		{
			CCatalog::Update($IBLOCK_OFFERS_ID,array('PRODUCT_IBLOCK_ID' => $IBLOCK_CATALOG_ID,'SKU_PROPERTY_ID' => $ID_SKU));
		}
		else
		{
			CCatalog::Add(array('IBLOCK_ID' => $IBLOCK_OFFERS_ID, 'PRODUCT_IBLOCK_ID' => $IBLOCK_CATALOG_ID, 'SKU_PROPERTY_ID' => $ID_SKU));
		}
	}

	//user fields for sections
	$arLanguages = Array();
	$rsLanguage = CLanguage::GetList($by, $order, array());
	while($arLanguage = $rsLanguage->Fetch())
		$arLanguages[] = $arLanguage["LID"];

	$arUserFields = array("UF_BROWSER_TITLE", "UF_KEYWORDS", "UF_META_DESCRIPTION");
	foreach ($arUserFields as $userField)
	{
		$arLabelNames = Array();
		foreach($arLanguages as $languageID)
		{
			WizardServices::IncludeServiceLang("property_names.php", $languageID);
			$arLabelNames[$languageID] = GetMessage($userField);
		}

		$arProperty["EDIT_FORM_LABEL"] = $arLabelNames;
		$arProperty["LIST_COLUMN_LABEL"] = $arLabelNames;
		$arProperty["LIST_FILTER_LABEL"] = $arLabelNames;

		$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION', "FIELD_NAME" => $userField));
		if ($arRes = $dbRes->Fetch())
		{
			$userType = new CUserTypeEntity();
			$userType->Update($arRes["ID"], $arProperty);
		}
		//if($ex = $APPLICATION->GetException())
			//$strError = $ex->GetString();
	}

//demo discount


	if(CModule::IncludeModule("sale")){
$ids = [];
$rs = \Bitrix\Iblock\ElementTable::getList(['filter' => ['CODE' => ['tsepnaya_benzinovaya_pila_husqvarna_450e','tsepnaya_benzinovaya_pila_husqvarna_365h']]]);
while($el = $rs->fetch())
{
	$ids[] = $el['ID'];
}

if($ids){
	$objDateTime = new \Bitrix\Main\Type\DateTime("01.01.2019 00:00:00");
	$objDateTime2 = new \Bitrix\Main\Type\DateTime("31.08.2020 00:00:00");
	\CSaleDiscount::Add(
		[
			'LID' => WIZARD_SITE_ID,
			'NAME' => GetMessage("SALE_WIZARD_DISCOUNT_1"),
			'CURRENCY' => 'RUB',
			'DISCOUNT_VALUE' => 20,
			'DISCOUNT_TYPE' => 'P',
			'ACTIVE' => 'Y',
			'SORT' => 100,
			'ACTIVE_FROM' => $objDateTime,
			'ACTIVE_TO' => $objDateTime2,
			'USER_GROUPS' => [2],
			'PRIORITY' => 1,
			'CONDITIONS' => [
				'CLASS_ID' => 'CondGroup',
				'DATA' =>
				[
					'All' => 'AND',
					'True' => 'True',
				],
				'CHILDREN' => [],
			],
			'ACTIONS' =>
			array (
			  'CLASS_ID' => 'CondGroup',
			  'DATA' =>
			  array (
				'All' => 'AND',
			  ),
			  'CHILDREN' =>
			  array (
				0 =>
				array (
				  'CLASS_ID' => 'ActSaleBsktGrp',
				  'DATA' =>
				  array (
					'Type' => 'Discount',
					'Value' => '20',
					'Unit' => 'Perc',
					'Max' => 0,
					'All' => 'OR',
					'True' => 'True',
				  ),
				  'CHILDREN' =>
				  array (
					0 =>
					array (
					),
					1 =>
					array (
					  'CLASS_ID' => 'ActSaleSubGrp',
					  'DATA' =>
					  array (
						'All' => 'AND',
						'True' => 'True',
					  ),
					  'CHILDREN' =>
					  array (
						0 =>
						array (
						  'CLASS_ID' => 'CondIBElement',
						  'DATA' =>
						  array (
							'logic' => 'Equal',
							'value' => $ids,
						  ),
						),
					  ),
					),
				  ),
				),
			  ),
			)
		]
	);
}


$ids = [];
$rs = \Bitrix\Iblock\ElementTable::getList(['filter' => ['CODE' => ['stiralnaya_mashina_indesit_bwse_81082_l_b','stiralnaya_mashina_hotpoint_ariston_vmsl_501_b','stiralnaya_mashina_samsung_ww65k42e00s','stiralnaya_mashina_hansa_crown_whc_1238']]]);
while($el = $rs->fetch())
{
	$ids[] = $el['ID'];
}

$section  = \Bitrix\Iblock\SectionTable::getList(['filter' => ['CODE' => ['stiralnye_mashiny']]])->fetch();

if($ids){
	$objDateTime = new \Bitrix\Main\Type\DateTime("01.01.2019 00:00:00");
	$objDateTime2 = new \Bitrix\Main\Type\DateTime("31.08.2020 00:00:00");
	$fields = [
			'LID' => WIZARD_SITE_ID,
			'NAME' => GetMessage("SALE_WIZARD_DISCOUNT_2"),
			'CURRENCY' => 'RUB',
			'DISCOUNT_VALUE' => 25,
			'DISCOUNT_TYPE' => 'P',
			'ACTIVE' => 'Y',
			'SORT' => 100,
			'ACTIVE_FROM' => $objDateTime,
			'ACTIVE_TO' => $objDateTime2,
			'USER_GROUPS' => [2],
			'PRIORITY' => 1,
			'CONDITIONS' =>
array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
    'True' => 'True',
  ),
  'CHILDREN' =>
[],
),
			'ACTIONS' => array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
  ),
  'CHILDREN' =>
  array (
    0 =>
    array (
      'CLASS_ID' => 'ActSaleBsktGrp',
      'DATA' =>
      array (
        'Type' => 'Discount',
        'Value' => '25',
        'Unit' => 'Perc',
        'Max' => 0,
        'All' => 'OR',
        'True' => 'True',
      ),
      'CHILDREN' =>
      array (
        0 =>
        array (
          'CLASS_ID' => 'ActSaleSubGrp',
          'DATA' =>
          array (
            'All' => 'OR',
            'True' => 'True',
          ),
          'CHILDREN' =>
          array (
            0 =>
            array (
              'CLASS_ID' => 'CondIBSection',
              'DATA' =>
              array (
                'logic' => 'Equal',
                'value' => $section['ID'],
              ),
            ),
          ),
        ),
        1 =>
        array (
          'CLASS_ID' => 'ActSaleSubGrp',
          'DATA' =>
          array (
            'All' => 'AND',
            'True' => 'True',
          ),
          'CHILDREN' =>
          array (
            0 =>
            array (
              'CLASS_ID' => 'CondIBElement',
              'DATA' =>
              array (
                'logic' => 'Equal',
                'value' =>$ids,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
)
		];


	\CSaleDiscount::Add(
		$fields
	);
}


$ids = [];
$rs = \Bitrix\Iblock\ElementTable::getList(['filter' => ['CODE' => ['fotoapparat_so_smennoy_optikoy_panasonic_lumix_dmc_gx8_kit','zerkalnyy_fotoapparat_pentax_k_1_kit','fotoapparat_so_smennoy_optikoy_sony_alpha_ilce_7rm2_kit','kompaktnyy_fotoapparat_nikon_coolpix_p1000','kompaktnyy_fotoapparat_canon_powershot_sx420_is']]]);
while($el = $rs->fetch())
{
	$ids[] = $el['ID'];
}

$section  = \Bitrix\Iblock\SectionTable::getList(['filter' => ['CODE' => ['fotoapparaty']]])->fetch();

if($ids){
	$objDateTime = new \Bitrix\Main\Type\DateTime("01.01.2019 00:00:00");
	$objDateTime2 = new \Bitrix\Main\Type\DateTime("31.08.2020 00:00:00");
	$fields = [
			'LID' => WIZARD_SITE_ID,
			'NAME' => GetMessage("SALE_WIZARD_DISCOUNT_3"),
			'CURRENCY' => 'RUB',
			'DISCOUNT_VALUE' => 10,
			'DISCOUNT_TYPE' => 'P',
			'ACTIVE' => 'Y',
			'SORT' => 100,
			'ACTIVE_FROM' => $objDateTime,
			'ACTIVE_TO' => $objDateTime2,
			'USER_GROUPS' => [2],
			'PRIORITY' => 1,
			'CONDITIONS' =>
array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
    'True' => 'True',
  ),
  'CHILDREN' =>
[],
),
			'ACTIONS' => array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
  ),
  'CHILDREN' =>
  array (
    0 =>
    array (
      'CLASS_ID' => 'ActSaleBsktGrp',
      'DATA' =>
      array (
        'Type' => 'Discount',
        'Value' => '10',
        'Unit' => 'Perc',
        'Max' => 0,
        'All' => 'OR',
        'True' => 'True',
      ),
      'CHILDREN' =>
      array (
        0 =>
        array (
          'CLASS_ID' => 'ActSaleSubGrp',
          'DATA' =>
          array (
            'All' => 'OR',
            'True' => 'True',
          ),
          'CHILDREN' =>
          array (
            0 =>
            array (
              'CLASS_ID' => 'CondIBSection',
              'DATA' =>
              array (
                'logic' => 'Equal',
                'value' => $section['ID'],
              ),
            ),
          ),
        ),
        1 =>
        array (
          'CLASS_ID' => 'ActSaleSubGrp',
          'DATA' =>
          array (
            'All' => 'AND',
            'True' => 'True',
          ),
          'CHILDREN' =>
          array (
            0 =>
            array (
              'CLASS_ID' => 'CondIBElement',
              'DATA' =>
              array (
                'logic' => 'Equal',
                'value' =>$ids,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
)
		];


	\CSaleDiscount::Add(
		$fields
	);
}



$ids = [];
$rs = \Bitrix\Iblock\ElementTable::getList(['filter' => ['CODE' => ['futbolka_m_nsw_tee_swoosh_bmpr_stkr_nike','futbolka_m_nk_dry_tee_hard_work_nike','top_w_nk_tank_vcty_nike','tolstovka_m_nk_dry_hoodie_fz_fleece_nike','tolstovka_envy_lab_c']]]);
while($el = $rs->fetch())
{
	$ids[] = $el['ID'];
}

if($ids){
	$objDateTime = new \Bitrix\Main\Type\DateTime("01.01.2019 00:00:00");
	$objDateTime2 = new \Bitrix\Main\Type\DateTime("31.08.2020 00:00:00");
	$fields = [
			'LID' => WIZARD_SITE_ID,
			'NAME' => GetMessage("SALE_WIZARD_DISCOUNT_4"),
			'CURRENCY' => 'RUB',
			'DISCOUNT_VALUE' => 30,
			'DISCOUNT_TYPE' => 'P',
			'ACTIVE' => 'Y',
			'SORT' => 100,
			'ACTIVE_FROM' => $objDateTime,
			'ACTIVE_TO' => $objDateTime2,
			'USER_GROUPS' => [2],
			'PRIORITY' => 1,
			'CONDITIONS' =>
array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
    'True' => 'True',
  ),
  'CHILDREN' =>
[],
),
			'ACTIONS' => array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
  ),
  'CHILDREN' =>
  array (
    0 =>
    array (
      'CLASS_ID' => 'ActSaleBsktGrp',
      'DATA' =>
      array (
        'Type' => 'Discount',
        'Value' => '30',
        'Unit' => 'Perc',
        'Max' => 0,
        'All' => 'OR',
        'True' => 'True',
      ),
      'CHILDREN' =>
      array (
        0 =>
        array (
          'CLASS_ID' => 'ActSaleSubGrp',
          'DATA' =>
          array (
            'All' => 'AND',
            'True' => 'True',
          ),
          'CHILDREN' =>
          array (
            0 =>
            array (
              'CLASS_ID' => 'CondIBElement',
              'DATA' =>
              array (
                'logic' => 'Equal',
                'value' =>$ids,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
)
		];


	\CSaleDiscount::Add(
		$fields
	);
}


$ids = [];
$rs = \Bitrix\Iblock\ElementTable::getList(['filter' => ['CODE' => ['top_bra_vcty_comp_hbr_bra_nike','tolstovka_m_nsw_n98_jkt_pk_tribute_nike','top_w_nk_miler_tank_nike','tolstovka_m_nsw_club_hoodie_fz_bb_nike']]]);
while($el = $rs->fetch())
{
	$ids[] = $el['ID'];
}

if($ids){
	$objDateTime = new \Bitrix\Main\Type\DateTime("01.01.2019 00:00:00");
	$objDateTime2 = new \Bitrix\Main\Type\DateTime("31.08.2020 00:00:00");
	$fields = [
			'LID' => WIZARD_SITE_ID,
			'NAME' => GetMessage("SALE_WIZARD_DISCOUNT_5"),
			'CURRENCY' => 'RUB',
			'DISCOUNT_VALUE' => 15,
			'DISCOUNT_TYPE' => 'P',
			'ACTIVE' => 'Y',
			'SORT' => 100,
			'ACTIVE_FROM' => $objDateTime,
			'ACTIVE_TO' => $objDateTime2,
			'USER_GROUPS' => [2],
			'PRIORITY' => 1,
			'CONDITIONS' =>
array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
    'True' => 'True',
  ),
  'CHILDREN' =>
[],
),
			'ACTIONS' => array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
  ),
  'CHILDREN' =>
  array (
    0 =>
    array (
      'CLASS_ID' => 'ActSaleBsktGrp',
      'DATA' =>
      array (
        'Type' => 'Discount',
        'Value' => '15',
        'Unit' => 'Perc',
        'Max' => 0,
        'All' => 'OR',
        'True' => 'True',
      ),
      'CHILDREN' =>
      array (
        0 =>
        array (
          'CLASS_ID' => 'ActSaleSubGrp',
          'DATA' =>
          array (
            'All' => 'AND',
            'True' => 'True',
          ),
          'CHILDREN' =>
          array (
            0 =>
            array (
              'CLASS_ID' => 'CondIBElement',
              'DATA' =>
              array (
                'logic' => 'Equal',
                'value' =>$ids,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
)
		];


	\CSaleDiscount::Add(
		$fields
	);
}


$ids = [];
$rs = \Bitrix\Iblock\ElementTable::getList(['filter' => ['CODE' => ['naushniki_philips_shq1300','naushniki_apple_airpods','naushniki_beats_studio_3_wireless','naushniki_marshall_mode_eq','naushniki_jbl_everest_310','chekhol_apple_dlya_apple_iphone_xs','chekhol_knizhka_tetded_dlya_google_google_pixel','chekhol_luxo_dlya_huawei','chekhol_gosso_dlya_oneplus','chekhol_samsung_dlya_samsung_galaxy_s9','chekhol_gosso_dlya_xiaomi']]]);
while($el = $rs->fetch())
{
	$ids[] = $el['ID'];
}

if($ids){
	$objDateTime = new \Bitrix\Main\Type\DateTime("01.01.2019 00:00:00");
	$objDateTime2 = new \Bitrix\Main\Type\DateTime("31.08.2020 00:00:00");
	$fields = [
			'LID' => WIZARD_SITE_ID,
			'NAME' => GetMessage("SALE_WIZARD_DISCOUNT_6"),
			'CURRENCY' => 'RUB',
			'DISCOUNT_VALUE' => 15,
			'DISCOUNT_TYPE' => 'P',
			'ACTIVE' => 'Y',
			'SORT' => 100,
			'ACTIVE_FROM' => $objDateTime,
			'ACTIVE_TO' => $objDateTime2,
			'USER_GROUPS' => [2],
			'PRIORITY' => 1,
			'CONDITIONS' =>
array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
    'True' => 'True',
  ),
  'CHILDREN' =>
[],
),
			'ACTIONS' => array (
  'CLASS_ID' => 'CondGroup',
  'DATA' =>
  array (
    'All' => 'AND',
  ),
  'CHILDREN' =>
  array (
    0 =>
    array (
      'CLASS_ID' => 'GiftCondGroup',
      'DATA' =>
      array (
        'All' => 'AND',
      ),
      'CHILDREN' =>
      array (
        0 =>
        array (
          'CLASS_ID' => 'GifterCondIBElement',
          'DATA' =>
          array (
            'Type' => 'one',
            'Value' => $ids,
          ),
        ),
      ),
    ),
  ),
)
		];


	\CSaleDiscount::Add(
		$fields
	);
}
	}
//precet
	$dbSite = CSite::GetByID(WIZARD_SITE_ID);
	if($arSite = $dbSite -> Fetch())
		$lang = $arSite["LANGUAGE_ID"];

	$dbProperty = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_CATALOG_ID, "CODE"=>"SALELEADER"));
	$arFields = array();
	while($arProperty = $dbProperty->GetNext())
	{
		$arFields["find_el_property_".$arProperty["ID"]] = "";
	}
	$dbProperty = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_CATALOG_ID, "CODE"=>"NEWPRODUCT"));
	while($arProperty = $dbProperty->GetNext())
	{
		$arFields["find_el_property_".$arProperty["ID"]] = "";
	}
	$dbProperty = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_CATALOG_ID, "CODE"=>"SPECIALOFFER"));
	while($arProperty = $dbProperty->GetNext())
	{
		$arFields["find_el_property_".$arProperty["ID"]] = "";
	}
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/interface/admin_lib.php");
	CAdminFilter::AddPresetToBase( array(
			"NAME" => GetMessage("WIZ_PRECET"),
			"FILTER_ID" => "tbl_product_admin_".md5($iblockType.".".$IBLOCK_CATALOG_ID)."_filter",
			"LANGUAGE_ID" => $lang,
			"FIELDS" => $arFields
		)
	);
	CUserOptions::SetOption("filter", "tbl_product_admin_".md5($iblockType.".".$IBLOCK_CATALOG_ID)."_filter", array("rows" => "find_el_name, find_el_active, find_el_timestamp_from, find_el_timestamp_to"), true);

	CAdminFilter::SetDefaultRowsOption("tbl_product_admin_".md5($iblockType.".".$IBLOCK_CATALOG_ID)."_filter", array("miss-0","IBEL_A_F_PARENT"));

}
?>