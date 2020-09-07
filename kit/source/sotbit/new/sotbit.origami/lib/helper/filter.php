<?php

namespace Sotbit\Origami\Helper;

class Filter
{

    private static $position = 'before';

    static private function setBefore()
    {
        self::$position = 'before';
    }

    public function changeElProp($props = [])
    {
        if ($props) {
            global $APPLICATION;
            $curPage = $APPLICATION->GetCurDir();
            $url = explode('/', $curPage);
            $cntUrl = count($url);
            $sectionUrl = '';
            for ($i = 0; $i < $cntUrl - 2; ++$i) {
                if ($url[$i]) {
                    $sectionUrl = $sectionUrl.'/'.$url[$i];
                }
            }
            $sectionUrl .= '/';
            foreach ($props as &$prop) {
                if ($prop['FILTRABLE'] == 'Y') {
                    $filterUrl = 'filter/'.strtolower($prop['CODE']).'-is-'
                        .$prop['VALUE_XML_ID'].'/apply/';
                    $prop['DISPLAY_VALUE'] = '<a onclick="" 
                    href="'.$sectionUrl.$filterUrl.'" title="">'.$prop['DISPLAY_VALUE'].'</a>';
                }
            }
        }
        return $props;
    }

    public function getCharacteristics($iblockID, $sectionID, $sectionURL, $DisplayProperties = array(), $type = "link", $arParams)
    {
        if(empty($DisplayProperties) || $type == "")
            return false;

        $seoModule = \CModule::IncludeModule("sotbit.seometa");
        // get all filter props
        $FilterProps = array ();
        $link = "";
        $arPropsSection = \CIBlockSectionPropertyLink::GetArray($iblockID, $sectionID, $bNewSection = false );

        if(!empty($arPropsSection))
        {
            foreach($arPropsSection as $prop)
            {
                if ($prop['SMART_FILTER'] == "Y")
                    $FilterProps[$prop['PROPERTY_ID']] = $prop['PROPERTY_ID'];
            }
        }

        $sURL = str_replace(array("#SECTION_CODE_PATH#/", "#SECTION_CODE#/"), $sectionURL, $arParams["SEF_URL_TEMPLATES"]["smart_filter"]);

        foreach($DisplayProperties as $code => $arOneProp)
        {
            if(isset($FilterProps[$arOneProp["ID"]]))
            {
                if (is_array($arOneProp['DISPLAY_VALUE']))
                {
                    foreach ($arOneProp['DISPLAY_VALUE'] as $key => $value)
                    {
                        if($arOneProp['PROPERTY_TYPE'] == 'L') // list
                        {
                            if(is_array($arOneProp['VALUE_XML_ID']))
                            {
                                //$link = $sectionURL .  'filter/' . strtolower($code) .'-is-'. toLower($arOneProp['VALUE_XML_ID'][$key]) . '/apply/';
                                $smartPath = strtolower($code) .'-is-'. toLower($arOneProp['VALUE_XML_ID'][$key]);
                            }
                            else{
                                //$link = $sectionURL .  'filter/' . strtolower($code) .'-is-'. toLower($arOneProp['VALUE_XML_ID']) . '/apply/';
                                $smartPath = strtolower($code) .'-is-'. toLower($arOneProp['VALUE_XML_ID']);
                            }

                            $link = str_replace("#SMART_FILTER_PATH#", $smartPath, $sURL);


                            if($seoModule && $type == "seometa")
                            {
                                $arLink = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl($link);
                                if(isset($arLink["NEW_URL"]))
                                    $link = $arLink["NEW_URL"];
                                else
                                    $link = "";
                            }

                            if($link)
                                $DisplayProperties[$code]['DISPLAY_VALUE'][$key] = '<a onclick="" href="' .$link.'" title="'.$DisplayProperties[$code]["NAME"].": ".$value.'">' . $value . '</a>';
                        }
                        elseif($arOneProp['PROPERTY_TYPE'] == 'S')
                        {
                            if(is_array($arOneProp['VALUE']))
                            {
                                //$link = $sectionURL .  'filter/' . strtolower($code) .'-is-'. toLower($arOneProp['VALUE_XML_ID'][$key]) . '/apply/';
                                $smartPath = strtolower($code) .'-is-'. toLower($arOneProp['VALUE'][$key]);
                            }
                            else{
                                //$link = $sectionURL .  'filter/' . strtolower($code) .'-is-'. toLower($arOneProp['VALUE_XML_ID']) . '/apply/';
                                $smartPath = strtolower($code) .'-is-'. toLower($arOneProp['VALUE']);
                            }

                            $link = str_replace("#SMART_FILTER_PATH#", $smartPath, $sURL);


                            if($seoModule && $type == "seometa")
                            {
                                $arLink = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl($link);
                                if(isset($arLink["NEW_URL"]))
                                    $link = $arLink["NEW_URL"];
                                else
                                    $link = "";
                            }

                            if($link)
                                $DisplayProperties[$code]['DISPLAY_VALUE'][$key] = '<a onclick="" href="' .$link.'" title="'.$DisplayProperties[$code]["NAME"].": ".$value.'">' . $value . '</a>';
                        }
                        elseif($arOneProp['PROPERTY_TYPE'] != 'E')
                        {
                            //$link = $sectionURL .  'filter/' . strtolower($code) .'-is-'. strtolower($value) . '/apply/';


                            $smartPath = strtolower($code) .'-is-'. strtolower($value);

                            $link = str_replace("#SMART_FILTER_PATH#", $smartPath, $sURL);

                            if($seoModule && $type == "seometa")
                            {
                                $arLink = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl($link);
                                if(isset($arLink["NEW_URL"]))
                                    $link = $arLink["NEW_URL"];
                                else
                                    $link = "";
                            }

                            if($link)
                                $DisplayProperties[$code]['DISPLAY_VALUE'][$key] = '<a onclick="" href="' .$link.'" title="'.$DisplayProperties[$code]["NAME"].": ".$value.'">' . $value . '</a>';
                        }
                    }
                }else{

                    if($arOneProp['PROPERTY_TYPE'] == 'L') // list
                    {
                        //$link = $sectionURL .  'filter/' . strtolower( $code ) .'-is-'. toLower($arOneProp['VALUE_XML_ID']) . '/apply/';
                        $smartPath = strtolower( $code ) .'-is-'. toLower($arOneProp['VALUE_XML_ID']);
                        $link = str_replace("#SMART_FILTER_PATH#", $smartPath, $sURL);

                        if($seoModule && $type == "seometa")
                        {
                            $arLink = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl($link);
                            if(isset($arLink["NEW_URL"]))
                                $link = $arLink["NEW_URL"];
                            else
                                $link = "";
                        }

                        if($link)
                            $DisplayProperties[$code]['DISPLAY_VALUE'] = '<a onclick="" href="' .$link.'" title="'.$DisplayProperties[$code]["NAME"].": ".$arOneProp['DISPLAY_VALUE'].'">' . $arOneProp['DISPLAY_VALUE'] . '</a>';
                    }
                    elseif($arOneProp['PROPERTY_TYPE'] == 'S')
                    {
                        //$link = $sectionURL .  'filter/' . strtolower( $code ) .'-is-'. toLower($arOneProp['VALUE_XML_ID']) . '/apply/';
                        $smartPath = strtolower( $code ) .'-is-'. toLower($arOneProp['VALUE']);
                        $link = str_replace("#SMART_FILTER_PATH#", $smartPath, $sURL);

                        if($seoModule && $type == "seometa")
                        {
                            $arLink = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl($link);
                            if(isset($arLink["NEW_URL"]))
                                $link = $arLink["NEW_URL"];
                            else
                                $link = "";
                        }

                        if($link)
                            $DisplayProperties[$code]['DISPLAY_VALUE'] = '<a onclick="" href="' .$link.'" title="'.$DisplayProperties[$code]["NAME"].": ".$arOneProp['DISPLAY_VALUE'].'">' . $arOneProp['DISPLAY_VALUE'] . '</a>';
                    }
                    elseif($arOneProp['PROPERTY_TYPE'] != 'E')
                    {
                        //$link = $sectionURL .  'filter/' . strtolower( $code ) .'-is-'. strtolower($arOneProp['DISPLAY_VALUE']) . '/apply/';
                        $smartPath = strtolower( $code ) .'-is-'. strtolower($arOneProp['DISPLAY_VALUE']);
                        $link = str_replace("#SMART_FILTER_PATH#", $smartPath, $sURL);

                        if($seoModule && $type == "seometa")
                        {
                            $arLink = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl($link);
                            if(isset($arLink["NEW_URL"]))
                                $link = $arLink["NEW_URL"];
                            else
                                $link = "";
                        }

                        if($link)
                            $DisplayProperties[$code]['DISPLAY_VALUE'] = '<a onclick="" href="' .$link.'" title="'.$DisplayProperties[$code]["NAME"].": ".$arOneProp['DISPLAY_VALUE'].'">' . $arOneProp['DISPLAY_VALUE'] . '</a>';
                    }
                }
                $link = "";
            }
        }

        return $DisplayProperties;
    }

    static function sameUrl($filterCode, $ID, $codeEl){

        foreach ($filterCode as $code)
        {
            if(isset($code) && !empty($code))
                if( (is_array($code[1]) && ($index = array_search($ID, $code[1])) !== false) || (!is_array($code[1]) && $ID == $code[1]) )
                    if(strtolower($code[1]) == strtolower($codeEl))
                        return true;
        }

        return false;
    }

    public function getFilterOutUrl($link)
    {
        self::setBefore();

        $filterCode = preg_replace('/^[\w\s\W]*\/filter\/(clear)?/', '', $link);
        if(isset($filterCode))
        {
            $filterCode = preg_replace('/\/apply\//', '', $filterCode);
            $tmpFilterCode = explode('/', $filterCode);
            $arCode = array();

            foreach ($tmpFilterCode as $key => $code)
            {
                $resIs = explode('-is-', $code);
                if(!empty($resIs) && !empty($resIs[0]) && !empty($resIs[1]))
                    array_push($arCode, $resIs);
            }

            $tmpFilterCode = $arCode;
            $arCode = array();

            foreach ($tmpFilterCode as $key => $code)
            {
                $resOr = explode('-or-', $code[1]);

                if(!empty($resOr) && !empty($resOr[0]) && !empty($resOr[1]))
                {
                    $tmpFilterCode[$key][1] = $resOr;
                }
            }

        } else
            $tmpFilterCode = array();

        return $tmpFilterCode;
    }

    /**
     * @param string $section
     * @param string $ID
     * @param string $code
     * @return string
     */
    public static function createUrl($section, $ID, $item, $filterCode, $linkMode = 'DISABLED')
    {
        $smartPath = strtolower($item['CODE']) . "-is-" . $ID;
        $url = str_replace("clear", $smartPath, $section);

        if($linkMode == 'MULTIPLE_LEVEL' && !empty($filterCode) && count($filterCode) < 2)
        {
            foreach ($filterCode as $code)
            {
                if(strtolower($item['CODE']) != strtolower($code[0]))
                {
                    if(count($code[1]) > 1)
                        $codes = implode('-or-', $code[1]);
                    else
                        $codes = $code[1];

                    //$url = $section . "filter/" . ( self::$position == 'before' ? strtolower($item['CODE']) . "-is-" . $ID : strtolower($code[0]) . "-is-" . $codes ) .  "/" .
                    //    ( self::$position == 'after' ? strtolower($item['CODE']) . "-is-" . $ID : strtolower($code[0]) . "-is-" . $codes ) . "/apply/";

                    $smartPath = ( self::$position == 'before' ? strtolower($item['CODE']) . "-is-" . $ID : strtolower($code[0]) . "-is-" . $codes ) .  "/" .
                        ( self::$position == 'after' ? strtolower($item['CODE']) . "-is-" . $ID : strtolower($code[0]) . "-is-" . $codes );
                    $url = str_replace("clear", $smartPath, $section);
                }else
                {
                    self::$position = 'after';
                }
            }
        }else if($linkMode == 'MULTIPLE_LEVEL' && !empty($filterCode) && count($filterCode) >= 2)
            return "";

        if(!empty($filterCode) && self::sameUrl($filterCode, $ID, $item['CODE']))
            return "";

        return $url;
    }
}

?>