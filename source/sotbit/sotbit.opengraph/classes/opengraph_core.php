<?

use Sotbit\Opengraph\OpengraphPageMetaTable;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

class OpengraphCore {
    public function getMetaArray($keyGroup, array &$arMetaTable) {
        $arMeta = $this->filterMetaArray($keyGroup, $arMetaTable);
        if($keyGroup == 'OG' && isset($arMeta['OG_IMAGE'])) {
            $src = CFile::GetPath($arMeta['OG_IMAGE']);
            if($src != null) {
                $arInfo = getimagesize($_SERVER['DOCUMENT_ROOT'].$src);
                $arMeta['OG_IMAGE_WIDTH'] = $arInfo[0];
                $arMeta['OG_IMAGE_HEIGHT'] = $arInfo[1];
                $arMeta['OG_IMAGE_TYPE'] = $arInfo['mime'];
                $arMeta['OG_IMAGE'] = $this->getHost().$src;
            }
        }
        else if($keyGroup == 'TW' && isset($arMeta['TW_IMAGE'])) {
            $arMeta['TW_IMAGE'] = $this->getHost().CFile::GetPath($arMeta['TW_IMAGE']);
        }
        
        return $arMeta;
    }
    
    private function filterMetaArray($keyGroup, array &$arMetaTable) {
        $keyGroup = strtoupper($keyGroup);
        $active = unserialize($arMetaTable[$keyGroup.'_PROPS_ACTIVE']);
        if($arMetaTable['ACTIVE_'.$keyGroup] == 'Y')
            return array_filter($arMetaTable, function($value, $key) use ($keyGroup, $active) {
                return strpos($key, $keyGroup) === 0 && strpos($key, 'PROPS_ACTIVE') === false && isset($active[$key]) && $active[$key];
            }, ARRAY_FILTER_USE_BOTH);
        else
            return array();
    }
    
    private function getHost() {
        return $this->getHttpSchema().'://'.$_SERVER['SERVER_NAME'];
    }
    
    public function getRulePathExactOnPageStart($url, $siteId = SITE_ID) {
        return $this->getRules(array(
            'NAME' => $url,
            'SITE_ID' => $siteId,
            '=ORDER' => "START_PAGE"
        ));
    }
    
    private function getRules($filter = array(), $order = array('SORT' => 'DESC')) {
        return OpengraphPageMetaTable::getList(array(
            'filter' => $filter,
            'order' => $order
        ));
    }
    
    public function getRulePathExactOnEndPage($url, $siteId = SITE_ID) {
        return $this->getRules(array(
            'NAME' => $url,
            'SITE_ID' => $siteId,
            '!ORDER' => "START_PAGE"
        ));
    }
    
    public function getRulePathExact($url, $siteId = SITE_ID) {
        return $this->getRules(array(
            'NAME' => $url,
            'SITE_ID' => $siteId,
        ));
    }
    
    public function getRulePathTemplate($siteId = SITE_ID) {
        return $this->getRules(array(
            'LOGIC' => 'AND',
            array(
                'LOGIC' => 'OR',
                array('ACTIVE_OG' => 'Y'),
                array('ACTIVE_TW' => 'Y'),
            ),
            '%NAME' => '*',
            'SITE_ID' => $siteId,
        ));
    }
    
    public function getFirstRightRule(\Bitrix\Main\DB\Result $resultList, $url) {
        while($arList = $resultList->fetch()) {
            if($this->checkUrlTemplate($arList['NAME'], $url))
                return $arList;
        }
        
        return array();
    }
    
    public function checkUrlTemplate($template, $url) {
        $template = str_replace(array(
            '*',
            '/'
        ), array(
            '.*',
            "\/"
        ), $template);
        
        return preg_match('/^'.$template.'$/ui', $url);
    }
    
    public function checkUrlTemplateList(array $templateList, $url) {
        foreach($templateList as $template) {
            if($this->checkUrlTemplate($template, $url))
                return true;
        }
        
        return false;
    }
    
    public function getArRightRules(array $resultList, $url) {
        return array_filter($resultList, function($val, $key) use ($url) {
            $name = str_replace(array(
                '*',
                '/'
            ), array(
                '.*',
                "\/"
            ), $val['NAME']);
            
            return preg_match('/^'.$name.'$/ui', $url);
        }, ARRAY_FILTER_USE_BOTH);
    }
    
    public function getHttpSchema() {
        if(isset($_SERVER['HTTPS']))
            $scheme = $_SERVER['HTTPS'];
        else
            $scheme = '';
        
        return (($scheme) && ($scheme != 'off')) ? 'https' : 'http';
    }
}

?>