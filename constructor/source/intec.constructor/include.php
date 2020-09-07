<?php
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

require_once('classes/Loader.php');
Loc::loadMessages(__FILE__);

class IntecConstructor
{
    protected static $MODULE_ID = 'intec.constructor';
    protected static $VARIABLE = 0;

    public static function Initialize()
    {
        static::Validate();
    }

    protected static function Validate()
    {
//        if (static::$VARIABLE != 1 && static::$VARIABLE != 2) die(Loc::getMessage('intec.constructor.demo', ['#MODULE_ID#' => static::$MODULE_ID]));
    }
}