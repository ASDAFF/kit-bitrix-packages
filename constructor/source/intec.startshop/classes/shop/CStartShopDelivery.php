<? $GLOBALS['_92630286_']=Array('i' .'ntval','i' .'ntval','intv' .'al','in_arra' .'y','' .'is_a' .'r' .'ray','is' .'_' .'arra' .'y','int' .'v' .'al','is_a' .'rray','is_array','' .'in_array','i' .'s_array','' .'is' .'_array','i' .'ntva' .'l','i' .'n_' .'array','i' .'n_ar' .'ray','preg_replace','array_' .'k' .'eys','i' .'n' .'tval'); ?><? function _367482818($i){$a=Array('PROPERTIES','LANG','PROPERTIES','LANG','CODE','CODE','CODE','SID','SID','SID','CODE','SID','CODE','CODE','SID','SID','CODE','CODE','CODE','CODE','CODE','SID','SID','SID','SID','SID','/^(>=|<=|=|!|>|<)/','','LANG','ID');return $a[$i];} ?><? class CStartShopDelivery extends CStartShop implements CStartShopInterface{private static $arFieldsEditable=array('CODE','SORT','ACTIVE','PRICE','SID');public static function getArFieldsEditable(){return static::$arFieldsEditable;}private static $arFieldsFiltering=array('ID','CODE','SORT','ACTIVE','PRICE','SID');public static function getArFieldsFiltering(){return static::$arFieldsFiltering;}protected static $sLanguageTable="startshop_delivery_language";protected static $sLanguageTableLink="DELIVERY";protected static $sLanguageTableLanguage="LID";protected static $arLanguageFields=array("NAME");private static function AddProperty($_0,$_1){$_0=$GLOBALS['_92630286_'][0]($_0);$_1=$GLOBALS['_92630286_'][1]($_1);if($_0>round(0)&& $_1>round(0)){CStartShopDBQueryBX::Insert()->Into('startshop_delivery_properties')->Values(array('DELIVERY'=> $_0,'PROPERTY'=> $_1))->Execute();return true;}return false;}private static function DeleteProperties($_0){CStartShopDBQueryBX::Delete()->From('startshop_delivery_properties')->Where(array('DELIVERY'=> $GLOBALS['_92630286_'][2]($_0)))->Execute();return true;}private static function DeletePropertiesAll(){CStartShopDBQueryBX::Delete()->From('startshop_delivery_properties')->Execute();return true;}public static function Add($_2){if(empty($_2['CODE'])|| empty($_2['SID']))return false;$_3=$_2[_367482818(0)];$_4=$_2[_367482818(1)];$_2=CStartShopUtil::ArrayFilter($_2,function($_5){return $GLOBALS['_92630286_'][3]($_5,CStartShopDelivery::getArFieldsEditable());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);if(!$GLOBALS['_92630286_'][4]($_3))$_3=array();if(!$GLOBALS['_92630286_'][5]($_4))$_4=array();$_6=(bool)static::GetList(array(),array('CODE'=> $_2['CODE'],'SID'=> $_2['SID']))->Fetch();if($_6)return false;$_7=CStartShopDBQueryBX::Insert()->Into('startshop_delivery')->Values($_2)->Execute();if($_7){foreach($_3 as $_1)static::AddProperty($_7,$_1);foreach($_4 as $_8 => $_9)static::SetLanguage($_7,$_8,$_9);return $_7;}return false;}public static function Update($_0,$_2){$_0=$GLOBALS['_92630286_'][6]($_0);$_3=$_2[_367482818(2)];$_4=$_2[_367482818(3)];$_6=false;$_10=static::GetByID($_0)->GetNext();if(empty($_10))return false;static::ResetCacheByID($_0);if(!$GLOBALS['_92630286_'][7]($_3))$_3=false;if(!$GLOBALS['_92630286_'][8]($_4))$_4=false;if(isset($_2[_367482818(4)])&& empty($_2[_367482818(5)]))unset($_2[_367482818(6)]);if(isset($_2[_367482818(7)])&& empty($_2[_367482818(8)]))unset($_2[_367482818(9)]);if(isset($_2[_367482818(10)])|| isset($_2[_367482818(11)]))if($_2[_367482818(12)]!= $_10[_367482818(13)]|| $_2[_367482818(14)]!= $_10[_367482818(15)]){$_11=array();if(isset($_2[_367482818(16)])){$_11[_367482818(17)]=$_2[_367482818(18)];}else{$_11[_367482818(19)]=$_10[_367482818(20)];}if(isset($_2[_367482818(21)])){$_11[_367482818(22)]=$_2[_367482818(23)];}else{$_11[_367482818(24)]=$_10[_367482818(25)];}$_6=(bool)static::GetList(array(),$_11)->Fetch();unset($_11);}if($_6)return false;$_2=CStartShopUtil::ArrayFilter($_2,function($_5){return $GLOBALS['_92630286_'][9]($_5,CStartShopDelivery::getArFieldsEditable());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);if(!empty($_2))CStartShopDBQueryBX::Update()->Tables('startshop_delivery')->Values($_2)->Where(array('ID'=> $_0))->Execute();if($GLOBALS['_92630286_'][10]($_3)){static::DeleteProperties($_0);foreach($_3 as $_1)static::AddProperty($_0,$_1);}if($GLOBALS['_92630286_'][11]($_4)){static::DeleteLanguages($_0);foreach($_4 as $_8 => $_9)static::SetLanguage($_0,$_8,$_9);}return true;}public static function Delete($_0){$_0=$GLOBALS['_92630286_'][12]($_0);CStartShopDBQueryBX::Delete()->From('startshop_delivery')->Where(array('ID'=> $_0))->Execute();static::DeleteLanguages($_0);static::DeleteProperties($_0);static::ResetCacheByID($_0);return true;}public static function DeleteAll(){CStartShopDBQueryBX::Delete()->From('startshop_delivery')->Execute();static::DeleteLanguagesAll();static::DeletePropertiesAll();static::ResetCache();return true;}public static function GetList($_12=array('ID'=> 'ASC'),$_13=array()){$_14=array();$_12=CStartShopUtil::ArrayFilter($_12,function($_5){return $GLOBALS['_92630286_'][13]($_5,CStartShopDelivery::getArFieldsFiltering());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);$_13=CStartShopUtil::ArrayFilter($_13,function($_5){return $GLOBALS['_92630286_'][14]($GLOBALS['_92630286_'][15](_367482818(26),_367482818(27),$_5),CStartShopDelivery::getArFieldsFiltering());},STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);$_15=CStartShopDBQueryBX::Select()->From('startshop_delivery')->Where($_13)->OrderBy($_12)->Execute();while($_10=$_15->Fetch()){$_10['PROPERTIES']=array();$_10[_367482818(28)]=array();$_14[$_10[_367482818(29)]]=$_10;}if(!empty($_14)){$_16=$GLOBALS['_92630286_'][16]($_14);$_17=CStartShopDBQueryBX::Select()->From('startshop_delivery_properties')->Where(array('DELIVERY'=> $_16))->Execute();$_18=static::GetLanguageList(array('LID'=> 'ASC'),array('DELIVERY'=> $_16));while($_9=$_18->Fetch())$_14[$_9['DELIVERY']]['LANG'][$_9['LID']]=array("NAME"=> $_9['NAME']);while($_19=$_17->Fetch())$_14[$_19['DELIVERY']]['PROPERTIES'][]=$_19['PROPERTY'];}return CStartShopUtil::ArrayToDBResult($_14);}public static function GetByID($_0){if(!CStartShopCache::IsExists('CStartShopDelivery',$_0)){$_15=self::GetList(array(),array("ID"=> $_0));$_15=CStartShopCache::CreateFromResult('CStartShopDelivery',array('ID'),$_15);}else{$_15=CStartShopCache::GetAsResult('CStartShopDelivery',$_0);}return $_15;}public static function ResetCacheByID($_0){CStartShopCache::Clear('CStartShopDelivery',$GLOBALS['_92630286_'][17]($_0));}public static function ResetCache(){CStartShopCache::ClearPath('CStartShopDelivery');}} ?>