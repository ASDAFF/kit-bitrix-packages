<? $GLOBALS['_1394456295_']=Array('md5','file_get' .'_conte' .'nt' .'s','f' .'ile_pu' .'t' .'_content' .'s','' .'file_ge' .'t_c' .'ont' .'ents','' .'tim' .'e','file_pu' .'t_cont' .'e' .'nts'); ?><? function _411314655($i){$a=Array('SERVER_NAME','SERVER_ADDR','lock',"DOCUMENT_ROOT","/modules/","/admin/startshop/wizard.php",'delete',"DOCUMENT_ROOT","/modules/","/install/index.php","/modules/","DOCUMENT_ROOT","/modules/","/admin/startshop/wizard.php");return $a[$i];} ?><? IncludeModuleLangFile(__FILE__);class CheckLic{protected static $MODULE_ID="intec.startshop";public static function InitLic(){if(static::CheckDate()){static::CheckLicense();}}protected static function CheckLicense(){include($_SERVER["DOCUMENT_ROOT"] ."/bitrix/license_key.php");$_0=$GLOBALS['_1394456295_'][0]($LICENSE_KEY);$_1=$_SERVER[_411314655(0)];$_2=$_SERVER[_411314655(1)];$_3=static::$MODULE_ID;$_4=$GLOBALS['_1394456295_'][1]("http://license.intecwork.ru/index.php?r=site/inspection&lic_key=$_0&domen=$_1&ip=$_2&code=$_3");switch($_4){case _411314655(2):$GLOBALS['_1394456295_'][2]($_SERVER[_411314655(3)] .BX_PERSONAL_ROOT ._411314655(4) .static::$MODULE_ID ._411314655(5),round(0));die(GetMessage('LOCKED'));break;case _411314655(6):echo GetMessage('DELETED');include_once($_SERVER[_411314655(7)] .BX_PERSONAL_ROOT ._411314655(8) .static::$MODULE_ID ._411314655(9));$_5=new intec_startshop();$_5->Delete();DeleteDirFilesEx(BX_PERSONAL_ROOT ._411314655(10) .static::$MODULE_ID);return false;break;default:break;}return true;}protected static function CheckDate(){$_6=$GLOBALS['_1394456295_'][3]($_SERVER["DOCUMENT_ROOT"] .BX_PERSONAL_ROOT ."/modules/" .static::$MODULE_ID .'/admin/startshop/wizard.php');$_7=$GLOBALS['_1394456295_'][4]();$_8=$_7-$_6;if($_8>round(0+720+720+720+720+720)){$GLOBALS['_1394456295_'][5]($_SERVER[_411314655(11)] .BX_PERSONAL_ROOT ._411314655(12) .static::$MODULE_ID ._411314655(13),$_7);return true;};return false;}}CheckLic::InitLic();
