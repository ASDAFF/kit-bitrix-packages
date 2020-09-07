<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 23-Jan-18
 * Time: 9:59 AM
 */

namespace Sotbit\Origami\Config\Widgets;

use Bitrix\Main\Loader;

class IblockId extends Select
{
    private $iblockTypeCode;

    public function setValues($site = 's1')
    {
        if (Loader::includeModule('iblock')) {
            $iblockType = \Sotbit\Origami\Config\Option::get(
                $this->getIblockTypeCode(),
                $site
            );
            $filter = ['ACTIVE' => 'Y'];
            if ($iblockType) {
                $filter['IBLOCK_TYPE_ID'] = $iblockType;
            }
            $rs = \Bitrix\Iblock\IblockTable::getList(
                [
                    'select' => [
                        'ID',
                        'NAME',
                    ],
                    'filter' => $filter,
                ]
            );
            while ($iblockId = $rs->fetch()) {
                $this->setValue(
                    $iblockId['ID'],
                    '['.$iblockId['ID'].'] '.$iblockId['NAME']
                );
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIblockTypeCode()
    {
        return $this->iblockTypeCode;
    }

    /**
     * @param mixed $iblockTypeCode
     */
    public function setIblockTypeCode($iblockTypeCode)
    {
        $this->iblockTypeCode = $iblockTypeCode;
    }
}