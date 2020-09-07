<?
use Bitrix\Main\Loader;
use \Sotbit\Crosssell\Orm\CrosssellTable;

\Bitrix\Main\Loader::registerAutoloadClasses('sotbit.crosssell',
    array(
        'SotbitCrosssellCatalogCondTree' => '/classes/general/CondTree.php',
        )
);

class SotbitCrosssell
{
    const moduleId = 'sotbit.crosssell';
    private static $_1747861839 = false;

    private static function __12942853()
    {
        static::$_1747861839 = CModule::IncludeModuleEx(self::moduleId);
    }

    public static function getSites()
    {
        $_886781608 = [];
        try {
            $_1787891218 = \Bitrix\Main\SiteTable::getList(['select' => ['SITE_NAME', 'LID', 'DEF'], 'filter' => ['ACTIVE' => 'Y'],]);
            while ($_1981537559 = $_1787891218->fetch()) {
                if ($_1981537559['DEF'] == 'Y') {
                    $_886781608[] = $_1981537559['LID'];
                } else {
                    $_886781608[$_1981537559['LID']] = $_1981537559['SITE_NAME'];
                }
            }
        } catch (ObjectPropertyException $_1446653616) {
            $_1446653616->getMessage();
        } catch (ArgumentException $_1446653616) {
            $_1446653616->getMessage();
        } catch (SystemException $_1446653616) {
            $_1446653616->getMessage();
        }
        try {
            if (!is_array($_886781608) || count($_886781608) == 0) {
                throw new SystemException('Cannt get sites');
            }
        } catch (SystemException $_427941019) {
            echo $_427941019->getMessage();
        }
        return $_886781608;
    }

    public function hasInfoBlock($_442383325)
    {
        $_1902825736 = false;
        foreach ($_442383325['CHILDREN'] as $_249767294) {
            if ($_1902825736) {
                break;
            }
            if ($_249767294['CLASS_ID'] == 'CondIBIBlock') {
                $_1902825736 = true;
                break;
            }
            if ($_249767294['CLASS_ID'] == 'CondGroup') {
                foreach ($_249767294['CHILDREN'] as $_817150337) {
                    if ($_817150337['CLASS_ID'] == 'CondIBIBlock') {
                        $_1902825736 = true;
                        break;
                    }
                }
            }
        }
        return $_1902825736;
    }

    public function getInfoBlocks($_442383325)
    {
        $_1185842533 = array();
        foreach ($_442383325['CHILDREN'] as $_249767294) {
            if ($_249767294['CLASS_ID'] == 'CondIBIBlock') {
                array_push($_1185842533, $_249767294['DATA']['value']);
            }
            if ($_249767294['CLASS_ID'] == 'CondGroup') {
                foreach ($_249767294['CHILDREN'] as $_817150337) {
                    if ($_817150337['CLASS_ID'] == 'CondIBIBlock') {
                        array_push($_1185842533, $_817150337['DATA']['value']);
                    }
                }
            }
        }
        if (count($_1185842533) > 0) {
            return $_1185842533;
        } else {
            return false;
        }
    }

    public function getChilden($_1295845711, $_1966716165)
    {
        $_995508359 = array();
        foreach ($_1295845711 as $_249767294) {
            if ($_249767294['CLASS_ID'] == 'CondIBSection') {
                if ($_1966716165) {
                    array_push($_995508359, array('SECTION_ID' => $_249767294['DATA']['value'], 'INCLUDE_SUBSECTIONS' => 'Y'));
                } else {
                    array_push($_995508359, array('!SECTION_ID' => $_249767294['DATA']['value'], 'INCLUDE_SUBSECTIONS' => 'Y'));
                }
            } elseif ($_249767294['CLASS_ID'] == 'CondIBXmlID') {
                if ($_1966716165) {
                    array_push($_995508359, array('XML_ID' => $_249767294['DATA']['value']));
                } else {
                    array_push($_995508359, array('!XML_ID' => $_249767294['DATA']['value']));
                }
            } elseif ($_249767294['CLASS_ID'] == 'CondIBName') {
                if ($_1966716165) {
                    array_push($_995508359, array('NAME' => $_249767294['DATA']['value']));
                } else {
                    array_push($_995508359, array('!NAME' => $_249767294['DATA']['value']));
                }
            } elseif ($_249767294['CLASS_ID'] == 'CondIBElement') {
                if ($_1966716165) {
                    array_push($_995508359, array('ID' => $_249767294['DATA']['value']));
                } else {
                    array_push($_995508359, array('!ID' => $_249767294['DATA']['value']));
                }
            } elseif ($_249767294['CLASS_ID'] == 'CondIBCode') {
                if ($_1966716165) {
                    array_push($_995508359, array('CODE' => $_249767294['DATA']['value']));
                } else {
                    array_push($_995508359, array('!CODE' => $_249767294['DATA']['value']));
                }
            } elseif (strpos($_249767294['CLASS_ID'], 'CondIBProp') !== false) {
                $_977447124 = explode(':', $_249767294['CLASS_ID']);
                $_1905260242 = $_977447124[2];
                if ($_1966716165) {
                    array_push($_995508359, array('PROPERTY_' . $_1905260242 => $_249767294['DATA']['value']));
                } else {
                    array_push($_995508359, array('!PROPERTY_' . $_1905260242 => $_249767294['DATA']['value']));
                }
            }
        }
        return $_995508359;
    }

    private function __1118222750($_116914788, $_1361652619, $_1412559735, $_1981991763, $_800147747)
    {
        $_943870346 = array('ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array('IBLOCK_ID' => $_800147747, 'PROPERTY_' . $_116914788 => $_1361652619,)));
        $_1862530086 = CIBlockElement::GetList(Array(), $_943870346, false, Array(), array('ID'));
        while ($_915215541 = $_1862530086->fetch()) {
            $_1941151456[] = $_915215541['ID'];
        }
        if (is_array($_1941151456) && (count($_1941151456) > 0)) {
            if (isset($_1412559735[0])) {
                array_push($_1412559735[0], array($_1981991763 . 'ID' => $_1941151456));
            } else {
                $_1412559735 = array_merge($_1412559735, array($_1981991763 . 'ID' => $_1941151456));
            }
        }
        return $_1412559735;
    }

    public function getFilter($_270023601)
    {
        $_442383325 = $_270023601;
        if (is_array($_442383325['CHILDREN']) && (count($_442383325['CHILDREN']) > 0)) {
            $_1412559735 = array('INCLUDE_SUBSECTIONS' => 'Y', array('LOGIC' => $_442383325['DATA']['All'],));
            $_1023156526 = 0;
            $_1184027514 = 0;
            foreach ($_442383325['CHILDREN'] as $_1347225655 => $_249767294) {
                if (strpos($_249767294['CLASS_ID'], 'CondIBPrice') !== false) {
                    $_1184027514++;
                }
            }
        } elseif (is_array($_442383325['RULE3']) && (count($_442383325['RULE3']) > 0)) {
            $_1412559735 = array('INCLUDE_SUBSECTIONS' => 'Y', array('LOGIC' => $_442383325['DATA']['All'],));
            array_push($_1412559735[0], array('ID' => 0));
        }
        if ($_442383325['DATA']['True'] == 'True') {
            $_1308771574 = true;
        } else {
            $_1308771574 = false;
        }
        if (CModule::IncludeModule('iblock')) {
            $_1862530086 = CIBlockElement::GetList(array(), array('ID' => $this->_978439892['PRODUCT_ID']));
            if ($_1568352059 = $_1862530086->GetNext()) $_1380744813 = $_1568352059['IBLOCK_SECTION_ID'];
        }
        foreach ($_442383325['CHILDREN'] as $_1347225655 => $_249767294) {
            if ($_249767294['DATA']['logic']) $_1981991763 = $_249767294['DATA']['logic'];
            if ($_1308771574) {
                if ($_1981991763 == 'Not') {
                    $_1981991763 = '!';
                } else {
                    $_1981991763 = ' ';
                }
            } else {
                if ($_1981991763 == 'Not') {
                    $_1981991763 = ' ';
                } else {
                    $_1981991763 = '!';
                }
            }
            if ($_249767294['CLASS_ID'] == 'CondIBSection') {
                array_push($_1412559735[0], array($_1981991763 . 'SECTION_ID' => ($_249767294['DATA']['value'] != '') ? $_249767294['DATA']['value'] : $_1380744813, 'INCLUDE_SUBSECTIONS' => 'Y'));
            } elseif ($_249767294['CLASS_ID'] == 'CondIBIBlock') {
                array_push($_1412559735[0], array('IBLOCK_ID' => $_249767294['DATA']['value']));
            } elseif ($_249767294['CLASS_ID'] == 'CondIBXmlID') {
                array_push($_1412559735[0], array($_1981991763 . 'XML_ID' => $_249767294['DATA']['value']));
            } elseif ($_249767294['CLASS_ID'] == 'CondIBName') {
                array_push($_1412559735[0], array($_1981991763 . 'NAME' => $_249767294['DATA']['value']));
            } elseif ($_249767294['CLASS_ID'] == 'CondIBElement') {
                array_push($_1412559735[0], array($_1981991763 . 'ID' => $_249767294['DATA']['value']));
            } elseif ($_249767294['CLASS_ID'] == 'CondIBCode') {
                array_push($_1412559735[0], array($_1981991763 . 'CODE' => $_249767294['DATA']['value']));
            } elseif (strpos($_249767294['CLASS_ID'], 'CondIBPrice') !== false) {
                $_1804124921 = str_replace('CondIBPrice', '', $_249767294['CLASS_ID']);
                ($_249767294['DATA']['logic'] != '') ? $_289441088 = $_249767294['DATA']['logic'] : $_289441088 = '';
                $_289441088 = $this->convertLogic($_289441088, $_1308771574);
                if ($_1023156526 == 0) {
                    $_1131581348 = array('LOGIC' => $_442383325['DATA']['All'], array('ACTIVE' => 'Y'), array($_289441088 . 'CATALOG_PRICE_' . $_1804124921 => $_249767294['DATA']['value']),);
                    $_198230311 = array('IBLOCK_ID' => $this->_1677848281, 'INCLUDE_SUBSECTIONS' => 'Y', $_1131581348,);
                } else {
                    array_push($_1131581348, array($_289441088 . 'CATALOG_PRICE_' . $_1804124921 => $_249767294['DATA']['value']));
                    $_198230311 = array('IBLOCK_ID' => $this->_1677848281, 'INCLUDE_SUBSECTIONS' => 'Y', $_1131581348,);
                }
                if ($_1023156526 == ($_1184027514 - 1)) {
                    $_301262208 = CIBlockElement::GetList(array('SORT' => 'ASC'), $_198230311, false, array(), array());
                    $_61703395 = array();
                    $_1943777521 = 0;
                    while ($_1862530086 = $_301262208->GetNext()) {
                        $_61703395[$_1943777521]['ID'] = $_1862530086['ID'];
                        $_1943777521++;
                    }
                    if (!isset($_1748897348)) $_1748897348 = array();
                    foreach ($_61703395 as $_555218906) {
                        $_534680834 = CCatalogSku::GetProductInfo($_555218906['ID'], $this->_1677848281);
                        array_push($_1748897348, $_534680834['ID']);
                        $this->insertIdToFilter($_1412559735, $_534680834['ID'], $_1981991763);
                    }
                }
                $_289441088 = $this->convertLogic($_249767294['DATA']['logic'], $_1308771574);
                $_494394586 = array($_289441088 . 'CATALOG_PRICE_' . $_1804124921 => $_249767294['DATA']['value']);
                array_push($_1412559735[0], $_494394586);
                $_1023156526++;
            } elseif (strpos($_249767294['CLASS_ID'], 'CondIBProp') !== false) {
                $_977447124 = explode(':', $_249767294['CLASS_ID']);
                $_1905260242 = $_977447124[2];
                $_800147747 = $_977447124[1];
                $_1674487108 = CCatalogSKU::GetInfoByOfferIBlock($_800147747);
                $_1238536243 = $_249767294['DATA']['value'];
                $_1712258680 = CIBlockElement::GetProperty($_800147747, $this->_978439892['PRODUCT_ID'], array('sort' => 'asc'), array('ID' => $_1905260242));
                $_1713410051 = array();
                while ($_1571786514 = $_1712258680->GetNext()) {
                    array_push($_1713410051, $_1571786514['VALUE']);
                }
                $_851757919 = Array('PROPERTY_' . $_1905260242);
                $_943870346 = Array('ID' => $this->_978439892['ELEMENT_ID']);
                $_1862530086 = CIBlockElement::GetList(Array(), $_943870346, false, Array(), $_851757919);
                $_1568352059 = $_1862530086->fetch();
                if ($_1238536243 == '') {
                    $_1238536243 = $_1713410051;
                }
                $_494394586 = array($_1981991763 . 'PROPERTY_' . $_1905260242 => $_1238536243);
                $_962253960 = CIBlockProperty::GetList(array(), array('ID' => $_1905260242));
                if ($_1289553103 = $_962253960->GetNext()) {
                    $_116914788 = $_1289553103['CODE'];
                    $_1956117034 = $_1289553103['USER_TYPE'];
                    $_828409763 = $_1289553103['PROPERTY_TYPE'];
                }
                if ($_1674487108) {
                    $_924727861 = $_1289553103['USER_TYPE_SETTINGS']['TABLE_NAME'];
                    $_1077222955 = $_249767294['DATA']['value'];
                    if ($_1077222955 == '') {
                        $_1862530086 = CCatalogSKU::getOffersList($this->_978439892['PRODUCT_ID'], '', $_219605444 = array(), $_630760951 = array(), $_1953932205 = array('ID' => array($_1905260242)));
                        $_185165775 = array();
                        if (is_array($_1862530086[$this->_978439892['PRODUCT_ID']])) {
                            foreach ($_1862530086[$this->_978439892['PRODUCT_ID']] as $_11317761) {
                                foreach ($_11317761['PROPERTIES'] as $_1962754489) {
                                    if ($_1962754489['VALUE'] != '') array_push($_185165775, $_1962754489['VALUE']);
                                }
                            }
                        }
                    }
                    if ($_1956117034 == 'directory' && $_828409763 == 'S') {
                        if ($_1077222955 == '') {
                            if (is_array($_185165775)) {
                                $_1177772250 = array();
                                foreach ($_185165775 as $_451191334) {
                                    $_1412559735[0][$_1981991763 . 'ID'] = array();
                                    $_1412559735 = $this->__1118222750($_116914788, $_451191334, $_1412559735, $_1981991763, $_800147747);
                                    array_push($_1177772250, $_1412559735[0][$_1981991763 . 'ID']);
                                }
                            }
                            if (count($_1177772250) > 1) {
                                $_263551425 = $_1177772250[0];
                                for ($_1943777521 = 0; $_1943777521 < count($_1177772250); $_1943777521++) {
                                    $_263551425 = array_intersect($_263551425, $_1177772250[$_1943777521]);
                                }
                                $_1412559735[0][$_1981991763 . 'ID'] = $_263551425;
                            } else {
                                foreach ($_1177772250 as $_1367225421) {
                                    $_1412559735[0][$_1981991763 . 'ID'] = $_1367225421;
                                }
                            }
                        } else {
                            $_658494993 = $this->__1647633292($_1077222955, $_924727861);
                            $_1412559735 = $this->__1118222750($_116914788, $_658494993, $_1412559735, $_1981991763, $_800147747);
                        }
                    } else {
                        $_1412559735 = $this->__1118222750($_116914788, $_249767294['DATA']['value'], $_1412559735, $_1981991763, $_800147747);
                    }
                } else {
                    array_push($_1412559735[0], $_494394586);
                }
            } elseif ($_249767294['CLASS_ID'] == 'CondGroup') {
                $_1781130938 = ($_249767294['DATA']['True'] == 'True') ? true : false;
                $_663564368 = $this->getChilden($_249767294['CHILDREN'], $_1781130938, $_1568352059);
                $_1502065158 = array_merge(Array('INCLUDE_SUBSECTIONS' => 'Y', 'LOGIC' => $_249767294['DATA']['All'],), $_663564368);
                array_push($_1412559735[0], $_1502065158);
            }
        }
        if (is_array($_442383325['RULE3'])) {
            $_1412559735 = array('INCLUDE_SUBSECTIONS' => 'Y', array('LOGIC' => 'OR', $_1412559735[0]));
            foreach ($_442383325['RULE3'] as $_1024196622) {
                $_1902944396 = explode(':', $_1024196622);
                $_1024196622 = $_1902944396[0];
                $_1862530086 = CIBlockElement::GetProperty($_1902944396[1], $this->_978439892['PRODUCT_ID'], 'sort', 'asc', array('ID' => $_1024196622));
                while ($_1568352059 = $_1862530086->GetNext()) {
                    if ($_1568352059['VALUE']) $_2023017624[] = $_1568352059['VALUE'];
                }
            }
            if (is_array($_2023017624) && (count($_2023017624) > 0)) {
                array_push($_1412559735[0], array('ID' => $_2023017624));
            }
        }
        return $_1412559735;
    }

    public function getProducts($_270023601)
    {
        $_1092949799 = $this->getFilter($_270023601);
        $_148795745 = array('PRODUCTS' => array(),);
        $_1245923451 = 0;
        Loader::includeModule('iblock');
        $_232529584 = CIBlockElement::GetList(array(), $_1092949799, false, false, array());
        while ($_1655667124 = $_232529584->fetch()) {
            array_push($_148795745['PRODUCTS'], array('ID' => $_1655667124['ID'], 'IBLOCK_ID' => $_1655667124['IBLOCK_ID']));
            $_1245923451++;
        }
        $_148795745['COUNTER'] = $_1245923451;
        return $_148795745;
    }

    public function generateCondition($_1367225421 = 0)
    {
        if ($_1367225421 == 0) {
            $_1787891218 = CrosssellTable::getList(array('select' => array('ID', 'RULE1', 'TYPE_BLOCK', "RULE2"), 'filter' => array('Active' => 'Y')));
        } else {
            $_1787891218 = CrosssellTable::getList(array('select' => array('ID', 'RULE1', 'TYPE_BLOCK', 'RULE2'), 'filter' => array('Active' => 'Y', 'ID' => $_1367225421)));
        }
        $_206196299 = 0;
        while ($_915215541 = $_1787891218->fetch()) {
            $_39751551 = 0;
            if ($_915215541['TYPE_BLOCK'] == 'CROSSSELL') {
                $_833598682 = new \SotbitCrosssellCatalogCondTree();
                $_1850119558 = $_833598682->Init(BT_COND_MODE_PARSE, BT_COND_BUILD_CATALOG, array());
                $_270023601 = unserialize($_915215541['RULE1']);
                $_148795745 = $this->getProducts($_270023601);
                $_206196299 = $_148795745['COUNTER'];
            } else if ($_915215541['TYPE_BLOCK'] == 'COLLECTION') {
                $_148795745 = $this->getProducts(unserialize($_915215541['RULE1']));
                $_148795745['PRODUCTS'] = '';
            }
            $_1765135931 = array('NUMBER_OF_MATCHED_PRODUCTS' => $_148795745['COUNTER'], 'PRODUCTS' => serialize($_148795745['PRODUCTS']));
            CrosssellTable::update($_915215541['ID'], $_1765135931);
        }
        return $_206196299;
    }

    private function __1647633292($_1077222955, $_924727861)
    {
        \Bitrix\Main\Loader::IncludeModule("highloadblock");
        $_1995366041 = ['UF_XML_ID'];
        $_1092949799 = ['ID' => $_1077222955];
        $_159769090 = 1;
        $_629026490 = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter' => array('TABLE_NAME' => $_924727861)))->fetch();
        $_1944948743 = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($_629026490);
        $_1111783121 = new \Bitrix\Main\Entity\Query($_1944948743);
        $_1111783121->setSelect($_1995366041);
        $_1111783121->setFilter($_1092949799);
        $_1111783121->setOrder([]);
        $_1111783121->setLimit($_159769090);
        $_263551425 = $_1111783121->exec();
        $_263551425 = $_263551425->fetch();
        $_658494993 = $_263551425['UF_XML_ID'];
        return $_658494993;
    }
}

class DataManagerCrosssell extends Bitrix\Main\Entity\DataManager
{
    public static function getList(array $_1782806433 = array())
    {
//        if (!SotbitCrosssell::getDemo())
//            return
        new Bitrix\Main\ORM\Query\Result(parent::query(), new \Bitrix\Main\DB\ArrayResult(array()));
//        else
            return parent::getList($_1782806433);
    }

    public static function getById($_1367225421 = "")
    {
//        if (!SotbitCrosssell::getDemo())
//            return
        new \CDBResult;
//        else
            return parent::getById($_1367225421);
    }

    public static function add(array $_1940318754 = array())
    {
//        if (!SotbitCrosssell::getDemo())
//            return
        new \Bitrix\Main\Entity\AddResult();
//        else
            return parent::add($_1940318754);
    }

    public static function update($_1367225421 = "", array $_1940318754 = array())
    {
//        if (!SotbitCrosssell::getDemo())
//            return
        new \Bitrix\Main\Entity\UpdateResult();
//        else
            return parent::update($_1367225421, $_1940318754);
    }
} ?>