<?
namespace intec\core\bitrix\components;

use CBitrixComponent;
use CIBlock;
use Bitrix\Main\Loader;
use intec\core\helpers\Type;

/**
 * Class IBlock
 * @package intec\core\bitrix\components
 * @author apocalypsisdimon@gmail.com
 */
class IBlock extends CBitrixComponent
{
    /**
     * Идентификатор инфоблока.
     * @var integer|null
     */
    protected $_id;
    /**
     * Тип инфоблока.
     * @var string|null
     */
    protected $_type;

    /**
     * Возвращает идентификатор инфоблока.
     * @return integer|null
     */
    protected function getIBlockId()
    {
        return $this->_id;
    }

    /**
     * Устанавливает идентификатор инфоблока.
     * @param integer|null $value
     * @return $this
     */
    protected function setIBlockId($value)
    {
        if ($value !== null) {
            $value = Type::toInteger($value);
            $value = $value > 0 ? $value : null;
        }

        $this->_id = $value;

        return $this;
    }

    /**
     * Возвращает тип инфоблока.
     * @return integer|null
     */
    protected function getIBlockType()
    {
        return $this->_type;
    }

    /**
     * Устанавливает тип инфоблока.
     * @param string|null $value
     * @return $this
     */
    protected function setIBlockType($value)
    {
        if ($value !== null)
            $value = Type::toString($value);

        $this->_type = $value;

        return $this;
    }

    /**
     * Возвращает инфоблок.
     * @return array
     */
    protected function getIBlock()
    {
        $arResult = null;

        if (!Loader::includeModule('iblock'))
            return $arResult;

        $iId = $this->getIBlockId();
        $sType = $this->getIBlockType();

        if ($iId === null)
            return null;

        $arFilter = [];

        if ($sType !== null)
            $arFilter['TYPE'] = $sType;

        $arFilter['ID'] = $iId;
        $rsResult = CIBlock::GetList([], $arFilter);

        if (empty($rsResult))
            return null;

        $arResult = $rsResult->GetNext();

        if (empty($arResult))
            return null;

        return $arResult;
    }
}