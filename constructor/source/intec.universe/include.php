<?
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class IntecUniverse
{
    protected static $MODULE_ID = 'intec.universe';
    protected static $VARIABLE = 0;

    public static function Initialize()
    {
        static::Validate();
    }

    protected static function Validate()
    {
//        if (static::$VARIABLE != 1 && static::$VARIABLE != 2) die(Loc::getMessage('intec.universe.demo', ['#MODULE_ID#' => static::$MODULE_ID]));
    }

    public static function SettingsDisplay($value = null, $siteId = false)
    {
        if ($value === null) return COption::GetOptionString(static::$MODULE_ID, 'settingsDisplay', 'admin', $siteId);
        if (!in_array($value, ['none', 'admin', 'all'])) $value = 'none';
        COption::SetOptionString(static::$MODULE_ID, 'settingsDisplay', $value, '', $siteId);
        return true;
    }

    public static function YandexMetrikaUse($value = null, $siteId = false)
    {
        if ($value === null) return COption::GetOptionString(static::$MODULE_ID, 'yandexMetrikaUse', '', $siteId) == 1;
        COption::SetOptionString(static::$MODULE_ID, 'yandexMetrikaUse', $value ? 1 : 0, '', $siteId);
        return true;
    }

    public static function YandexMetrikaId($value = null, $siteId = false)
    {
        if ($value === null) return COption::GetOptionString(static::$MODULE_ID, 'yandexMetrikaId', '', $siteId);
        COption::SetOptionString(static::$MODULE_ID, 'yandexMetrikaId', $value, '', $siteId);
        return true;
    }

    public static function YandexMetrikaClickMap($value = null, $siteId = false)
    {
        if ($value === null) return COption::GetOptionString(static::$MODULE_ID, 'yandexMetrikaClickMap', '', $siteId) == 1;
        COption::SetOptionString(static::$MODULE_ID, 'yandexMetrikaClickMap', $value ? 1 : 0, '', $siteId);
        return true;
    }

    public static function YandexMetrikaTrackHash($value = null, $siteId = false)
    {
        if ($value === null) return COption::GetOptionString(static::$MODULE_ID, 'yandexMetrikaTrackHash', '', $siteId) == 1;
        COption::SetOptionString(static::$MODULE_ID, 'yandexMetrikaTrackHash', $value ? 1 : 0, '', $siteId);
        return true;
    }

    public static function YandexMetrikaTrackLinks($value = null, $siteId = false)
    {
        if ($value === null) return COption::GetOptionString(static::$MODULE_ID, 'yandexMetrikaTrackLinks', '', $siteId) == 1;
        COption::SetOptionString(static::$MODULE_ID, 'yandexMetrikaTrackLinks', $value ? 1 : 0, '', $siteId);
        return true;
    }

    public static function YandexMetrikaWebvisor($value = null, $siteId = false)
    {
        if ($value === null) return COption::GetOptionString(static::$MODULE_ID, 'yandexMetrikaWebvisor', '', $siteId) == 1;
        COption::SetOptionString(static::$MODULE_ID, 'yandexMetrikaWebvisor', $value ? 1 : 0, '', $siteId);
        return true;
    }
}

?>