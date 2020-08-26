<?
use Bitrix\Main\SystemException,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

class KitRegions
{
    const moduleId = 'kit.regions';
    const regionsPath = 'kit_regions.php';
    const regionPath = 'kit_regions_edit.php';
    const settingsPath = 'kit_regions_settings.php';
    const sitemapPath = 'kit_regions_seofiles.php';
    const regionImport = 'kit_regions_import.php';
    const regionExport = 'kit_regions_export.php';
    const mask = '#KIT_REGIONS_#CODE##';
    const entityId = 'KIT_REGIONS';
    static private $_32785202 = null;

    private static function __1839569451()
    {
        self::$_32785202 = \Bitrix\Main\Loader::includeSharewareModule(self::moduleId);
    }

    public static function getSites()
    {
        $_1164424163 = array();
        try {
            $_1691335112 = \Bitrix\Main\SiteTable::getList(array('select' => array('SITE_NAME', 'LID'), 'filter' => array('ACTIVE' => 'Y'),));
            while ($_812325818 = $_1691335112->fetch()) {
                $_1164424163[$_812325818['LID']] = $_812325818['SITE_NAME'];
            }
            if (!is_array($_1164424163) || count($_1164424163) == 0) {
                throw new SystemException('Cannt get sites');
            }
        } catch (SystemException $_1930511524) {
            echo $_1930511524->getMessage();
        }
        return $_1164424163;
    }

    public static function getMenuParent($_948801080 = '')
    {
        try {
            if (Loader::includeModule('kit.missshop')) {
                $_948801080 = 'global_menu_missshop';
            }
            if (Loader::includeModule('kit.mistershop')) {
                $_948801080 = 'global_menu_mistershop';
            }
            if (Loader::includeModule('kit.b2bshop')) {
                $_948801080 = 'global_menu_b2bshop';
            }
            if (Loader::includeModule('kit.origami')) {
                $_948801080 = 'global_menu_kit';
            }
            if (!$_948801080 || !is_string($_948801080)) {
                throw new SystemException('Cannt find menu parent');
            }
            return $_948801080;
        } catch (SystemException $_1930511524) {
            echo $_1930511524->getMessage();
        }
    }

    public static function genCodeVariable($_1368563074 = '')
    {
        try {
            $_42991531 = self::getUserTypeFields();
            if ($_42991531[$_1368563074]['USER_TYPE_ID'] == 'file') {
                return false;
            }
            if (!$_1368563074 || !is_string($_1368563074)) {
                throw new SystemException('Code isnt string');
            }
            return str_replace('#CODE#', $_1368563074, self::mask);
        } catch (SystemException $_1930511524) {
            echo $_1930511524->getMessage();
        }
    }

    public static function getTags($_1164424163 = array())
    {
        $_214467021 = array();
        if (!$_1164424163) {
            $_1164424163 = array_keys(self::getSites());
        }
        $_214467021[0] = array('CODE' => 'CODE', 'NAME' => 'Домен');
        $_214467021[1] = array('CODE' => 'NAME', 'NAME' => 'Регион');
        $_214467021[2] = array('CODE' => 'SORT', 'NAME' => 'Сортировка');
        $_214467021[3] = array('CODE' => 'PRICE_CODE', 'NAME' => 'Типы цен');
        $_214467021[4] = array('CODE' => 'STORE', 'NAME' => 'Склады');
        $_214467021[5] = array('CODE' => 'COUNTER', 'NAME' => 'Счетчики');
        $_214467021[6] = array('CODE' => 'MAP_YANDEX', 'NAME' => 'Яндекс карта');
        $_214467021[7] = array('CODE' => 'MAP_GOOGLE', 'NAME' => 'Google карта');
        $_1461567851 = 8;
        foreach ($_1164424163 as $_812325818) {
            $_42991531 = self::getUserTypeFields();
            foreach ($_42991531 as $_1235880303) {
                $_1235880303 = \CUserTypeEntity::GetByID($_1235880303['ID']);
                if ($_1235880303['USER_TYPE_ID'] == 'file') {
                    $_214467021[$_1461567851++] = array('CODE' => $_1235880303['FIELD_NAME'], 'NAME' => $_1235880303['LIST_COLUMN_LABEL'][LANGUAGE_ID] . ' (массив)');
                } else {
                    $_214467021[$_1461567851++] = array('CODE' => $_1235880303['FIELD_NAME'], 'NAME' => $_1235880303['LIST_COLUMN_LABEL'][LANGUAGE_ID]);
                }
            }
        }
        return $_214467021;
    }

    public static function getUserTypeFields()
    {
        $_1174016369 = [];
        $_42991531 = \CUserTypeEntity::GetList(['FIELD_NAME' => 'ASC'], ['ENTITY_ID' => self::entityId]);
        while ($_1235880303 = $_42991531->Fetch()) {
            $_1174016369[$_1235880303['FIELD_NAME']] = $_1235880303;
        }
        return $_1174016369;
    }
}
 ?>