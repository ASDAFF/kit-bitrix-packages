<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\DataManager;
use Sotbit\Schemaorg\SchemaPageMetaTable;
use Sotbit\Schemaorg\SchemaCategoryTable;

\Bitrix\Main\Loader::registerAutoloadClasses('sotbit.schemaorg', array('SchemaMain' => '/classes/schema_main.php', 'SchemaCore' => '/classes/schema_core.php', 'SchemaSettings' => '/classes/schema_settings.php',));
IncludeModuleLangFile(__FILE__);
global $DB;

class SotbitSchema
{
    const MODULE_ID = "sotbit.schemaorg";
    private static $_745644782 = false;

    public function __construct()
    {
    }

    public static function setDemo()
    {
//        static::$_745644782 = CModule::IncludeModuleEx(self::MODULE_ID);
    }

    public function getDemo()
    {
//        if (self::$_745644782 === false) self::setDemo();
//        return !(static::$_745644782 == 0 || static::$_745644782 == 3);
    }

    public function returnDemo()
    {
//        if (self::$_745644782 === false) self::setDemo();
//        return static::$_745644782;
    }

    static function getPath()
    {
        $_1334607185 = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/sotbit.schemaorg/admin/sotbit_schemaorg_entities/';
        return $_1334607185;
    }

    static $_71378712 = array();

    static function setEntitiesError($_1238419535, $_1053590896)
    {
        if (isset($_1238419535) && isset($_1053590896) && !isset(self::$_71378712[$_1238419535])) self::$_71378712[$_1238419535] = array();
        if (is_array(self::$_71378712[$_1238419535])) array_merge(self::$_71378712[$_1238419535], $_1053590896);
    }

    static function getEntitiesError()
    {
        return self::$_71378712;
    }

    static function normMulEntities($_2139764468)
    {
        if (isset($_2139764468["itemListElement"]) && !empty($_2139764468["itemListElement"])) $_2139764468["itemListElement"] = array_values($_2139764468["itemListElement"]);
        if (isset($_2139764468['branchOf']['reviews']) && !empty($_2139764468['branchOf']['reviews'])) $_2139764468['branchOf']['reviews'] = array_values($_2139764468['branchOf']['reviews']);
        if (isset($_2139764468['reviews']) && !empty($_2139764468['reviews'])) $_2139764468['reviews'] = array_values($_2139764468['reviews']);
        if (isset($_2139764468['offers']) && !empty($_2139764468['offers'])) $_2139764468['offers'] = array_values($_2139764468['offers']);
        return $_2139764468 == '' ? array() : $_2139764468;
    }

    static function validateEntities($_734814024)
    {
        $_1729736331 = array("Any" => array("name" => ""), "LocalBusiness" => array("@type" => "LocalBusiness", "name" => "", "image" => ""), "Review" => array("@type" => "Review", "name" => "", "author" => ""), "ContactPoint" => array("@type" => "ContactPoint", "name" => "", "contactType" => "", "email" => "", "telephone" => ""), "Place" => array("@type" => "Place", "name" => "", "address" => ""), "PostalAddress" => array("@type" => "PostalAddress", "name" => "", "contactType" => "", "email" => "", "telephone" => ""), "AggregateRating" => array("@type" => "AggregateRating", "name" => "", "ratingCount" => "", "reviewCount" => "", "ratingValue" => ""), "Rating" => array("@type" => "Rating", "name" => "", "ratingValue" => ""), "Event" => array("@type" => "Event", "location" => "", "startDate" => ""),);
        if (isset($_734814024['@type']) && !empty($_734814024['@type']) && isset($_1729736331[$_734814024['@type']])) {
            if (!empty(array_diff_key($_1729736331[$_734814024['@type']], $_734814024))) self::setEntitiesError($_734814024['@type'], array_diff_key($_1729736331[$_734814024['@type']], $_734814024));
        } else if (!empty($_734814024) && !isset($_1729736331[$_734814024['@type']])) {
            if (!empty(array_diff_key($_1729736331['Any'], $_734814024))) self::setEntitiesError($_734814024['@type'], array_diff_key($_1729736331['Any'], $_734814024));
        }
        if (!empty($_734814024) && is_array($_734814024)) foreach ($_734814024 as $_441127954) {
            if (is_array($_441127954)) {
                self::validateEntities($_441127954);
            }
        }
    }

    public function arrayFilterRecursive(&$_884297376)
    {
        if (!empty($_884297376) && is_array($_884297376)) {
            foreach ($_884297376 as &$_221509068) {
                if (is_array($_221509068)) {
                    $_221509068 = self::arrayFilterRecursive($_221509068);
                }
            }
            return array_filter($_884297376, function ($_441127954) {
                if (is_array($_441127954)) return true; else return !preg_match('/^\s*$/', $_441127954);
            });
        } else return '';
    }

    private function __399282150($_422714394, $_1386610935, $_72676755 = false)
    {
        if (!$_72676755) $_301072884 = array_keys(SchemaCategoryTable::getMap()); else $_301072884 = array_keys(SchemaPageMetaTable::getMap());
        $_1771868183 = array(self::__68721054($_301072884, $_1386610935, $_72676755), $_422714394);
        return $_1771868183;
    }

    private function __68721054(array $_301072884, $_491733779, $_72676755 = false)
    {
        $_301072884 = array_flip($_301072884);
        if (!$_72676755) {
            unset($_301072884['PARENT']);
            unset($_301072884['PARENT_ID']);
        } else {
            unset($_301072884['ACTIVE']);
            unset($_301072884['CATEGORY_ID']);
        }
        $_301072884 = array_keys($_301072884);
        $_1771868183 = array('LOGIC' => 'OR',);
        foreach ($_301072884 as $_2051586567) {
            $_1771868183[] = array('%=' . $_2051586567 => '%' . $_491733779 . '%');
        }
        return $_1771868183;
    }

    public function getMixedList($_58515906 = array("ID" => "ASC"), $_125983064 = array(), $_1682208057 = false, $_1372567224 = false)
    {
        $_1753444236 = array();
        $_301072884 = array_map(function ($_1613117014) {
            return '';
        }, array_flip(array_keys(SchemaPageMetaTable::getMap())));
        $_124673746 = array('SORT' => '', 'SITE_ID' => '', 'NAME' => '', 'PARENT_ID' => false, '>=DATE_CREATE' => '', '<=DATE_CREATE' => '');
        $_124673746 = array_intersect_key($_125983064, $_124673746);
        if (isset($_125983064['CATEGORY_ID'])) $_124673746['PARENT_ID'] = $_125983064['CATEGORY_ID']; else if (count($_125983064) < 2) $_124673746['PARENT_ID'] = false;
        if (isset($_125983064['NAME'])) $_124673746['NAME'] = $_125983064['NAME'];
        if (isset($_125983064['FIND'])) $_124673746 = self::__399282150($_124673746, $_125983064['FIND']);
        $_1551632309 = new SchemaCategoryTable;
        $_1389879463 = $_1551632309->getList(array('order' => $_58515906, 'filter' => $_124673746, 'select' => array('ID', 'NAME', 'DATE_CREATE', 'TIMESTAMP_X', 'SITE_ID', 'SORT')));
        while ($_1905230314 = $_1389879463->Fetch()) {
            $_1905230314['TYPE'] = 'S';
            $_1753444236[] = $_1905230314;
        }
        $_1433807962 = array_merge($_301072884, array('>=DATE_CREATE' => '', '<=DATE_CREATE' => ''));
        $_1433807962 = array_intersect_key($_125983064, $_1433807962);
        if (isset($_125983064['NAME'])) $_1433807962['NAME'] = $_125983064['NAME'];
        if (isset($_125983064['CATEGORY_ID'])) $_1433807962['CATEGORY_ID'] = $_125983064['CATEGORY_ID']; else if (count($_125983064) < 2) $_1433807962['CATEGORY_ID'] = false;
        if (isset($_125983064['SITE_ID']) && !empty($_125983064['SITE_ID'])) $_1433807962['SITE_ID'] = $_125983064['SITE_ID'];
        if (isset($_125983064['FIND']) && !empty($_125983064['FIND'])) $_1433807962 = self::__399282150($_1433807962, $_125983064['FIND'], true);
        if (!is_array($_1372567224)) $_1372567224 = array('*');
        $_443625053 = new SchemaPageMetaTable;
        $_1676804078 = $_443625053->GetList(array('order' => $_58515906, 'filter' => $_1433807962, 'select' => $_1372567224));
        while ($_696889307 = $_1676804078->Fetch()) {
            $_696889307['TYPE'] = 'E';
            $_1753444236[] = $_696889307;
        }
        $_1337305239 = new \CDBResult;
        $_1337305239->InitFromArray($_1753444236);
        return $_1337305239;
    }

    public function makeSingleDropDownField($_625660885, $_668064939, $_1300306693, $_2051586567, $_211608319, $_1188926000, $_383961041 = false)
    {
        $_1334607185 = "";
        $_625660885->AddDropDownField($_2051586567 ? $_2051586567 . '[' . ($_668064939) . ']' : $_668064939, Loc::getMessage($_211608319 . $_668064939), $_383961041, $_1300306693, (isset($_1188926000[$_668064939]['@type']) && !empty($_1188926000[$_668064939]['@type']) ? strtolower($_1188926000[$_668064939]['@type']) : ''), array('class="select_entity"'));
        $_625660885->BeginCustomField($_668064939 . '_BLOCK', '', false);
        if (file_exists(self::getPath() . strtolower($_1188926000[$_668064939]['@type']) . '.php')) {
            $_1334607185 = self::getPath() . strtolower($_1188926000[$_668064939]['@type']) . '.php';
            echo SotbitSchema::getBlockContent($_1188926000[$_668064939], $_1334607185, $_2051586567 . '[' . $_668064939 . ']');
        } else {
            echo '' . '' . '' . '';
        }
        $_625660885->EndCustomField($_668064939 . '_BLOCK');
        return $_625660885;
    }

    public function makeMultipleDropDownField($_625660885, $_668064939, $_1300306693, $_2051586567, $_211608319, $_1188926000)
    {
        $_1334607185 = "";
        $_625660885->BeginCustomField('btn_' . $_668064939 . '_BLOCK', '', false); ?>
        <tr id="tr_<?= $_2051586567 ? $_2051586567 . '[' . ($_668064939) . ']' : $_668064939 ?>">
            <td width="40%"><?= Loc::getMessage($_211608319 . $_668064939) ?></td>
            <td>
                <select name="<?= $_2051586567 ? $_2051586567 . '[' . $_668064939 . ']' : $_668064939 ?>"
                        class="select_entity select_entity_multiple">
                    <? foreach ($_1300306693 as $_745720950 => $_441127954) {
                        if (isset($_1188926000[$_668064939]) && !empty($_1188926000[$_668064939])) $_550120156 = end($_1188926000[$_668064939])['@type'];
                        echo '' . (empty($_745720950) ? '' : $_745720950) . '"' . ($_550120156 == $_441127954 ? 'selected' : '') . '>' . $_441127954 . '';
                    } ?>
                </select>
                <input type="button" class="add-more" value="+"
                       style="display: <?= ($_550120156 == $_441127954 ? 'inline-block' : 'none') ?>">
            </td>
        </tr>
        <? $_625660885->EndCustomField('btn_' . $_668064939 . '_BLOCK');
        $_625660885->BeginCustomField($_668064939 . '_BLOCK', '', false);
        if (isset($_1188926000[$_668064939])) {
            foreach ($_1188926000[$_668064939] as $_745720950 => $_1486268347) {
                if (file_exists(self::getPath() . strtolower($_1188926000[$_668064939][$_745720950]['@type']) . '.php')) {
                    $_1334607185 = self::getPath() . strtolower($_1188926000[$_668064939][$_745720950]['@type']) . '.php';
                    echo SotbitSchema::getBlockContent($_1486268347, $_1334607185, $_2051586567 . '[' . $_668064939 . ']' . '[' . $_745720950 . ']', true);
                }
            }
            self::__1267881910(false);
        } else {
            echo '' . '' . '' . '';
        }
        $_625660885->EndCustomField($_668064939 . '_BLOCK');
        return $_625660885;
    }

    private static $_1519313154 = false;

    private static function __1267881910($_1985127820)
    {
        self::$_1519313154 = $_1985127820;
    }

    private static function __798217034()
    {
        return self::$_1519313154;
    }

    public function getBlockContent($_1188926000, $_1334607185, $_2051586567 = "", $_2011984228 = false)
    {
        if ($_2011984228 && !self::__798217034()) {
            $_1279797638 = '<tr id="tr_ENTITIES_BLOCK" class="adm-detail-file-row">
            <td colspan="2" style="padding-left: 8px !important; padding-top: 8px !important;">';
            self::__1267881910(true);
        } else if (!$_2011984228) {
            $_1279797638 = '';
        } else {
            $_1279797638 = '';
        }
        ob_start();
        include($_1334607185);
        $_1771868183 = ob_get_contents();
        preg_match('//', $_1771868183, $_2017191397);
        ob_end_clean();
        $_1771868183 = $_1279797638 . $_2017191397[0];
        if ($_2011984228 && !self::__798217034()) {
            $_1771868183 .= '';
        } else if (!$_2011984228) {
            $_1771868183 .= '';
        } else {
            $_1771868183 .= '';
        }
        return $_1771868183;
    }

    public function checkKey($_2051586567)
    {
        if (preg_match("/\[0?\]$/", $_2051586567) === 0 && preg_match("/\[\d\]$/", $_2051586567) === 0) $_2051586567 .= "[0]"; else if (preg_match('/\[\d+\]$/', $_2051586567) != 0) {
            $_2051586567 = preg_replace_callback('/\[\d+\]$/', function ($_767780364) {
                $_810029713 = strtr($_767780364[0], array('[' => '', ']' => ''));
                return '[' . (++$_810029713) . ']';
            }, $_2051586567);
        }
        return $_2051586567;
    }
}

class DataManagerEx_SchemaOrg extends DataManager
{
    public static function getList(array $_1463948715 = array())
    {
        if (!SotbitSchema::getDemo()) return new Bitrix\Main\ORM\Query\Result(parent::query(), new \Bitrix\Main\DB\ArrayResult(array())); else return parent::getList($_1463948715);
    }

    public static function getById($_1702899765 = "")
    {
        if (!SotbitSchema::getDemo()) return new \CDBResult; else return parent::getById($_1702899765);
    }

    public static function add(array $_1137271263 = array())
    {
        if (!SotbitSchema::getDemo()) return new \Bitrix\Main\Entity\AddResult(); else return parent::add($_1137271263);
    }

    public static function update($_1702899765 = "", array $_1137271263 = array())
    {
        if (!SotbitSchema::getDemo()) return new \Bitrix\Main\Entity\UpdateResult(); else return parent::update($_1702899765, $_1137271263);
    }
}
 ?>