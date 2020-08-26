<?php
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
	die();

if( !CModule::IncludeModule( 'sale' ) )
	return;

use Bitrix\Catalog, Bitrix\Main, Bitrix\Sale, Bitrix\Sale\OrderStatus, Bitrix\Sale\DeliveryStatus, Bitrix\Main\Localization\Loc;

$module = 'kit.origami';

WizardServices::IncludeServiceLang( "step1.php", 'ru' );

//if( COption::GetOptionString( "kit.origami", "wizard_installed", "N", WIZARD_SITE_ID ) != "Y" || WIZARD_INSTALL_DEMO_DATA )
//{

	$saleConverted15 = COption::GetOptionString( "main", "~sale_converted_15", "" ) == "Y";
	$arLanguages = Array();
	$rsLanguage = CLanguage::GetList( $by, $order, array() );
	while ( $arLanguage = $rsLanguage->Fetch() )
		$arLanguages[] = $arLanguage["LID"];

	$arPersonTypeNames = array();
	$dbPerson = CSalePersonType::GetList( array(), array(
			"LID" => WIZARD_SITE_ID
	) );

	while ( $arPerson = $dbPerson->Fetch() )
	{
		$arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];
	}

	$arGeneralInfo["personType"]["fiz"] = array_search( GetMessage( "SALE_WIZARD_PERSON_1" ), $arPersonTypeNames );
	$arGeneralInfo["personType"]["ur"] = array_search( GetMessage( "SALE_WIZARD_PERSON_2" ), $arPersonTypeNames );
	$arGeneralInfo["personType"]["ip"] = array_search( GetMessage( "SALE_WIZARD_PERSON_4" ), $arPersonTypeNames );

	$rs = \Bitrix\Sale\Internals\PaySystemActionTable::getList( array(
			'filter' => array(
					'ACTION_FILE' => array(
							'cash',
							'bill',
							'sber',
							'paypal'
					)
			)
	) );
	while ( $pay = $rs->fetch() )
	{
		$arGeneralInfo["paySystem"][$pay['ACTION_FILE']][$pay['PERSON_TYPE_ID']] = $pay['ID'];
	}

	$dbSite = CSite::GetByID( WIZARD_SITE_ID );
	if( $arSite = $dbSite->Fetch() )
		$lang = $arSite["LANGUAGE_ID"];

	$shopLocalization = $wizard->GetVar( "shopLocalization" );

	if( $shopLocalization == "ru" )
	{
		$shopLocation = $wizard->GetVar( "shopLocation" );
	}
	elseif( $shopLocalization == "ua" )
	{
		$shopLocation = $wizard->GetVar( "shopLocation_ua" );
	}
	else
	{
		$shopLocation = GetMessage( "WIZ_CITY" );
	}

	if( strlen( $shopLocation ) )
	{
		// get city with name equal to $shopLocation
		$item = \Bitrix\Sale\Location\LocationTable::getList( array(
				'filter' => array(
						'=NAME.LANGUAGE_ID' => $lang,
						'=NAME.NAME' => $shopLocation,
						'=TYPE.CODE' => 'CITY'
				),
				'select' => array(
						'CODE'
				)
		) )->fetch();

		if( $item )
			$location = $item['CODE'];
	}

	$defCurrency = "EUR";
	if( $lang == "ru" )
	{
		if( $shopLocalization == "ua" )
			$defCurrency = "UAH";
		elseif( $shopLocalization == "bl" )
			$defCurrency = "BYR";
		else
			$defCurrency = "RUB";
	}
	elseif( $lang == "en" )
	{
		$defCurrency = "USD";
	}

	if( $saleConverted15 )
	{
		$orderPaidStatus = 'P';
		$deliveryAssembleStatus = 'DA';
		$deliveryGoodsStatus = 'DG';
		$deliveryTransportStatus = 'DT';
		$deliveryShipmentStatus = 'DS';

		$statusIds = array(
				$orderPaidStatus,
				$deliveryAssembleStatus,
				$deliveryGoodsStatus,
				$deliveryTransportStatus,
				$deliveryShipmentStatus
		);

		$statusLanguages = array();

		foreach( $arLanguages as $langID )
		{
			Loc::loadLanguageFile( $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/lib/status.php', $langID );

			foreach( $statusIds as $statusId )
			{
				if( $statusName = Loc::getMessage( "SALE_STATUS_{$statusId}" ) )
				{
					$statusLanguages[$statusId][] = array(
							'LID' => $langID,
							'NAME' => $statusName,
							'DESCRIPTION' => Loc::getMessage( "SALE_STATUS_{$statusId}_DESCR" )
					);
				}
			}
		}

		OrderStatus::install( array(
				'ID' => $orderPaidStatus,
				'SORT' => 150,
				'NOTIFY' => 'Y',
				'LANG' => $statusLanguages[$orderPaidStatus]
		) );
		CSaleStatus::CreateMailTemplate( $orderPaidStatus );

		DeliveryStatus::install( array(
				'ID' => $deliveryAssembleStatus,
				'SORT' => 310,
				'NOTIFY' => 'Y',
				'LANG' => $statusLanguages[$deliveryAssembleStatus]
		) );

		DeliveryStatus::install( array(
				'ID' => $deliveryGoodsStatus,
				'SORT' => 320,
				'NOTIFY' => 'Y',
				'LANG' => $statusLanguages[$deliveryGoodsStatus]
		) );

		DeliveryStatus::install( array(
				'ID' => $deliveryTransportStatus,
				'SORT' => 330,
				'NOTIFY' => 'Y',
				'LANG' => $statusLanguages[$deliveryTransportStatus]
		) );

		DeliveryStatus::install( array(
				'ID' => $deliveryShipmentStatus,
				'SORT' => 340,
				'NOTIFY' => 'Y',
				'LANG' => $statusLanguages[$deliveryShipmentStatus]
		) );
	}
	else
	{
		$bStatusP = false;
		$dbStatus = CSaleStatus::GetList( Array(
				"SORT" => "ASC"
		) );
		while ( $arStatus = $dbStatus->Fetch() )
		{
			$arFields = Array();
			foreach( $arLanguages as $langID )
			{
				WizardServices::IncludeServiceLang( "step1.php", $langID );
				$arFields["LANG"][] = Array(
						"LID" => $langID,
						"NAME" => GetMessage( "WIZ_SALE_STATUS_" . $arStatus["ID"] ),
						"DESCRIPTION" => GetMessage( "WIZ_SALE_STATUS_DESCRIPTION_" . $arStatus["ID"] )
				);
			}
			$arFields["ID"] = $arStatus["ID"];
			CSaleStatus::Update( $arStatus["ID"], $arFields );
			if( $arStatus["ID"] == "P" )
				$bStatusP = true;
		}
		if( !$bStatusP )
		{
			$arFields = Array(
					"ID" => "P",
					"SORT" => 150
			);
			foreach( $arLanguages as $langID )
			{
				WizardServices::IncludeServiceLang( "step1.php", $langID );
				$arFields["LANG"][] = Array(
						"LID" => $langID,
						"NAME" => GetMessage( "WIZ_SALE_STATUS_P" ),
						"DESCRIPTION" => GetMessage( "WIZ_SALE_STATUS_DESCRIPTION_P" )
				);
			}

			$ID = CSaleStatus::Add( $arFields );
			if( $ID !== '' )
			{
				CSaleStatus::CreateMailTemplate( $ID );
			}
		}
	}

	if( CModule::IncludeModule( "currency" ) )
	{
		$dbCur = CCurrency::GetList( $by = "currency", $o = "asc" );
		while ( $arCur = $dbCur->Fetch() )
		{
			if( $lang == "ru" )
				CCurrencyLang::Update( $arCur["CURRENCY"], $lang, array(
						"DECIMALS" => 2,
						"HIDE_ZERO" => "Y"
				) );
			elseif( $arCur["CURRENCY"] == "EUR" )
				CCurrencyLang::Update( $arCur["CURRENCY"], $lang, array(
						"DECIMALS" => 2,
						"FORMAT_STRING" => "&euro;#"
				) );
		}
	}
	WizardServices::IncludeServiceLang( "step1.php", $lang );

	if( CModule::IncludeModule( "catalog" ) )
	{
		$dbVat = CCatalogVat::GetListEx( array(), array(
				'RATE' => 0
		), false, false, array(
				'ID',
				'RATE'
		) );
		if( !($dbVat->Fetch()) )
		{
			$arF = array(
					"ACTIVE" => "Y",
					"SORT" => "100",
					"NAME" => GetMessage( "WIZ_VAT_1" ),
					"RATE" => 0
			);
			CCatalogVat::Add( $arF );
		}
		$dbVat = CCatalogVat::GetListEx( array(), array(
				'RATE' => GetMessage( "WIZ_VAT_2_VALUE" )
		), false, false, array(
				'ID',
				'RATE'
		) );
		if( !($dbVat->Fetch()) )
		{
			$arF = array(
					"ACTIVE" => "Y",
					"SORT" => "200",
					"NAME" => GetMessage( "WIZ_VAT_2" ),
					"RATE" => GetMessage( "WIZ_VAT_2_VALUE" )
			);
			CCatalogVat::Add( $arF );
		}
		$dbResultList = CCatalogGroup::GetList( array(), array(
				"CODE" => "BASE"
		) );
		if( $arRes = $dbResultList->Fetch() )
		{
			$arFields = Array();
			foreach( $arLanguages as $langID )
			{
				WizardServices::IncludeServiceLang( "step1.php", $langID );
				$arFields["USER_LANG"][$langID] = GetMessage( "WIZ_PRICE_NAME" );
			}
			$arFields["BASE"] = "Y";
			if( $wizard->GetVar( "installPriceBASE" ) == "Y" )
			{
				$db_res = CCatalogGroup::GetGroupsList( array(
						"CATALOG_GROUP_ID" => '1',
						"BUY" => "Y"
				) );
				if( $ar_res = $db_res->Fetch() )
				{
					$wizGroupId[] = $ar_res['GROUP_ID'];
				}
				$wizGroupId[] = 1;
				$wizGroupId[] = 2;
				$arFields["USER_GROUP"] = $wizGroupId;
				$arFields["USER_GROUP_BUY"] = $wizGroupId;
			}
			CCatalogGroup::Update( $arRes["ID"], $arFields );
		}
	}

	// making orders
	function __MakeOrder(array $arData, array $productFilter, $prdCnt = 1)
	{
		static $catalogIncluded = null;
		static $saleIncluded = null;

		if( empty( $arData ) || empty( $productFilter ) )
			return false;

		$prdCnt = ( int ) $prdCnt;
		if( $prdCnt < 1 || $prdCnt > 20 )
			$prdCnt = 1;

		if( $catalogIncluded === null )
			$catalogIncluded = Main\Loader::includeModule( 'catalog' );
		if( !$catalogIncluded )
			return false;
		if( $saleIncluded === null )
			$saleIncluded = Main\Loader::includeModule( 'sale' );
		if( !$saleIncluded )
			return false;

		$arPrd = array();
		$dbItem = CIBlockElement::GetList( array(), $productFilter, false, array(
				"nTopCount" => 100
		), array(
				"ID",
				"IBLOCK_ID",
				"NAME"
		) );
		while ( $arItem = $dbItem->Fetch() )
			$arPrd[] = $arItem;
		unset( $arItem, $dbItem );

		if( empty( $arPrd ) )
			return false;

		$order = Sale\Order::create( $arData['SITE_ID'], $arData['USER_ID'], $arData['CURRENCY'] );
		$order->setPersonTypeId( $arData['PERSON_TYPE_ID'] );
		if( !empty( $arData['PROPS'] ) )
		{
			$propertyValues = array();
			$propertyCollection = $order->getPropertyCollection();
			/** @var Sale\PropertyValue $property */
			foreach( $propertyCollection as $property )
			{
				if( $property->isUtil() )
					continue;

				$propertyId = $property->getPropertyId();
				if( !isset( $arData['PROPS'][$propertyId] ) && $property->isRequired() )
					return false;

				$propertyValues[$propertyId] = $arData['PROPS'][$propertyId];
				unset( $propertyId );
			}
			unset( $property );
			if( !empty( $propertyValues ) )
			{
				$result = $propertyCollection->setValuesFromPost( array(
						'PROPERTIES' => $propertyValues
				), array() );
				if( !$result->isSuccess() )
					return false;
				unset( $result );
			}
			unset( $propertyValues );
		}

		$basket = Sale\Basket::create( $arData['SITE_ID'] );
		$basket->setFUserId( $arData['FUSER_ID'] );

		while ( $prdCnt > 0 )
		{
			$product = $arPrd[mt_rand( 0, 99 )];
			$item = $basket->createItem( 'catalog', $product['ID'] );

			$result = $item->setFields( array(
					'NAME' => $product['NAME'],
					'QUANTITY' => 1,
					'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider'
			) );

			if( !$result->isSuccess() )
				return false;

			$prdCnt--;
			unset( $result, $product );
		}

		$result = $order->setBasket( $basket );
		if( !$result->isSuccess() )
			return false;
		unset( $result );

		$shipmentCollection = $order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem();
		$shipmentItemCollection = $shipment->getShipmentItemCollection();

		/** @var Sale\BasketItem $basketItem */
		foreach( $order->getBasket() as $basketItem )
		{
			/** @var Sale\ShipmentItem $shipmentItem */
			$shipmentItem = $shipmentItemCollection->createItem( $basketItem );
			$result = $shipmentItem->setQuantity( $basketItem->getQuantity() );
			if( !$result->isSuccess() )
				return false;
			unset( $result );
		}
		unset( $basketItem );

		$emptyDeliveryServiceId = Sale\Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId();
		$result = $shipment->setField( 'DELIVERY_ID', $emptyDeliveryServiceId );
		if( !$result->isSuccess() )
			return false;
		unset( $result );

		$paySystemObject = Sale\PaySystem\Manager::getObjectById( $arData['PAY_SYSTEM_ID'] );
		if( $paySystemObject === null )
			return false;
		$paymentCollection = $order->getPaymentCollection();
		/** @var \Bitrix\Sale\Payment $payment */
		$payment = $paymentCollection->createItem( $paySystemObject );

		$discounts = $order->getDiscount();
		$result = $discounts->calculate();
		if( !$result->isSuccess() )
			return false;
		unset( $result );

		$result = $payment->setFields( array(
				'SUM' => $order->getPrice(),
				'CURRENCY' => $order->getCurrency()
		) );
		if( !$result->isSuccess() )
			return false;
		unset( $result );

		$result = $order->save();
		if( !$result->isSuccess() )
			return false;
		unset( $result );

		return $order->getId();
	}

	$personType = $arGeneralInfo["personType"]["ur"];
	if( IntVal( $arGeneralInfo["personType"]["fiz"] ) > 0 )
		$personType = $arGeneralInfo["personType"]["fiz"];
	if( IntVal( $personType ) <= 0 )
	{
		$dbPerson = CSalePersonType::GetList( array(), Array(
				"LID" => WIZARD_SITE_ID
		) );
		if( $arPerson = $dbPerson->Fetch() )
		{
			$personType = $arPerson["ID"];
		}
	}
	if( IntVal( $arGeneralInfo["paySystem"]["cash"][$personType] ) > 0 )
		$paySystem = $arGeneralInfo["paySystem"]["cash"][$personType];
	elseif( IntVal( $arGeneralInfo["paySystem"]["bill"][$personType] ) > 0 )
		$paySystem = $arGeneralInfo["paySystem"]["bill"][$personType];
	elseif( IntVal( $arGeneralInfo["paySystem"]["bill"][$personType] ) > 0 )
		$paySystem = $arGeneralInfo["paySystem"]["sber"][$personType];
	elseif( IntVal( $arGeneralInfo["paySystem"]["paypal"][$personType] ) > 0 )
		$paySystem = $arGeneralInfo["paySystem"]["paypal"][$personType];
	else
	{
		$dbPS = \Bitrix\Sale\PaySystem\Manager::getList( array() );
		if( $arPS = $dbPS->fetch() )
			$paySystem = $arPS["ID"];
	}

	if( \Bitrix\Main\Config\Option::get( 'sale', 'sale_locationpro_migrated', '' ) == 'Y' )
	{
		if( !strlen( $location ) )
		{
			// get first found
			$item = \Bitrix\Sale\Location\LocationTable::getList( array(
					'limit' => 1,
					'select' => array(
							'CODE'
					)
			) )->fetch();
			if( $item )
				$location = $item['CODE'];
		}
	}
	else
	{
		if( IntVal( $location ) <= 0 )
		{
			$dbLocation = CSaleLocation::GetList( Array(
					"ID" => "ASC"
			), Array(
					"LID" => $lang
			) );
			if( $arLocation = $dbLocation->Fetch() )
			{
				$location = $arLocation["ID"];
			}
		}
	}

	if( empty( $arGeneralInfo["properies"][$personType] ) )
	{
		$dbProp = CSaleOrderProps::GetList( array(), Array(
				"PERSON_TYPE_ID" => $personType
		) );
		while ( $arProp = $dbProp->Fetch() )
			$arGeneralInfo["properies"][$personType][$arProp["CODE"]] = $arProp;
	}

	$LeaveOrders = $wizard->GetVar( "LEAVE_ORDERS" );

	if( $LeaveOrders != 'Y' )
	{

		$db_sales = CSaleOrder::GetList( array(
				"DATE_INSERT" => "ASC"
		), array(
				"LID" => WIZARD_SITE_ID
		), false, false, array(
				"ID"
		) );
		while ( $ar_sales = $db_sales->Fetch() )
		{
			CSaleOrder::Delete( $ar_sales["ID"] );
		}
	}

	$productFilter = array(
			"=IBLOCK_TYPE" => "b2bs_catalog",
			"=IBLOCK_SITE_ID" => WIZARD_SITE_ID,
			"PROPERTY_NEWPRODUCT" => false,
			"ACTIVE" => "Y",
			"CATALOG_AVAILABLE" => "Y",
			"CATALOG_TYPE" => Catalog\ProductTable::TYPE_OFFER
	);

	if($module == 'kit.origami')
	{
		$productFilter["=IBLOCK_TYPE"] = 'mrs_catalog';
	}
	if($module == 'kit.origami')
	{
		$productFilter["=IBLOCK_TYPE"] = 'mrs_catalog';
	}


	$arData = Array(
			"SITE_ID" => WIZARD_SITE_ID,
			"PERSON_TYPE_ID" => $personType,
			"CURRENCY" => $defCurrency,
			"USER_ID" => 1,
			"PAY_SYSTEM_ID" => $paySystem,
			// "PRICE_DELIVERY" => "0",
			// "DELIVERY_ID" => "",
			"PROPS" => Array()
	);
	foreach( $arGeneralInfo["properies"][$personType] as $key => $val )
	{
		$arProp = Array(
				"ID" => $val["ID"],
				"NAME" => $val["NAME"],
				"CODE" => $val["CODE"],
				"VALUE" => ""
		);

		if( $key == "FIO" || $key == "CONTACT_PERSON" )
			$arProp["VALUE"] = GetMessage( "WIZ_ORD_FIO" );
		elseif( $key == "ADDRESS" || $key == "COMPANY_ADR" )
			$arProp["VALUE"] = GetMessage( "WIZ_ORD_ADR" );
		elseif( $key == "EMAIL" )
			$arProp["VALUE"] = "example@example.com";
		elseif( $key == "PHONE" )
			$arProp["VALUE"] = "8 495 2312121";
		elseif( $key == "ZIP" )
			$arProp["VALUE"] = "101000";
		elseif( $key == "LOCATION" )
			$arProp["VALUE"] = $location;
		elseif( $key == "CITY" )
			$arProp["VALUE"] = $shopLocation;
		elseif( $key == "CONFIDENTIAL" )
			$arProp["VALUE"] = 'Y';
		$arData["PROPS"][] = $arProp;
	}
	$orderID = __MakeOrder( $arData, $productFilter, 3 );
	if( $orderID )
	{
		CSaleOrder::DeliverOrder( $orderID, "Y" );
		CSaleOrder::PayOrder( $orderID, "Y" );
		CSaleOrder::StatusOrder( $orderID, "F" );
	}
	$orderID = __MakeOrder( $arData, $productFilter, 4 );
	if( $orderID )
	{
		CSaleOrder::DeliverOrder( $orderID, "Y" );
		CSaleOrder::PayOrder( $orderID, "Y" );
		CSaleOrder::StatusOrder( $orderID, "F" );
	}
	$orderID = __MakeOrder( $arData, $productFilter, 2 );
	if( $orderID )
	{
		CSaleOrder::PayOrder( $orderID, "Y" );
		CSaleOrder::StatusOrder( $orderID, "P" );
	}
	$orderID = __MakeOrder( $arData, $productFilter, 1 );
	$orderID = __MakeOrder( $arData, $productFilter, 1 );
	if( $orderID )
	{
		CSaleOrder::CancelOrder( $orderID, "Y" );
	}

	if( $module == 'kit.b2bshop' )
	{

		$arIMAGE = CFile::MakeFileArray( $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/avatar.jpg" );
		$arIMAGE["MODULE_ID"] = "main";

		$groups = array();
		$rs = \Bitrix\Main\GroupTable::getList( array(
				'filter' => array(
						'STRING_ID' => array(
								'OPT1',
								'OPT2'
						)
				)
		) );
		while ( $group = $rs->fetch() )
		{
			$groups[] = $group['ID'];
		}

		if( $groups )
		{
			$user = new CUser();
			$arFields = Array(
					"NAME" => "demo",
					"LAST_NAME" => "demo",
					"EMAIL" => "demo@demo.ru",
					"LOGIN" => "demo@demo.ru",
					"LID" => "ru",
					"ACTIVE" => "Y",
					"GROUP_ID" => $groups,
					"PASSWORD" => "demo123456",
					"CONFIRM_PASSWORD" => "demo123456",
					"PERSONAL_PHOTO" => $arIMAGE,
					"PERSONAL_PHONE" => "+7 (495) 278-08-54",
					"WORK_COMPANY" => GetMessage( "WIZ_ORD_COMPANY" ),
					"WORK_PHONE" => '8 (495) 988-46-18',
					"WORK_CITY" => GetMessage( "WIZ_ORD_CITY" )
			);

			$personType = 0;
			if( $arGeneralInfo["personType"]["ur"] > 0 )
			{
				$personType = $arGeneralInfo["personType"]["ur"];
			}
			elseif( $arGeneralInfo["personType"]["ip"] > 0 )
			{
				$personType = $arGeneralInfo["personType"]["ip"];
			}

			if( $personType > 0 )
			{
				$ID = $user->Add( $arFields );
				if( intval( $ID ) > 0 )
				{
					$arData['USER_ID'] = $ID;
					$arData['PERSON_TYPE_ID'] = $personType;

					if( empty( $arGeneralInfo["properies"][$personType] ) )
					{
						$dbProp = CSaleOrderProps::GetList( array(), Array(
								"PERSON_TYPE_ID" => $personType
						) );
						while ( $arProp = $dbProp->Fetch() )
						{
							$arGeneralInfo["properies"][$personType][$arProp["CODE"]] = $arProp;
						}
					}

					$arData["PROPS"] = array();

					foreach( $arGeneralInfo["properies"][$personType] as $key => $val )
					{
						$arProp = Array(
								"ID" => $val["ID"],
								"NAME" => $val["NAME"],
								"CODE" => $val["CODE"],
								"VALUE" => ""
						);

						if( $key == "COMPANY" )
							$arProp["VALUE"] = GetMessage( "WIZ_ORD_COMPANY" );
						elseif( $key == "NAME" )
							$arProp["VALUE"] = 'demo';
						elseif( $key == "LAST_NAME" )
							$arProp["VALUE"] = 'demo';
						elseif( $key == "UR_ADDRESS" || $key == "POST_ADDRESS" )
							$arProp["VALUE"] = GetMessage( "WIZ_ORD_ADDRESS" );
						elseif( $key == "INN" )
							$arProp["VALUE"] = '123456789';
						elseif( $key == "KPP" )
							$arProp["VALUE"] = "123456789";
						elseif( $key == "PHONE" )
							$arProp["VALUE"] = "+7 (495) 278-08-54";
						elseif( $key == "UR_ZIP" || $key == "POST_ZIP" )
							$arProp["VALUE"] = "101000";
						elseif( $key == "LOCATION" )
							$arProp["VALUE"] = $location;
						elseif( $key == "UR_CITY" || $key == "POST_CITY" )
							$arProp["VALUE"] = GetMessage( "WIZ_ORD_CITY" );
						elseif( $key == "EMAIL" )
							$arProp["VALUE"] = "demo@demo.ru";
						elseif( $key == "CONFIDENTIAL" )
							$arProp["VALUE"] = 'Y';
						elseif( $key == "EQ_POST" )
							$arProp["VALUE"] = 'Y';

						$arData["PROPS"][] = $arProp;
					}

					$orderID = __MakeOrder( $arData, $productFilter, 3 );
					if( $orderID )
					{
						CSaleOrder::DeliverOrder( $orderID, "Y" );
						CSaleOrder::PayOrder( $orderID, "Y" );
						CSaleOrder::StatusOrder( $orderID, "F" );
					}
					$orderID = __MakeOrder( $arData, $productFilter, 4 );
					if( $orderID )
					{
						CSaleOrder::DeliverOrder( $orderID, "Y" );
						CSaleOrder::PayOrder( $orderID, "Y" );
						CSaleOrder::StatusOrder( $orderID, "F" );
					}
					$orderID = __MakeOrder( $arData, $productFilter, 2 );
					if( $orderID )
					{
						CSaleOrder::PayOrder( $orderID, "Y" );
						CSaleOrder::StatusOrder( $orderID, "P" );
					}
					$orderID = __MakeOrder( $arData, $productFilter, 1 );
					$orderID = __MakeOrder( $arData, $productFilter, 1 );
					if( $orderID )
					{
						CSaleOrder::CancelOrder( $orderID, "Y" );
					}

					$idUserProps = \CSaleOrderUserProps::add(
							array(
									'NAME' => '123456789',
									'USER_ID' => $ID,
									'PERSON_TYPE_ID' => $personType
							) );
					if( $idUserProps )
					{
						\CSaleOrderUserProps::DoSaveUserProfile( $ID, $idUserProps, '123456789', $personType, $orderID);
					}
				}
			}
		}
	}

	CAgent::RemoveAgent( "CSaleProduct::RefreshProductList();", "sale" );
	CAgent::AddAgent( "CSaleProduct::RefreshProductList();", "sale", "N", 60 * 60 * 24 * 4, "", "Y" );?>