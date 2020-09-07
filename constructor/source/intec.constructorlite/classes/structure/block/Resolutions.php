<?php
namespace intec\constructor\structure\block;

use intec\core\base\Collection;
use intec\core\helpers\Type;

class Resolutions extends Collection
{
    /**
     * Содержит только уникальные разрешения.
     * @var boolean
     */
    protected $_unique = false;

    /**
     * Содержит только уникальные разрешения.
     * @return boolean
     */
    public function getUnique()
    {
        return $this->_unique;
    }

    /**
     * Resolutions constructor.
     * @param array $items
     * @param boolean $unique
     */
    public function __construct($items = [], $unique = false)
    {
        $this->_unique = Type::toBoolean($unique);
        parent::__construct($items);
    }

    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        if (!($item instanceof Resolution))
            return false;

        if ($this->_unique) {
            $resolution = $this->find($item->getWidth());

            if (!empty($resolution))
                return false;
        }

        return true;
    }

    /**
     * Поиск разрешения по значению.
     * @param $value
     * @return Resolution|null
     */
    public function find($value)
    {
        /** @var Resolution $item */
        foreach ($this->items as $item)
            if ($item->getWidth() === $value)
                return $item;

        return null;
    }

    /**
     * Возвращает минимальное разрешение (высчитывается по ширине).
     * @return Resolution|null
     */
    public function getMinimum()
    {
        /** @var Resolution $result */
        $result = null;

        /** @var Resolution $item */
        foreach ($this->items as $item)
            if ($result === null || $item->getWidth() < $result->getWidth())
                $result = $item;

        return $result;
    }

    /**
     * Возвращает минималюную ширину.
     * @return integer|null
     */
    public function getMinimumWidth()
    {
        $result = null;

        /** @var Resolution $item */
        foreach ($this->items as $item)
            if ($result === null || $item->getWidth() < $result)
                $result = $item->getWidth();

        return $result;
    }

    /**
     * Возвращает максимальное разрешение (высчитывается по ширине).
     * @return Resolution|null
     */
    public function getMaximum()
    {
        /** @var Resolution $result */
        $result = null;

        /** @var Resolution $item */
        foreach ($this->items as $item)
            if ($result === null || $item->getWidth() > $result->getWidth())
                $result = $item;

        return $result;
    }

    /**
     * Возвращает максимальную ширину.
     * @return integer|null
     */
    public function getMaximumWidth()
    {
        $result = null;

        /** @var Resolution $item */
        foreach ($this->items as $item)
            if ($result === null || $item->getWidth() > $result)
                $result = $item->getWidth();

        return $result;
    }

    /**
     * Возвращает минималюную высоту.
     * @return integer|null
     */
    public function getMinimumHeight()
    {
        $result = null;

        /** @var Resolution $item */
        foreach ($this->items as $item)
            if ($result === null || $item->getHeight() < $result)
                $result = $item->getHeight();

        return $result;
    }

    /**
     * Возвращает максимальную высоту.
     * @return integer|null
     */
    public function getMaximumHeight()
    {
        $result = null;

        /** @var Resolution $item */
        foreach ($this->items as $item)
            if ($result === null || $item->getHeight() > $result)
                $result = $item->getHeight();

        return $result;
    }

    /**
     * Возвращает значения ширины всех разрешений.
     * @return array
     */
    public function getWidthValues()
    {
        $result = [];

        /** @var Resolution $item */
        foreach ($this->items as $item)
            $result[] = $item->getWidth();

        return $result;
    }

    /**
     * Возвращает значения высоты всех разрешений.
     * @return array
     */
    public function getHeightValues()
    {
        $result = [];

        /** @var Resolution $item */
        foreach ($this->items as $item)
            $result[] = $item->getHeight();

        return $result;
    }

    /**
     * Возвращает значения всех разрешений.
     * @return array
     */
    public function getValues()
    {
        $result = [];

        /** @var Resolution $item */
        foreach ($this->items as $item)
            $result[] = $item->getValue();

        return $result;
    }
}