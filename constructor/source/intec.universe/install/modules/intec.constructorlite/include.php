<?php
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

require_once('classes/Loader.php');
Loc::loadMessages(__FILE__);

class IntecConstructorLite {
    protected static $MODULE_ID = 'intec.constructorlite';
    protected static $MODULE_STATE = 0;

    public static function Initialize() {
        static::$MODULE_STATE = CModule::IncludeModuleEx(static::$MODULE_ID);
        static::Validate();
    }

    protected static function Validate() {
        if (static::$MODULE_STATE != 1 && static::$MODULE_STATE != 2)
            die(Loc::getMessage('intec.constructorlite.demo', ['#MODULE_ID#' => static::$MODULE_ID]));
    }
}
?>