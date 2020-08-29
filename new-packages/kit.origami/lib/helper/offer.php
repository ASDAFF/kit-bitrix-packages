<?php

namespace Kit\Origami\Helper;

use Bitrix\Iblock\PropertyTable;
use Kit\Origami\Config\Option;

class Offer
{
    protected $text;
    protected $delimeter;
    public function __construct($lid = SITE_ID) {
        $this->text = Option::get('OFFER_NAME',$lid);
        $this->delimeter = Option::get('OFFER_NAME_DELIMETER',$lid);
    }
    public function changeText($arResult = [],$props = []){
        $return = $arResult['OFFERS'][0]['NAME'];
        if(strpos($this->text,'{') !== false){
            $return = $this->text;

            preg_match_all('#\{=prop.Name\}(.*?)\{=prop.Value\}#', $return, $combo);
            if($combo[0][0]){
                $return = str_replace($combo[0][0],'{=prop.Combo}',$return);
            }

            preg_match_all('#\{=prop.Value\}(.*?)\{=prop.Name\}#', $return, $comboR);
            if($comboR[0][0]){
                $return = str_replace($comboR[0][0],'{=prop.ComboR}',$return);
            }

            preg_match_all('#\{(.*?)\}#', $return, $m);


            /*$arResult['PROPERTIES'] = [];
            $arResult['OFFERS'][0]['PROPERTIES'] = [];*/
            if($m[0]){
                $pProps = [];
                $pPropsNames = [];
                $oProps = [];
                $oPropsNames = [];
                $comboProps = [];
                $hls = [];

                foreach($m[0] as $mask){
                    switch ($mask){
                        case '{=product.Name}':
                            $return = str_replace('{=product.Name}',$arResult['NAME'],$return);
                            break;
                        case '{=offer.Name}':
                            $return = str_replace('{=offer.Name}',$arResult['OFFERS'][0]['NAME'],$return);
                            break;
                        case '{=prop.Name}':
                            if($props){
                                $names = [];
                                foreach($props as $prop){
                                    if($arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE']){
                                        $names[] = strtolower($arResult['OFFERS'][0]['PROPERTIES'][$prop]['NAME']);
                                    }
                                    else{
                                        $oPropsNames[] = $prop;
                                    }
                                }
                                if($names) {
                                    $names = implode($this->delimeter, $names);
                                    $return = str_replace('{=prop.Name}',$names, $return);
                                }
                            }
                            break;
                        case '{=prop.Value}':
                            if($props){
                                $values = [];
                                foreach($props as $prop){
                                    if($arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE']){
                                        if($arResult['OFFERS'][0]['PROPERTIES'][$prop]['USER_TYPE_SETTINGS']['TABLE_NAME']){
                                            $hls[$arResult['OFFERS'][0]['PROPERTIES'][$prop]['USER_TYPE_SETTINGS']['TABLE_NAME']][] = $arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE'];
                                        }
                                        $values[] = $arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE'];
                                    }
                                    else{
                                        $oProps[] = $prop;
                                    }
                                }
                                if($values) {
                                    $values = implode($this->delimeter,
                                        $values);
                                    $return = str_replace('{=prop.Value}',
                                        $values, $return);
                                }
                            }
                            break;
                        case '{=prop.Combo}':
                            if($props){
                                $values = [];
                                foreach($props as $prop){
                                    if($arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE']){
                                        if($arResult['OFFERS'][0]['PROPERTIES'][$prop]['USER_TYPE_SETTINGS']['TABLE_NAME']){
                                            $hls[$arResult['OFFERS'][0]['PROPERTIES'][$prop]['USER_TYPE_SETTINGS']['TABLE_NAME']][] = $arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE'];
                                        }
                                        $values[] = strtolower($arResult['OFFERS'][0]['PROPERTIES'][$prop]['NAME']).$combo[1][0].$arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE'];
                                    }
                                    else{
                                        $comboProps[] = $prop;
                                    }
                                }
                                if($values) {
                                    $values = implode($this->delimeter,
                                        $values);
                                    $return = str_replace('{=prop.Combo}',
                                        $values, $return);
                                }
                            }
                            break;
                        case '{=prop.ComboR}':
                            if($props){
                                $values = [];
                                foreach($props as $prop){
                                    if($arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE']){
                                        if($arResult['OFFERS'][0]['PROPERTIES'][$prop]['USER_TYPE_SETTINGS']['TABLE_NAME']){
                                            $hls[$arResult['OFFERS'][0]['PROPERTIES'][$prop]['USER_TYPE_SETTINGS']['TABLE_NAME']][] = $arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE'];
                                        }
                                        $values[] = $arResult['OFFERS'][0]['PROPERTIES'][$prop]['VALUE'].$comboR[1][0].strtolower($arResult['OFFERS'][0]['PROPERTIES'][$prop]['NAME']);
                                    }
                                    else{
                                        $comboProps[] = $prop;
                                    }
                                }
                                if($values) {
                                    $values = implode($this->delimeter,
                                        $values);
                                    $return = str_replace('{=prop.ComboR}',
                                        $values, $return);
                                }
                            }
                            break;
                        default:
                            $return = str_replace($mask,'', $return);
                            break;
                    }
                }

                if($comboProps && $arResult['OFFERS'][0]['ID']){
                    $comboNames = [];
                    $rs = PropertyTable::getList(
                        [
                            'select' => ['NAME','CODE'],
                            'filter' => ['CODE' => $comboProps],
                            'cache'  => [
                                'ttl' => 36000000,
                            ],
                        ]
                    );
                    while($prop = $rs->fetch()){
                        $comboNames[$prop['CODE']] = $prop['NAME'];
                    }

                    $select = [
                        'ID',
                        'IBLOCK_ID',
                    ];
                    foreach ($comboProps as $prop){
                        $select[] = 'PROPERTY_'.$prop;
                    }

                    $el = [];
                    $cache = \Bitrix\Main\Data\Cache::createInstance();
                    if ($cache->initCache(36000000, "origami_cache_props_".$arResult['OFFERS'][0]['ID'])) {
                        $el = $cache->getVars();
                    }
                    elseif ($cache->startDataCache()) {
                        $rs = \CIBlockElement::GetList([],['ID' => $arResult['OFFERS'][0]['ID']],false,false,$select);
                        if($el = $rs->Fetch()){

                        }
                        else{
                            $el = [];
                        }
                        $cache->endDataCache($el);
                    }
                    if($el){
                        $props = [];
                        foreach ($comboProps as $prop) {
                            if (isset($el['PROPERTY_'.$prop.'_VALUE'])) {
                                $props[$prop] = $el['PROPERTY_'.$prop.'_VALUE'];
                            }
                        }
                        if($props) {
                            $aCombo = [];
                            $aComboR = [];
                            foreach($props as $code => $value){
                                $aCombo[] = strtolower($comboNames[$code]).$combo[1][0].$value;
                                $aComboR[] = $value.$comboR[1][0].strtolower($comboNames[$code]);
                            }


                            $aCombo = implode($this->delimeter, $aCombo);
                            $aComboR = implode($this->delimeter, $aComboR);
                            $return = str_replace('{=prop.Combo}', $aCombo, $return);
                            $return = str_replace('{=prop.ComboR}', $aComboR, $return);
                        }
                        $rs = PropertyTable::getList(
                            [
                                'filter' => ['CODE' => $comboProps,'LIST_TYPE' => 'L','USER_TYPE' => 'directory'],
                                'cache'  => [
                                    'ttl' => 36000000,
                                ],
                            ]
                        );
                        while($prop = $rs->fetch()){
                            $settings = unserialize($prop['USER_TYPE_SETTINGS']);
                            if($settings['TABLE_NAME']){
                                if($el['PROPERTY_'.$prop['CODE'].'_VALUE']){
                                    $hls[$settings['TABLE_NAME']][] = $el['PROPERTY_'.$prop['CODE'].'_VALUE'];
                                }
                            }
                        }
                    }
                }

                if($pProps && $arResult['ID']){
                    $select = [
                        'ID',
                        'IBLOCK_ID',
                    ];
                    foreach ($pProps as $prop){
                        $select[] = 'PROPERTY_'.$prop;
                    }

                    $el = [];
                    $cache = \Bitrix\Main\Data\Cache::createInstance();
                    if ($cache->initCache(36000000, "origami_cache_pprops_".$arResult['ID'])) {
                        $el = $cache->getVars();
                    }
                    elseif ($cache->startDataCache()) {
                        $rs = \CIBlockElement::GetList([],['ID' => $arResult['ID']],false,false,$select);
                        if($el = $rs->GetNext()){

                        }
                        else{
                            $el = [];
                        }
                        $cache->endDataCache($el);
                    }
                    if($el){
                        foreach ($pProps as $prop){
                            if(isset($el['PROPERTY_'.$prop.'_VALUE']))
                            {
                                $return = str_replace('{=ProductProperty.'.$prop.'}',$el['PROPERTY_'.$prop.'_VALUE'],$return);
                            }
                            else{
                                $return = str_replace('{=ProductProperty.'.$prop.'}','',$return);
                            }
                        }
                    }
                }
                if($pPropsNames){
                    $rs = PropertyTable::getList(
                        [
                            'select' => ['NAME','CODE'],
                            'filter' => ['CODE' => $pPropsNames],
                            'cache'  => [
                                'ttl' => 36000000,
                            ],
                        ]
                    );
                    while($prop = $rs->fetch()){
                        $return = str_replace('{=ProductPropertyName.'.$prop['CODE'].'}',strtolower($prop['NAME']),$return);
                    }
                }
                if($oProps && $arResult['OFFERS'][0]['ID']){
                    $select = [
                        'ID',
                        'IBLOCK_ID',
                    ];
                    foreach ($oProps as $prop){
                        $select[] = 'PROPERTY_'.$prop;
                    }

                    $el = [];
                    $cache = \Bitrix\Main\Data\Cache::createInstance();
                    if ($cache->initCache(36000000, "origami_cache_oprops_".$arResult['OFFERS'][0]['ID'])) {
                        $el = $cache->getVars();
                    }
                    elseif ($cache->startDataCache()) {
                        $rs = \CIBlockElement::GetList([],['ID' => $arResult['OFFERS'][0]['ID']],false,false,$select);
                        if($el = $rs->Fetch()){

                        }
                        else{
                            $el = [];
                        }
                        $cache->endDataCache($el);
                    }
                    if($el){
                        $props = [];
                        foreach ($oProps as $prop) {
                            if (isset($el['PROPERTY_'.$prop.'_VALUE'])) {
                                $props[] = $el['PROPERTY_'.$prop.'_VALUE'];
                            }
                        }
                        if($props) {
                            $props = implode($this->delimeter, $props);
                            $return = str_replace('{=prop.Value}', $props,
                                $return);
                        }
                        $rs = PropertyTable::getList(
                            [
                                'filter' => ['CODE' => $oProps,'LIST_TYPE' => 'L','USER_TYPE' => 'directory'],
                                'cache'  => [
                                    'ttl' => 36000000,
                                ],
                            ]
                        );
                        while($prop = $rs->fetch()){
                            $settings = unserialize($prop['USER_TYPE_SETTINGS']);
                            if($settings['TABLE_NAME']){
                                if($el['PROPERTY_'.$prop['CODE'].'_VALUE']){
                                    $hls[$settings['TABLE_NAME']][] = $el['PROPERTY_'.$prop['CODE'].'_VALUE'];
                                }
                            }
                        }
                    }
                }
                if($oPropsNames){
                    $names = [];
                    $rs = PropertyTable::getList(
                        [
                            'select' => ['NAME','CODE'],
                            'filter' => ['CODE' => $oPropsNames],
                            'cache'  => [
                                'ttl' => 36000000,
                            ],
                        ]
                    );
                    while($prop = $rs->fetch()){
                        $names[] = $prop['NAME'];
                    }
                    $names = implode($this->delimeter,$names);
                    $return = str_replace('{=prop.Name}',$names,$return);

                }
                if($hls){
                    $rs = \Bitrix\Highloadblock\HighloadBlockTable::getList(
                        [
                            'filter' => ['TABLE_NAME' => array_keys($hls)],
                            'cache'  => [
                                'ttl' => 36000000,
                            ],
                        ]
                    );
                    while($hl = $rs->fetch()){
                        $entity   = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity( $hl );
                        $entityClass = $entity->getDataClass();
                        $rst = $entityClass::getList(
                            [
                                'filter' => ['UF_XML_ID' => $hls[$hl['TABLE_NAME']]],
                                'cache'  => [
                                    'ttl' => 36000000,
                                ],
                            ]
                        );
                        while($row = $rst->fetch()){
                            if(isset($row['UF_NAME'])){
                                $return = str_replace($row['UF_XML_ID'],$row['UF_NAME'],$return);
                            }
                        }
                    }
                }
            }

            $return = str_replace(['{=product.Name}','{=offer.Name}','{=prop.Value}','{=prop.Name}','{=prop.Combo}','{=prop.ComboR}'],'',$return);

            $return = trim($return);
        }

        return $return;
    }
}
