<? $GLOBALS['_2114104588_']=Array('intva' .'l','strval','' .'strva' .'l','i' .'s_n' .'umer' .'ic','in' .'t' .'val','strval','int' .'v' .'al','i' .'n' .'tval','in_array','' .'is_array','is' .'_arr' .'ay','int' .'va' .'l','is_array','is_arra' .'y','in' .'_arra' .'y','' .'is' .'_arr' .'ay','is' .'_array','intva' .'l','i' .'n_array','' .'in_arra' .'y','pre' .'g_replace','' .'ar' .'ray_keys','intval','s' .'c' .'a' .'ndir','' .'array_' .'shif' .'t','' .'array_s' .'h' .'ift','' .'is_' .'file','is_file','' .'is_f' .'ile','is_file','is_file','is' .'_file','intva' .'l','is_file','intv' .'a' .'l','is_f' .'ile'); ?><? function _1442369145($i){$a=Array('startshop_payment_properties','PAYMENT','PROPERTY','startshop_payment_properties','VALUE','PAYMENT','PROPERTY','startshop_payment_properties','PAYMENT','PROPERTY','VALUE','startshop_payment_properties','PAYMENT','PROPERTY','startshop_payment_properties','PAYMENT','startshop_payment_properties','PAYMENT','PROPERTIES','LANG','startshop_payment','PROPERTIES','LANG','CODE','CODE','CODE','CODE','CODE','CODE','startshop_payment','ID','startshop_payment','ID','/^(>=|<=|=|!|>|<)/','','startshop_payment','LANG','PROPERTIES','ID','startshop_payment_properties','PAYMENT','LID','ASC','PAYMENT','PAYMENT','LANG','LID',"NAME",'NAME','PAYMENT','PROPERTIES','PROPERTY','VALUE','/payment/','/description.php','/description.php','SUCCESS_PATH','RESULT_PATH','FAIL_PATH','PAYFORM_PATH','NAME','NAME','CODE','CODE','/parameters.php','/parameters.php','PARAMETERS','/success.php','SUCCESS_PATH','/success.php','/result.php','RESULT_PATH','/result.php','/fail.php','FAIL_PATH','/fail.php','/payform.php','PAYFORM_PATH','/payform.php','NAME','NAME','NAME','CODE','CODE','CODE','HANDLER','PROPERTIES','PAYFORM_PATH','PAYFORM_PATH','PAYFORM_PATH','HANDLER','PROPERTIES');return $a[$i];} ?><? class CStartShopPayment extends CStartShop implements CStartShopInterface{private static $arFieldsEditable=array('CODE','ACTIVE','SORT','HANDLER','CURRENCY');public static function getArFieldsEditable(){return static::$arFieldsEditable;}private static $arFieldsFiltering=array('ID','CODE','ACTIVE','SORT','HANDLER','CURRENCY');public static function getArFieldsFiltering(){return static::$arFieldsFiltering;}protected static $sLanguageTable="startshop_payment_language";protected static $sLanguageTableLink="PAYMENT";protected static $sLanguageTableLanguage="LID";protected static $arLanguageFields=array("NAME");public static function SetProperty($iPaymentID,$sPropertyKey,$cPropertyValue=null){$iPaymentID=$GLOBALS['_2114104588_'][0]($iPaymentID);$sPropertyKey=$GLOBALS['_2114104588_'][1]($sPropertyKey);$cPropertyValue=$GLOBALS['_2114104588_'][2]($cPropertyValue);if(empty($iPaymentID)|| empty($sPropertyKey))return false;if(!$GLOBALS['_2114104588_'][3]($cPropertyValue)&& empty($cPropertyValue))$cPropertyValue=null;$bExists=(bool)CStartShopDBQueryBX::Select()->From(_1442369145(0))->Where(array(_1442369145(1)=> $iPaymentID,_1442369145(2)=> $sPropertyKey))->Execute()->Fetch();if($bExists){CStartShopDBQueryBX::Update()->Tables(_1442369145(3))->Values(array(_1442369145(4)=> $cPropertyValue))->Where(array(_1442369145(5)=> $iPaymentID,_1442369145(6)=> $sPropertyKey))->Execute();}else{CStartShopDBQueryBX::Insert()->Into(_1442369145(7))->Values(array(_1442369145(8)=> $iPaymentID,_1442369145(9)=> $sPropertyKey,_1442369145(10)=> $cPropertyValue))->Execute();}return true;}public static function GetProperty($iPaymentID,$sPropertyKey){$iPaymentID=$GLOBALS['_2114104588_'][4]($iPaymentID);$sPropertyKey=$GLOBALS['_2114104588_'][5]($sPropertyKey);if(empty($iPaymentID)|| empty($sPropertyKey))return CStartShopUtil::ArrayToDBResult(array());return CStartShopDBQueryBX::Select()->From(_1442369145(11))->Where(array(_1442369145(12)=> $iPaymentID,_1442369145(13)=> $sPropertyKey))->Execute();}public static function GetProperties($iPaymentID){$iPaymentID=$GLOBALS['_2114104588_'][6]($iPaymentID);if(empty($iPaymentID))return CStartShopUtil::ArrayToDBResult(array());return CStartShopDBQueryBX::Select()->From(_1442369145(14))->Where(array(_1442369145(15)=> $iPaymentID))->Execute();}public static function DeleteProperties($iPaymentID){$iPaymentID=$GLOBALS['_2114104588_'][7]($iPaymentID);CStartShopDBQueryBX::Delete()->From(_1442369145(16))->Where(array(_1442369145(17)=> $iPaymentID))->Execute();return true;}public static function DeletePropertiesAll(){CStartShopDBQueryBX::Delete()->From('startshop_payment_properties')->Execute();return true;}public static function Add($arFields){if(empty($arFields['CODE']))return false;global $DB;$arProperties=$arFields[_1442369145(18)];$arLanguages=$arFields[_1442369145(19)];$arFields=CStartShopUtil::ArrayFilter($arFields,function($sKey){return $GLOBALS['_2114104588_'][8]($sKey,CStartShopPayment::getArFieldsEditable());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);if(!$GLOBALS['_2114104588_'][9]($arProperties))$arProperties=array();if(!$GLOBALS['_2114104588_'][10]($arLanguages))$arLanguages=array();$bExists=(bool)static::GetList(array(),array('CODE'=> $arFields['CODE']))->Fetch();if($bExists)return false;$iInsertedID=CStartShopDBQueryBX::Insert()->Into(_1442369145(20))->Values($arFields)->Execute();if($iInsertedID){foreach($arLanguages as $sKey => $arLanguage)static::SetLanguage($iInsertedID,$sKey,$arLanguage);foreach($arProperties as $sPropertyKey => $cPropertyValue)static::SetProperty($iInsertedID,$sPropertyKey,$cPropertyValue);return $iInsertedID;}return false;}public static function Update($iPaymentID,$arFields){$iPaymentID=$GLOBALS['_2114104588_'][11]($iPaymentID);$arProperties=$arFields[_1442369145(21)];$arLanguages=$arFields[_1442369145(22)];$bExists=false;$arPayment=static::GetByID($iPaymentID)->Fetch();if(!$GLOBALS['_2114104588_'][12]($arProperties))$arProperties=false;if(!$GLOBALS['_2114104588_'][13]($arLanguages))$arLanguages=false;if(isset($arFields[_1442369145(23)])&& empty($arFields[_1442369145(24)]))unset($arFields[_1442369145(25)]);if(isset($arFields[_1442369145(26)]))if($arFields[_1442369145(27)]!= $arPayment[_1442369145(28)])$bExists=(bool)static::GetList(array(),array('CODE'=> $arFields['CODE']))->Fetch();if($bExists)return false;$arFields=CStartShopUtil::ArrayFilter($arFields,function($sKey){return $GLOBALS['_2114104588_'][14]($sKey,CStartShopPayment::getArFieldsEditable());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);if(!empty($arFields))CStartShopDBQueryBX::Update()->Tables(_1442369145(29))->Values($arFields)->Where(array(_1442369145(30)=> $iPaymentID))->Execute();if($GLOBALS['_2114104588_'][15]($arProperties)){static::DeleteProperties($iPaymentID);foreach($arProperties as $sPropertyKey => $cPropertyValue)static::SetProperty($iPaymentID,$sPropertyKey,$cPropertyValue);}if($GLOBALS['_2114104588_'][16]($arLanguages)){static::DeleteLanguages($iPaymentID);foreach($arLanguages as $sLanguageKey => $arLanguage)static::SetLanguage($iPaymentID,$sLanguageKey,$arLanguage);}return true;}public static function Delete($iPaymentID){$iPaymentID=$GLOBALS['_2114104588_'][17]($iPaymentID);CStartShopDBQueryBX::Delete()->From(_1442369145(31))->Where(array(_1442369145(32)=> $iPaymentID))->Execute();static::DeleteLanguages($iPaymentID);static::DeleteProperties($iPaymentID);return true;}public static function DeleteAll(){CStartShopDBQueryBX::Delete()->From('startshop_payment')->Execute();static::DeleteLanguagesAll();static::DeletePropertiesAll();return true;}public static function GetList($arSort=array(),$arFilter=array()){$arPayments=array();$arSort=CStartShopUtil::ArrayFilter($arSort,function($sKey){return $GLOBALS['_2114104588_'][18]($sKey,CStartShopPayment::getArFieldsFiltering());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);$arFilter=CStartShopUtil::ArrayFilter($arFilter,function($sKey){return $GLOBALS['_2114104588_'][19]($GLOBALS['_2114104588_'][20](_1442369145(33),_1442369145(34),$sKey),CStartShopPayment::getArFieldsFiltering());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);$dbResult=CStartShopDBQueryBX::Select()->From(_1442369145(35))->Where($arFilter)->OrderBy($arSort)->Execute();while($arPayment=$dbResult->Fetch()){$arPayment[_1442369145(36)]=array();$arPayment[_1442369145(37)]=array();$arPayments[$arPayment[_1442369145(38)]]=$arPayment;}if(!empty($arPayments)){$arPaymentsID=$GLOBALS['_2114104588_'][21]($arPayments);$dbProperties=CStartShopDBQueryBX::Select()->From(_1442369145(39))->Where(array(_1442369145(40)=> $arPaymentsID))->Execute();$dbLanguages=static::GetLanguageList(array(_1442369145(41)=> _1442369145(42)),array(_1442369145(43)=> $arPaymentsID));while($arLanguage=$dbLanguages->Fetch())$arPayments[$arLanguage[_1442369145(44)]][_1442369145(45)][$arLanguage[_1442369145(46)]]=array(_1442369145(47)=> $arLanguage[_1442369145(48)]);while($arProperty=$dbProperties->Fetch()){$arPayments[$arProperty[_1442369145(49)]][_1442369145(50)][$arProperty[_1442369145(51)]]=$arProperty[_1442369145(52)];}}return CStartShopUtil::ArrayToDBResult($arPayments);}public static function GetByID($iPaymentID){$iPaymentID=$GLOBALS['_2114104588_'][22]($iPaymentID);return static::GetList(array(),array('ID'=> $iPaymentID));}public static function GetHandlersList($arFilter=array()){$sPathAbsolute=CStartShopVariables::$MODULE_PATH_ABSOLUTE .'/payment/';$sPathRelative=CStartShopVariables::$MODULE_PATH_RELATIVE ._1442369145(53);$arPayments=array();$arPaymentsDirectories=$GLOBALS['_2114104588_'][23]($sPathAbsolute);$GLOBALS['_2114104588_'][24]($arPaymentsDirectories);$GLOBALS['_2114104588_'][25]($arPaymentsDirectories);foreach($arPaymentsDirectories as $sPaymentsDirectory){$arPaymentDescription=array();$arPaymentParameters=array();$bAdd=true;if($GLOBALS['_2114104588_'][26]($sPathAbsolute .$sPaymentsDirectory ._1442369145(54))){include($sPathAbsolute .$sPaymentsDirectory ._1442369145(55));$arPayment=array();$arPayment[_1442369145(56)]=false;$arPayment[_1442369145(57)]=false;$arPayment[_1442369145(58)]=false;$arPayment[_1442369145(59)]=false;$arPayment[_1442369145(60)]=$arPaymentDescription[_1442369145(61)];$arPayment[_1442369145(62)]=$arPaymentDescription[_1442369145(63)];if($GLOBALS['_2114104588_'][27]($sPathAbsolute .$sPaymentsDirectory ._1442369145(64)))include($sPathAbsolute .$sPaymentsDirectory ._1442369145(65));$arPayment[_1442369145(66)]=$arPaymentParameters;if($GLOBALS['_2114104588_'][28]($sPathAbsolute .$sPaymentsDirectory ._1442369145(67)))$arPayment[_1442369145(68)]=$sPathAbsolute .$sPaymentsDirectory ._1442369145(69);if($GLOBALS['_2114104588_'][29]($sPathAbsolute .$sPaymentsDirectory ._1442369145(70)))$arPayment[_1442369145(71)]=$sPathAbsolute .$sPaymentsDirectory ._1442369145(72);if($GLOBALS['_2114104588_'][30]($sPathAbsolute .$sPaymentsDirectory ._1442369145(73)))$arPayment[_1442369145(74)]=$sPathAbsolute .$sPaymentsDirectory ._1442369145(75);if($GLOBALS['_2114104588_'][31]($sPathAbsolute .$sPaymentsDirectory ._1442369145(76)))$arPayment[_1442369145(77)]=$sPathAbsolute .$sPaymentsDirectory ._1442369145(78);if(!empty($arFilter[_1442369145(79)]))if($arFilter[_1442369145(80)]!= $arPayment[_1442369145(81)])$bAdd=false;if(!empty($arFilter[_1442369145(82)]))if($arFilter[_1442369145(83)]!= $arPayment[_1442369145(84)])$bAdd=false;if($bAdd)$arPayments[]=$arPayment;}$arPaySystem=array();$arPaymentDescription=array();$arPaymentParameters=array();}$dbResult=new CDBResult();$dbResult->InitFromArray($arPayments);return $dbResult;}public static function ShowPayForm($iPaymentID,$arHandlerParameters=array()){$iPaymentID=$GLOBALS['_2114104588_'][32]($iPaymentID);$arPayment=static::GetByID($iPaymentID)->Fetch();if($arPayment)if($arPayment[_1442369145(85)]){$arHandlerFields=$arPayment[_1442369145(86)];$arHandler=static::GetHandlersList(array('CODE'=> $arPayment['HANDLER']))->Fetch();if(!empty($arHandler[_1442369145(87)]))if($GLOBALS['_2114104588_'][33]($arHandler[_1442369145(88)])){include($arHandler[_1442369145(89)]);return true;}return false;}}protected static function _IncludeHandler($iPaymentID,$sPathVariable){$iPaymentID=$GLOBALS['_2114104588_'][34]($iPaymentID);if($iPaymentID <= round(0))return false;$arPayment=static::GetList(array(),array('ID'=> $iPaymentID))->Fetch();if($arPayment)if($arPayment[_1442369145(90)]){$arHandlerFields=$arPayment[_1442369145(91)];$arHandler=static::GetHandlersList(array('CODE'=> $arPayment['HANDLER']))->Fetch();if(!empty($arHandler[$sPathVariable]))if($GLOBALS['_2114104588_'][35]($arHandler[$sPathVariable])){return include($arHandler[$sPathVariable]);}return false;}}public static function IncludeResultHandler($iPaymentID){return static::_IncludeHandler($iPaymentID,'RESULT_PATH');}public static function IncludeSuccessHandler($iPaymentID){return static::_IncludeHandler($iPaymentID,'SUCCESS_PATH');}public static function IncludeFailHandler($iPaymentID){return static::_IncludeHandler($iPaymentID,'FAIL_PATH');}} ?>