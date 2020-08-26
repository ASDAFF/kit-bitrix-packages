<?
IncludeModuleLangFile(__FILE__);
Class CKitOrderphone
{
	private $userID = 0;
	public $error = array();
	public $order = 0;
	private $fields = [];
	private $arParams = array();
	private $currency = "";
	private $orderPrice = 0;
	CONST EVENT_ADD_ORDER = "KIT_NEW_ORDER_PHONE";
	
	function CKitOrderphone($arParams, $fields)
	{
		$this->arParams = $arParams;
		$this->siteID = isset($arParams["SITE_ID"])?$arParams["SITE_ID"]:SITE_ID;
		$this->fields = $fields;
	}
	function GetDemo()
	{
		$module_id = 'kit.orderphone';
		$module_status = CModule::IncludeModuleEx($module_id);
		if($module_status == '3')
		{   echo GetMessage('kit_error_demo');
		return false;
		}
		return true;
	}
	
	function CheckFields()
	{
		if($this->arParams["SEND_EVENT"]=="Y")
		{
			$arFilter = array(
					"TYPE_ID" => self::EVENT_ADD_ORDER,
					"LID"     => "ru"
			);
			$arET = CEventType::GetList($arFilter)->Fetch();
			
			if(!$arET)
			{
				$et = new CEventType;
				$et->Add(array(
						"LID"           => "ru",
						"EVENT_NAME"    => self::EVENT_ADD_ORDER,
						"NAME"          => GetMessage('kit_new_order_phone_order_name'),
						"DESCRIPTION"   => ""
				));
				$arFields = array(
						"ACTIVE" => "Y",
						"EVENT_NAME" => self::EVENT_ADD_ORDER,
						"LID" => $this->siteID,
						"EMAIL_FROM" => "#EMAIL_FROM#",
						"EMAIL_TO" => "#EMAIL_TO#",
						//"BBC" => "#BBC#",
						"SUBJECT" => GetMessage('kit_new_order_phone_order_name'),
						"BODY_TYPE" => "text",
						"MESSAGE" => GetMessage('kit_new_order_phone_order_message')
				);//return;
				$mes = new CEventMessage;
				$mesID = $mes->Add($arFields);
			}
		}
	}
	
	function StartAjax()
	{
		global $USER, $APPLICATION;
		
		if(!$this->GetDemo()){
			return false;
		}
		
		if($this->fields['EMAIL'])
		{
			$email = $this->fields['EMAIL'];
		}
		else
		{
			$email = str_replace(array(" ", "(", ")", "+", "_", "*", "-"), "", $this->fields['PHONE']);
			$email = preg_replace("/\D/", "", $email);
			$email.="@phone.ru";
		}
		
		if (!$USER->IsAuthorized())
		{
			if($this->arParams["SELECT_USER"]=="new")
			{
				COption::RemoveOption("kit.orderphone", "KIT_ORDER_USER");
				$filter = Array
				(
						"LOGIN" => $email."%",
						);
				$rsUsers = CUser::GetList(($by="date_register"), ($order="desc"), $filter, array("SELECT"=>array("ID")));
				while($arUsers = $rsUsers->Fetch())
				{
					$this->userID = $arUsers["ID"];
				}
				
				if(!$this->userID)
				{
					$this->AddUser();
				}
			}
			elseif($this->arParams["SELECT_USER"]=="isset")
			{
				if(strpos('@phone.ru',$email) !== false)
				{
					COption::RemoveOption("kit.orderphone", "KIT_ORDER_USER");
					$filter = Array
					(
							"LOGIN" => $email."%",
							);
					$rsUsers = CUser::GetList(($by="date_register"), ($order="desc"), $filter, array("SELECT"=>array("ID")));
					while($arUsers = $rsUsers->Fetch())
					{
						$this->userID = $arUsers["ID"];
					}
					
					if(!$this->userID)
					{
						$this->AddUser();
					}
				}
				else
				{
					$filter = Array
					(
							"EMAIL" => $email,
							);
					$user = CUser::GetList(($by="date_register"), ($order="desc"), $filter, array("SELECT"=>array("ID")))->Fetch();
					if($user['ID'])
					{
						$this->error[] = GetMessage('SOP_ORDER_ERROR_ISSET');
					}
					else
					{
						$this->AddUser();
					}
				}
			}
			else
			{
				$this->userID = COption::GetOptionString("kit.orderphone", "KIT_ORDER_USER");
				if(!$this->userID)
				{
					$this->AddUser();
					COption::SetOptionString("kit.orderphone", "KIT_ORDER_USER", $this->userID);
					
				}
			}
			$USER->Authorize($this->userID);
			$noAuth = true;
		}
		else{
			$userID = $USER->GetID();
			$this->userID = $userID;
		}
		
		if(count($this->error)>0) return false;
		if(!isset($this->arParams["PRODUCT_ID"]) || empty($this->arParams["PRODUCT_ID"]))
			$this->AddOrder();
			else
				$this->AddItemOrder();
				if($noAuth) $USER->Logout();
				
				$this->AddOrderProps();
				
	}
	
	function AddOrderProps()
	{
		if(isset($this->arParams["order_props"]) && is_array($this->arParams["order_props"]))
			foreach($this->arParams["order_props"] as $id=>$arParamsProp)
			{
				$orderProp[] = $id;
			}
		$arFilter["ID"] = $orderProp;
		if(isset($this->arParams["ORDER_TEL_PROP"]) && !empty($this->arParams["ORDER_TEL_PROP"]))
		{
			$arFilter["ID"][] = $this->arParams["ORDER_TEL_PROP"];
			$this->arParams["order_props"][$this->arParams["ORDER_TEL_PROP"]] = $this->fields['PHONE'];
		}
		if(isset($this->arParams["ORDER_NAME_PROP"]) && !empty($this->arParams["ORDER_NAME_PROP"]))
		{
			$arFilter["ID"][] = $this->arParams["ORDER_NAME_PROP"];
			$this->arParams["order_props"][$this->arParams["ORDER_NAME_PROP"]] = $this->fields['NAME'];
		}
		if(isset($this->arParams["ORDER_EMAIL_PROP"]) && !empty($this->arParams["ORDER_EMAIL_PROP"]))
		{
			$arFilter["ID"][] = $this->arParams["ORDER_EMAIL_PROP"];
			$this->arParams["order_props"][$this->arParams["ORDER_EMAIL_PROP"]] = $this->fields['EMAIL'];
		}
		
		
		if(empty($arFilter["ID"])) return;
		$dbOrderProps = CSaleOrderProps::GetList(
				array("SORT" => "ASC"),
				$arFilter,
				false,
				false,
				array("ID", "NAME", "CODE")
				);
		while ($arOrderProps = $dbOrderProps->GetNext())
		{
			if(!$this->arParams["order_props"][$arOrderProps['ID']]) continue;
			CSaleOrderPropsValue::Add(array(
					'NAME' => $arOrderProps['NAME'],
					'CODE' => $arOrderProps['CODE'],
					'ORDER_PROPS_ID' => $arOrderProps['ID'],
					'ORDER_ID' => $this->orderID,
					'VALUE' => $this->arParams["order_props"][$arOrderProps['ID']],
			));
		}
		
		while ($arOrderProps = $dbOrderProps->Fetch())
		{
			$arResult["ORDER_PROPS"][$arOrderProps["ID"]] = $arOrderProps;
		}
		
		
		
	}
	
	function AddItemOrder()
	{
		global $USER, $APPLICATION, $DB;
		$QUANTITY = 1;
		$PRODUCT_ID = $this->arParams["PRODUCT_ID"];
		$rsProducts = CCatalogProduct::GetList(
				array(),
				array('ID' => $PRODUCT_ID),
				false,
				false,
				array(
						'ID',
						'CAN_BUY_ZERO',
						'QUANTITY_TRACE',
						'QUANTITY',
						'WEIGHT',
						'WIDTH',
						'HEIGHT',
						'LENGTH',
						'TYPE'
				)
				);
		if (!($arCatalogProduct = $rsProducts->Fetch()))
		{
			$this->error[] = $APPLICATION->ThrowException(GetMessage('CATALOG_ERR_NO_PRODUCT'), "NO_PRODUCT");
			return false;
		}
		$dblQuantity = doubleval($arCatalogProduct["QUANTITY"]);
		$intQuantity = intval($arCatalogProduct["QUANTITY"]);
		$boolQuantity = ('Y' != $arCatalogProduct["CAN_BUY_ZERO"] && 'Y' == $arCatalogProduct["QUANTITY_TRACE"]);
		
		$strCallbackFunc = "";
		$strProductProviderClass = "CCatalogProductProvider";
		
		$arCallbackPrice = CSaleBasket::ReReadPrice($strCallbackFunc, "catalog", $PRODUCT_ID, $QUANTITY, "N", $strProductProviderClass);
		if (!is_array($arCallbackPrice) || empty($arCallbackPrice))
		{
			$this->error[] = $APPLICATION->ThrowException(GetMessage('CATALOG_PRODUCT_PRICE_NOT_FOUND'), "NO_PRODUCT_PRICE");
			return false;
		}
		
		$mxResult = CCatalogSku::GetProductInfo($PRODUCT_ID);
		$arSelectProps = array();
		$arSelectOfferProps = array();
		$product_properties0 = $product_properties1 = array();
		if(is_array($mxResult))
		{
			
			if(isset($this->arParams["PRODUCT_PROPS"]) && !empty($this->arParams["PRODUCT_PROPS"]) && isset($this->arParams["PRODUCT_PROPS_VALUE"]) && !empty($this->arParams["PRODUCT_PROPS_VALUE"]))
			{
				$product_properties0 = CIBlockPriceTools::CheckProductProperties(
						$this->arParams["IBLOCK_ID"],
						$mxResult["ID"],
						$this->arParams["PRODUCT_PROPS"],
						$this->arParams["PRODUCT_PROPS_VALUE"],
						false
						);
				if(!is_array($product_properties0)) $product_properties0 = array();
			}
			if(isset($this->arParams["OFFERS_PROPS"]) && !empty($this->arParams["OFFERS_PROPS"]))
			{
				$product_properties1 = CIBlockPriceTools::GetOfferProperties(
						$PRODUCT_ID,
						$this->arParams["IBLOCK_ID"],
						$this->arParams["OFFERS_PROPS"],
						""
						);
				if(!is_array($product_properties1)) $product_properties1 = array();
			}
			$product_properties = array_merge($product_properties0, $product_properties1);
			
		}elseif(isset($this->arParams["PRODUCT_PROPS"]) && !empty($this->arParams["PRODUCT_PROPS"]) && isset($this->arParams["PRODUCT_PROPS_VALUE"]) && !empty($this->arParams["PRODUCT_PROPS_VALUE"])){
			$product_properties = CIBlockPriceTools::CheckProductProperties(
					$this->arParams["IBLOCK_ID"],
					$PRODUCT_ID,
					$this->arParams["PRODUCT_PROPS"],
					$this->arParams["PRODUCT_PROPS_VALUE"],
					false
					);
		}
		
		
		$arProduct = CIBlockElement::GetList(Array(), array("ID"=>$PRODUCT_ID, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "CHECK_PERMISSIONS" => "Y", "MIN_PERMISSION" => "R"), false, Array("nTopCount"=>1), array_merge(array("ID", "NAME", "DETAIL_PAGE_URL", "IBLOCK_ID", "XML_ID"), $arSelectProps))->GetNext();
		if(!$arProduct) return false;
		$arIBlock = CIBlock::GetList(
				array(),
				array("ID" => $arProduct["IBLOCK_ID"])
				)->Fetch();
				
				$arPr = CCatalogProduct::GetByID((int)$item->offerId);
				$arFields = array(
						"PRODUCT_ID" => $PRODUCT_ID,
						"PRODUCT_PRICE_ID" => $arCallbackPrice["PRODUCT_PRICE_ID"],
						"PRICE" => $arCallbackPrice["PRICE"],
						"CURRENCY" => $arCallbackPrice["CURRENCY"],
						"WEIGHT" => $arCatalogProduct["WEIGHT"],
						"DIMENSIONS" => serialize(array(
								"WIDTH" => $arCatalogProduct["WIDTH"],
								"HEIGHT" => $arCatalogProduct["HEIGHT"],
								"LENGTH" => $arCatalogProduct["LENGTH"]
						)
								),
						"QUANTITY" => ($boolQuantity && $dblQuantity < $QUANTITY ? $dblQuantity : $QUANTITY),
						"LID" => $this->siteID,
						"DELAY" => "N",
						"CAN_BUY" => "Y",
						"NAME" => $arProduct["~NAME"],
						"MODULE" => "catalog",
						"PRODUCT_PROVIDER_CLASS" => "CCatalogProductProvider",
						"NOTES" => $arCallbackPrice["NOTES"],
						"DETAIL_PAGE_URL" => $arProduct["~DETAIL_PAGE_URL"],
						"CATALOG_XML_ID" => $arIBlock["XML_ID"],
						"PRODUCT_XML_ID" => $arProduct["XML_ID"],
						"VAT_RATE" => $arCallbackPrice['VAT_RATE'],
						"PROPS" => $product_properties,
						"TYPE" => ($arCatalogProduct["TYPE"] == CCatalogProduct::TYPE_SET) ? CCatalogProductSet::TYPE_SET : NULL,
						"PRICE_TYPE_ID" => $arCallbackPrice["PRICE_TYPE_ID"]
				);
				
				
				$comment = '';
				if($this->fields['COMMENT'])
				{
					$comment = $this->fields['COMMENT'];
				}
				else
				{
					$comment = GetMessage("SOP_COMMENTS").$this->fields['PHONE'];
				}
				
				
				$arDeliv = CSaleDelivery::GetByID($this->arParams["DELIVERY_ID"]);
				$arFieldsOrder = array(
						"LID" => $this->siteID,
						"PERSON_TYPE_ID" => $this->arParams["PERSON_TYPE"],
						"PAYED" => "N",
						"CANCELED" => "N",
						"PRICE" => $arFields["PRICE"],
						"CURRENCY" => CSaleLang::GetLangCurrency($this->siteID),
						"USER_ID" => $this->userID,
						"PAY_SYSTEM_ID" => $this->arParams["PAY_SYSTEM_ID"],
						"PRICE_DELIVERY" => $arDeliv['PRICE'],
						"DELIVERY_ID" => $this->arParams["DELIVERY_ID"],
						"DISCOUNT_VALUE" => $arFields["DISCOUNT_PRICE"],
						"USER_DESCRIPTION" => $comment,
						"STATUS_ID" => $this->arParams["STATUS_ORDER"],
				);
				$this->orderID = CSaleOrder::Add($arFieldsOrder);
				if($this->orderID>0){
					$arFields["ORDER_ID"] = $this->orderID;
				}else{
					if($ex = $APPLICATION->GetException())
						$this->error[] = $ex->GetString();
						else
							$this->error[] = GetMessage("SOP_ORDER_ERROR");
							
							return false;
				}
				$addres = CSaleBasket::Add($arFields);
				if ($addres)
				{
					if (CModule::IncludeModule("statistic"))
						CStatistic::Set_Event("sale2basket", "catalog", $arFields["DETAIL_PAGE_URL"]);
				}
				
				if(count($this->error)==0 && $this->arParams["SEND_EVENT"]=="Y")
				{
					$this->CheckFields();
					$this->AddEvent();
				}
	}
	
	function AddOrder()
	{
		global $APPLICATION;
		
		$arErrors = array();
		$arWarnings = array();
		
		$arShoppingCart = CSaleBasket::DoGetUserShoppingCart($this->siteID, $this->userID, intval(CSaleBasket::GetBasketUserID()), $arErrors);
		
		
		$arResultProps["USER_PROFILES"] = CSaleOrderUserProps::DoLoadProfiles($this->userID, $this->arParams["PERSON_TYPE"]);
		$arProfileTmp = array();
		
		if (!empty($arResultProps["USER_PROFILES"]) && is_array($arResultProps["USER_PROFILES"]))
		{
			foreach($arResultProps["USER_PROFILES"] as $key => $val)
			{
				if ($PROFILE_ID === "")
				{
					$arResultProps["USER_PROFILES"][$key]["CHECKED"] = "Y";
					$PROFILE_ID = $key;
				}
				elseif ($PROFILE_ID == $key)
				{
					$arResultProps["USER_PROFILES"][$key]["CHECKED"] = "Y";
				}
			}
		}
		else
			$PROFILE_ID = (int)$PROFILE_ID;
			
			
			$userProfile = $arResultProps["USER_PROFILES"];
			$arPropValues = array();
			
			$arPropValues = $userProfile[$PROFILE_ID]["VALUES"];
			
			
			$arOrderDat = CSaleOrder::DoCalculateOrder(
					$this->siteID,
					$this->userID,
					$arShoppingCart,
					$this->arParams["PERSON_TYPE"],
					$arOrderPropsValues,
					$this->arParams["DELIVERY_ID"],
					$this->arParams["PAY_SYSTEM_ID"],
					array(),
					$arErrors,
					$arWarnings
					);
			
			
			$info = ($this->fields['COMMENT'])?$this->fields['COMMENT']:'';
			
			$arOrderFields = array(
					"LID" => $arOrderDat['LID'],
					"PERSON_TYPE_ID" => $arOrderDat['PERSON_TYPE_ID'],
					"PAYED" => "N",
					"CANCELED" => "N",
					"STATUS_ID" => "N",
					"PRICE" => $arOrderDat['PRICE'],
					"CURRENCY" => $arOrderDat['CURRENCY'],
					"USER_ID" => $arOrderDat['USER_ID'],
					"USER_DESCRIPTION" => $info,
					"ADDITIONAL_INFO" => ""
			);
			
			$this->orderID = CSaleOrder::DoSaveOrder($arOrderDat, $arOrderFields, 0, $arErrors);
			if($this->orderID<=0){
				if($ex = $APPLICATION->GetException())
					$this->error[] = $ex->GetString();
					else
						$this->error[] = GetMessage("SOP_ORDER_ERROR");
			}
			
			if(count($arErrors)==0 && $this->arParams["SEND_EVENT"]=="Y")
			{
				$this->CheckFields();
				$this->AddEvent();
			}
			
	}
	
	function AddEvent()
	{
		global $DB, $USER, $APPLICATION;
		$strOrderList = "";
		$arBasketList = array();
		$arResult["ORDER_PRICE"] = $this->orderPrice;
		$arResult["BASE_LANG_CURRENCY"] = $this->currency;
		
		$dbBasketItems = CSaleBasket::GetList(
				array("NAME" => "ASC"),
				array("ORDER_ID" => $this->orderID),
				false,
				false,
				array("ID", "PRODUCT_ID", "NAME", "QUANTITY", "PRICE", "CURRENCY", "TYPE", "SET_PARENT_ID")
				);
		while ($arItem = $dbBasketItems->Fetch())
		{
			if (CSaleBasketHelper::isSetItem($arItem))
				continue;
				
				$arBasketList[] = $arItem;
		}
		
		$arBasketList = getMeasures($arBasketList);
		
		foreach ($arBasketList as $arItem)
		{
			$measureText = (isset($arItem["MEASURE_TEXT"]) && strlen($arItem["MEASURE_TEXT"])) ? $arItem["MEASURE_TEXT"] : GetMessage("SOP_SHT");
			
			$strOrderList .= $arItem["NAME"]." - ".$arItem["QUANTITY"]." ".$measureText.": ".SaleFormatCurrency($arItem["PRICE"], $arItem["CURRENCY"]);
			$strOrderList .= "\n";
		}
		$arFields = Array(
				"ORDER_ID" => $this->orderID,
				"ORDER_DATE" => Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", $this->siteID))),
				"PRICE" => SaleFormatCurrency($arResult["ORDER_PRICE"], $arResult["BASE_LANG_CURRENCY"]),
				"EMAIL_TO" => COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
				"PRODUCT_LIST" => $strOrderList,
				"PHONE" => $this->phone,
				"EMAIL_FROM" => COption::GetOptionString("main", "email_from"),
				"SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
				
				);
		
		$bSend = true;
		$eventName = self::EVENT_ADD_ORDER;
		foreach(GetModuleEvents("sale", "OnOrderNewSendEmail", true) as $arEvent)
			if (ExecuteModuleEventEx($arEvent, Array($this->orderID, &$eventName, &$arFields))===false)
				$bSend = false;
				if($bSend)
				{
					$event = new CEvent;
					$event->Send($eventName, $this->siteID, $arFields, "N");
				}
	}
	
	function AddUser()
	{
		global $USER, $APPLICATION;
		
		if($this->fields['EMAIL'])
		{
			$email = $this->fields['EMAIL'];
		}
		else
		{
			$email = str_replace(array(" ", "(", ")", "+", "_", "*", "-"), "", $this->fields['PHONE']);
			$email = preg_replace("/\D/", "", $email);
			$email.="@phone.ru";
		}
		
		$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
		if($def_group!="")
		{
			$GROUP_ID = explode(",", $def_group);
			
			$arPolicy = $USER->GetGroupPolicy($GROUP_ID);
		}
		else
		{
			$arPolicy = $USER->GetGroupPolicy(array());
		}
		if($this->arParams["USER_GROUP"]!=0)$GROUP_ID[] = $this->arParams["USER_GROUP"];
		
		$password_min_length = intval($arPolicy["PASSWORD_LENGTH"]);
		if($password_min_length <= 0)
			$password_min_length = 6;
			$password_chars = array(
					"abcdefghijklnmopqrstuvwxyz",
					"ABCDEFGHIJKLNMOPQRSTUVWXYZ",
					"0123456789",
			);
			if($arPolicy["PASSWORD_PUNCTUATION"] === "Y")
				$password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
				
				$NEW_PASSWORD = $NEW_PASSWORD_CONFIRM = randString($password_min_length+2, $password_chars);
				$NEW_LOGIN = $email;
				if($this->arParams["SELECT_USER"]=="new")
				{
					$dbUserLogin = CUser::GetList($by = 'ID', $order1 = 'ASC', array("LOGIN"=>$NEW_LOGIN), array("SELECT"=>array("ID", "LOGIN")));
					if ($arUserLogin = $dbUserLogin->Fetch())
					{
						$newLoginTmp = $NEW_LOGIN;
						$uind = 0;
						do
						{
							$uind++;
							$newLoginTmp = $NEW_LOGIN.$uind;
							$dbUserLogin = CUser::GetList($by = 'ID', $order1 = 'ASC', array("LOGIN"=>$newLoginTmp), array("SELECT"=>array("ID", "LOGIN")));
						}
						while ($arUserLogin = $dbUserLogin->Fetch());
						$NEW_LOGIN = $newLoginTmp;
					}
				}
				
				$name = ($this->fields['NAME'])?$this->fields['NAME']:'';
				
				$login = 'USER_ORDERPHONE';
				$e = "orderphone@phone.ru";
				if($this->arParams["SELECT_USER"]=="new" || $this->arParams["SELECT_USER"]=="isset")
				{
					$login = $e = $NEW_LOGIN;
				}
				
				$user = new CUser;
				$arAuthResult = $user->Add(Array(
						"LOGIN" => $login,
						"NAME" => $name,
						"PASSWORD" => $NEW_PASSWORD,
						"CONFIRM_PASSWORD" => $NEW_PASSWORD_CONFIRM,
						"EMAIL" =>$e,
						"GROUP_ID" => $GROUP_ID,
						"ACTIVE" => "Y",
						"PERSONAL_PHONE" => $this->arParams["SELECT_USER"]=="new"?$this->phone:"",
						"LID" => $this->siteID,
						)
						);
				
				if (IntVal($arAuthResult) <= 0)
				{
					$this->error[] = $user->LAST_ERROR;
				}
				else $this->userID = $arAuthResult;
	}
	
}
?>