<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

\Bitrix\Main\Loader::registerAutoloadClasses('sotbit.opengraph', array('OpengraphMain' => '/classes/opengraph_main.php', 'OpengraphCore' => '/classes/opengraph_core.php', 'OpengraphSettings' => '/classes/opengraph_settings.php',));
IncludeModuleLangFile(__FILE__);
global $DB;

class SotbitOpengraph
{
    private static $_2044709962 = 0;
    const MODULE_ID = "sotbit.opengraph";

    public function __construct()
    {
        $this->__875650465();
    }

    private static function __875650465()
    {
      //  static::$_2044709962 = CModule::IncludeModuleEx(self::MODULE_ID);
    }

    public function getDemo()
    {
       // return !(static::$_2044709962 == 0 || static::$_2044709962 == 3);
    }

    public function ReturnDemo()
    {
      //  return static::$_2044709962;
    }
} ?>