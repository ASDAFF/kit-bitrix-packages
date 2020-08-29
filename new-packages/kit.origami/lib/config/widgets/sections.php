<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 23-Jan-18
 * Time: 9:59 AM
 */

namespace Sotbit\Origami\Config\Widgets;

use Bitrix\Main\Loader;
use Sotbit\Origami\Config\Option;
class Sections extends Select
{
    private $iblockId;

    public function setValues($site = 's1')
    {
        $this->setIblockId(Option::get('IBLOCK_ID', $site));
        if (Loader::includeModule('iblock')) {
            $l = \CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$this->getIblockId()), array("ID", "NAME", "DEPTH_LEVEL"));
            if($this->isCanEmpty()){
                $this->setValue('','Не привязывать');
            }
            while($ar_l = $l->GetNext()){
                $this->setValue(
                    $ar_l["ID"],
                    str_repeat(" . ", $ar_l["DEPTH_LEVEL"]).' '.$ar_l["NAME"]
                );
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIblockId()
    {
        return $this->iblockId;
    }

    /**
     * @param mixed $iblockId
     */
    public function setIblockId($iblockId)
    {
        $this->iblockId = $iblockId;
    }
}
