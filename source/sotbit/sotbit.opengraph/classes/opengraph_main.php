<?
use Sotbit\Opengraph\OpengraphPageMetaTable;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

class OpengraphMain {
    private $application;
    private $memory;
    private static $meta = array();
    private $siteId;
    private static $defaultMeta = array(
        'OG' => array(),
        'TW' => array()
    );
    
    public function __construct($siteId = SITE_ID) {
        $this->memory = memory_get_usage();
        $this->siteId = $siteId;
        
        global $APPLICATION;
        $this->application = $APPLICATION;
    }
    
    public static function setMetaOnPageStart($siteId = SITE_ID) {
        $opengraph = new OpengraphMain($siteId);
        //$opengraph->showDefaultMeta($siteId);
        //$opengraph->setDefaultMeta($siteId);
        $opengraph->setMetaSetting($siteId);
        $opengraph->setMetaRuleOnPageStart($siteId);
    }
    
    private function setMetaRuleOnPageStart($siteID = SITE_ID) {
        $url = $this->application->GetCurPage(false);
        $opengraph = new OpengraphCore();
        $rs = $opengraph->getRulePathExact($url);
    
        if($rs->getSelectedRowsCount() < 1) {
            $rs = $opengraph->getRulePathTemplate();
            $rs = $opengraph->getFirstRightRule($rs, $url);
        }
        else {
            $rs = $rs->fetch();
        }
    
        if(is_array($rs) && !empty($rs)) {
            $core = new OpengraphCore();
            $ogMeta = array_filter($core->getMetaArray('OG', $rs), function($v, $k){
                return !empty($v);
            } , ARRAY_FILTER_USE_BOTH);
            $twMeta = array_filter($core->getMetaArray('TW', $rs), function($v, $k){
                return !empty($v);
            } , ARRAY_FILTER_USE_BOTH);
            //
            foreach($ogMeta as $metaKey => $metaValue) {
                $key = strtolower(preg_replace("/[_]+/", ':', $metaKey, 2));
                self::setMeta($key, $metaValue, $siteID);
            }
            
            foreach($twMeta as $metaKey => $metaValue) {
                $key = str_replace('tw:', 'twitter:', strtolower(preg_replace("/[_]+/", ':', $metaKey, 2)));
                self::setMeta($key, $metaValue, $siteID);
            }
        }
    }
    
    private function setMetaSetting($siteId = SITE_ID) {
        OpengraphSettings::getInstance()->refreshOptions();
        $arFields = OpengraphSettings::getInstance()->getOptions();
        $core = new OpengraphCore();
    
        global $APPLICATION;
    
        if(is_array($arFields['EXCEPTION_URL']) && $core->checkUrlTemplateList($arFields['EXCEPTION_URL'], $APPLICATION->GetCurPage(false)))
            return;
    
        $arFields['ACTIVE_TW'] = 'Y';
        $arFields['ACTIVE_OG'] = 'Y';
    
        $activeOg = array_filter($arFields, function($val, $key) use(&$activeOg) {
            return strpos($key, 'OG') === 0 && strpos($key, 'PROPS_ACTIVE') === false && !empty($val);
        }, ARRAY_FILTER_USE_BOTH);
    
        $activeTw = array_filter($arFields, function($val, $key) use(&$activeTw) {
            return strpos($key, 'TW') === 0 && strpos($key, 'PROPS_ACTIVE') === false && !empty($val);
        }, ARRAY_FILTER_USE_BOTH);
        $activeOg['OG_URL'] = $_SERVER['SCRIPT_URI'];
    
        $arFields['OG_PROPS_ACTIVE'] = serialize(array_map(function($val) {return 1;}, $activeOg));
        $arFields['TW_PROPS_ACTIVE'] = serialize(array_map(function($val) {return 1;}, $activeTw));
        $arFields['OG_URL'] = $_SERVER['SCRIPT_URI'];
    
        $ogMeta = $core->getMetaArray('OG', $arFields);
        $twMeta = $core->getMetaArray('TW', $arFields);
    
        $this->setMetaByArray($ogMeta, $siteId);
        $this->setMetaByArray($twMeta, $siteId);
    }
    
    private function setMetaByArray(array $meta, $siteId = SITE_ID) {
        foreach($meta as $key => $value) {
            $key = str_replace('tw:', 'twitter:', strtolower(preg_replace("/[_]+/", ':', $key, 2)));
        
            if($key) {
                self::setMeta($key, $value, $siteId);
            }
        }
    }
    
    public function setScriptUrlMeta($siteId = SITE_ID) {
        ksort(self::$meta[$siteId]);

        if(isset(self::$meta[$siteId]) && is_array(self::$meta[$siteId]))
            foreach(self::$meta[$siteId] as $metaKey => $metaValue)
                //$this->application->SetPageProperty($metaKey, $metaValue);
                $this->application->AddHeadString('<meta property="'.$metaKey.'" content="'.$metaValue.'">');
    }
    
    public static function setMeta($nameProperty, $value, $siteId = SITE_ID) {
        self::$meta[$siteId][$nameProperty] = $value;
    }
    
    public static function setImageMeta($nameProperty, $value, $siteId = SITE_ID) {
        if($nameProperty == 'og:image') {
            
            $arInfo = getimagesize($_SERVER['DOCUMENT_ROOT'].$value);
            self::$meta[$siteId]['og:image:width'] = $arInfo[0];
            self::$meta[$siteId]['og:image:height'] = $arInfo[1];
            self::$meta[$siteId]['og:image:type'] = $arInfo['mime'];
        }
        
        if(self::isLocalUrl($value)) {
            $value = OpengraphCore::getHttpSchema().'://'.$_SERVER['SERVER_NAME'].$value;
        }
        
        self::$meta[$siteId][$nameProperty] = $value;
    }
    
    private static function isLocalUrl($url)
    {
        if(!preg_match("/^(http|https|ftp)?(:?\/\/)?([A-Z0-9][A-Z0-9_-]*(?:\..[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?/Diu", $url))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function setUrlMeta($siteId = SITE_ID) {
        global $APPLICATION;
        $url = $APPLICATION->GetCurPage(false);
    
        $opengraph = new OpengraphCore();
        
        $rs = $opengraph->getRulePathExactOnEndPage($url);
    
        if($rs->getSelectedRowsCount() < 1) {
            $rs = $opengraph->getRulePathTemplate();
            $rs = $opengraph->getFirstRightRule($rs, $url);
        }
        else {
            $rs = $rs->fetch();
        }
        
        if(is_array($rs) && !empty($rs)) {
            $core = new OpengraphCore();
            $ogMeta = array_filter($core->getMetaArray('OG', $rs), function($v, $k){
                return !empty($v);
            } , ARRAY_FILTER_USE_BOTH);
            $twMeta = array_filter($core->getMetaArray('TW', $rs), function($v, $k){
                return !empty($v);
            } , ARRAY_FILTER_USE_BOTH);
//
            foreach($ogMeta as $metaKey => $metaValue) {
                $key = strtolower(preg_replace("/[_]+/", ':', $metaKey, 2));
                self::setMeta($key, $metaValue, $siteId);
            }
            
            foreach($twMeta as $metaKey => $metaValue) {
                $key = str_replace('tw:', 'twitter:', strtolower(preg_replace("/[_]+/", ':', $metaKey, 2)));
                self::setMeta($key, $metaValue, $siteId);
            }
        }
    }
}

?>