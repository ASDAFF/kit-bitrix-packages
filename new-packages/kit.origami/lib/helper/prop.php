<?php

namespace Sotbit\Origami\Helper;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class Prop
 *
 * @package Sotbit\Origami\Helper
 * @author  Sergey Danilkin <s.danilkin@kit.ru>
 */
class Prop
{
    /**
     * @param array $prop
     *
     * @return bool
     */
    public static function checkPropListYes($prop = []){
        $return = false;
        $arrTrue = [
            'true',
            'True',
            'TRUE',
            'yes',
            'Yes',
            'YES',
            Loc::getMessage(\SotbitOrigami::moduleId.'_YES'),
            Loc::getMessage(\SotbitOrigami::moduleId.'_YES2'),
            Loc::getMessage(\SotbitOrigami::moduleId.'_YES3'),
        ];
        if(in_array($prop['VALUE'],$arrTrue)){
            $return = true;
        }
        return $return;
    }
}