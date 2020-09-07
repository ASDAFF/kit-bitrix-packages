<?php
namespace intec\constructor\structure\block;

use intec\constructor\structure\Block;
use intec\core\base\BaseObject;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * Class Resolution
 * @property integer $width Только для чтения.
 * @property integer $height Только для чтения.
 * @property string $value Только для чтения.
 * @package intec\constructor\structure\block
 */
class Resolution extends BaseObject
{
    /**
     * Щирина.
     * @var integer
     */
    protected $_width = 0;
    /**
     * Высота.
     * @var integer
     */
    protected $_height = 0;

    /**
     * Создает разрешение из массива.
     * @param array $data
     * @param Block|null $block
     * @return static|null
     */
    public static function from($data, $block = null)
    {
        $width = ArrayHelper::getValue($data, 'width');
        $width = Type::toInteger($width);
        $height = ArrayHelper::getValue($data, 'height');
        $height = Type::toInteger($height);

        if ($width <= 0 || $height <= 0)
            return null;

        return new static($width, $height);
    }

    /**
     * Resolution constructor.
     * @param integer $width
     * @param integer $height
     */
    public function __construct($width, $height)
    {
        $this->_width = Type::toInteger($width);
        $this->_height = Type::toInteger($height);

        parent::__construct([]);
    }

    /**
     * Возвращает ширину.
     * @return integer
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * Возвращает высоту.
     * @return integer
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * Возвращает значение разрешения.
     * @return string
     */
    public function getValue()
    {
        return $this->_width.'x'.$this->_height;
    }

    /**
     * Возвращает структуру разрешения.
     * @return array
     */
    public function getStructure()
    {
        $result = [];
        $result['width'] = $this->_width;
        $result['height'] = $this->_height;

        return $result;
    }
}