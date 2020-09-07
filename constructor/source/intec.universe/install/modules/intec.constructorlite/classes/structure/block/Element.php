<?php
namespace intec\constructor\structure\block;

use Bitrix\Main\Localization\Loc;
use intec\core\base\UnknownPropertyException;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;
use intec\constructor\base\Renderable;
use intec\constructor\base\ScannableSnippet;
use intec\constructor\structure\Block;
use intec\constructor\structure\block\element\AttributeCorrectEvent;
use intec\constructor\structure\SnippetIconTrait;
use intec\constructor\structure\SnippetMetaTrait;

Loc::loadMessages(__FILE__);

/**
 * Class Element
 * @property string $container
 * @property integer $x
 * @property string $xAxis
 * @property string $xMeasure
 * @property integer $y
 * @property string $yAxis
 * @property string $yMeasure
 * @property float|null $width
 * @property string $widthMeasure
 * @property float|null $height
 * @property string $heightMeasure
 * @package intec\constructor\structure\block
 */
abstract class Element extends ScannableSnippet implements Renderable
{
    use SnippetIconTrait;
    use SnippetMetaTrait;

    /**
     * Событие коррекции атрибута.
     * @event AttributeCorrectEvent
     */
    const EVENT_ATTRIBUTE_CORRECT = 'attributeCorrect';

    /**
     * Блок элемента.
     * @var Block
     */
    protected $_block;
    /**
     * Атрибуты элемента.
     * @var array
     */
    protected $_attributes = [];

    /**
     * Порядок.
     * @var integer|null
     */
    public $order;

    /**
     * Контейнер: Сетка
     */
    const CONTAINER_GRID = 'grid';
    /**
     * Контейнер: Окно
     */
    const CONTAINER_WINDOW = 'window';

    /**
     * Возвращает список контейнеров.
     * @return array
     */
    public static function getContainers()
    {
        return [
            static::CONTAINER_GRID => Loc::getMessage('intec.constructor.structure.block.element.container.grid'),
            static::CONTAINER_WINDOW => Loc::getMessage('intec.constructor.structure.block.element.container.window')
        ];
    }

    /**
     * Возвращает список значений контейнеров.
     * @return array
     */
    public static function getContainersValues()
    {
        $values = static::getContainers();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * Выравнивание: Слева
     */
    const X_AXIS_LEFT = 'left';
    /**
     * Выравнивание: По центру
     */
    const X_AXIS_CENTER = 'center';
    /**
     * Выравнивание: Справа
     */
    const X_AXIS_RIGHT = 'right';

    /**
     * Возвращает список осей X.
     * @return array
     */
    public static function getXAxises()
    {
        return [
            static::X_AXIS_LEFT => Loc::getMessage('intec.constructor.structure.block.element.xAxis.left'),
            static::X_AXIS_CENTER => Loc::getMessage('intec.constructor.structure.block.element.xAxis.center'),
            static::X_AXIS_RIGHT => Loc::getMessage('intec.constructor.structure.block.element.xAxis.right')
        ];
    }

    /**
     * Возвращает список значений осей X.
     * @return array
     */
    public static function getXAxisesValues()
    {
        $values = static::getXAxises();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * Выравнивание: Сверху
     */
    const Y_AXIS_TOP = 'top';
    /**
     * Выравнивание: По центру
     */
    const Y_AXIS_CENTER = 'center';
    /**
     * Выравнивание: Снизу
     */
    const Y_AXIS_BOTTOM = 'bottom';

    /**
     * Возвращает список осей X.
     * @return array
     */
    public static function getYAxises()
    {
        return [
            static::Y_AXIS_TOP => Loc::getMessage('intec.constructor.structure.block.element.yAxis.top'),
            static::Y_AXIS_CENTER => Loc::getMessage('intec.constructor.structure.block.element.yAxis.center'),
            static::Y_AXIS_BOTTOM => Loc::getMessage('intec.constructor.structure.block.element.yAxis.bottom')
        ];
    }

    /**
     * Возвращает список значений осей X.
     * @return array
     */
    public static function getYAxisesValues()
    {
        $values = static::getYAxises();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * Создает элемент блока из массива.
     * @param array $data
     * @param Block|null $block
     * @return static|null
     */
    public static function from($data, $block)
    {
        if (!($block instanceof Block))
            $block = null;

        $code = ArrayHelper::getValue($data, 'code');
        $order = ArrayHelper::getValue($data, 'order');

        if (empty($code))
            return null;

        /** @var Element $instance */
        $instance = Elements::create($code);

        if (empty($instance))
            return null;

        $instance->order = $order;

        if ($block != null) {
            $attributes = ArrayHelper::getValue($data, 'attributes');

            if (!empty($attributes))
                foreach ($attributes as $resolution => $values) {
                    if ($resolution === '*') {
                        $resolution = null;
                    } else {
                        $resolution = $block->getResolutions()->find($resolution);

                        if (empty($resolution))
                            continue;
                    }

                    $instance->setAttributes($values, $resolution);
                }
        }

        return $instance;
    }

    /**
     * Возвращает стили для конкретного разрешения
     * @param Block $block
     * @param integer $id
     * @param Resolution|null $resolution
     * @return string|null
     */
    public function getStyle($block, $id, $resolution = null)
    {
        return null;
    }

    /**
     * Возвращает блок элемента.
     * @return Block
     */
    public function getBlock()
    {
        return $this->_block;
    }

    /**
     * Возвращает список атрибутов элемента.
     * @return array
     */
    public function attributes()
    {
        return [
            'display',
            'locked',
            'container',
            'x',
            'xAxis',
            'xMeasure',
            'y',
            'yAxis',
            'yMeasure',
            'width',
            'widthMeasure',
            'height',
            'heightMeasure',
            'indents',
            'indentTop',
            'indentTopMeasure',
            'indentBottom',
            'indentBottomMeasure',
            'indentLeft',
            'indentLeftMeasure',
            'indentRight',
            'indentRightMeasure',
        ];
    }

    /**
     * Возвращает наличие аттрибута.
     * @param $attribute
     * @return boolean
     */
    public function hasAttribute($attribute)
    {
        $attributes = $this->attributes();

        return ArrayHelper::isIn(
            $attribute,
            $attributes,
            true
        );
    }

    /**
     * Возвращает необработанное значение атрибута.
     * @param string $attribute
     * @param Resolution|null $resolution
     * @param boolean $default
     * @param boolean $correct
     * @return mixed
     */
    protected function getAttributeRaw($attribute, $resolution = null, $default = false, $correct = true)
    {
        $value = null;

        if (!($resolution instanceof Resolution))
            $resolution = null;

        $attributes = ArrayHelper::getValue(
            $this->_attributes,
            $resolution !== null ? $resolution->getWidth() : '*'
        );

        if (!Type::isArray($attributes))
            $attributes = [];

        if (ArrayHelper::keyExists($attribute, $attributes)) {
            $value = $attributes[$attribute];
        } else if ($resolution !== null && $default) {
            $value = $this->getAttributeRaw($attribute);
        }

        if ($correct)
            $value = $this->attributeCorrect(
                $attribute,
                $value,
                $resolution,
                AttributeCorrectEvent::OPERATION_GET
            );

        return $value;
    }

    /**
     * Возвращает значение атрибута.
     * @param string $attribute
     * @param Resolution|null $resolution
     * @param boolean $default
     * @return mixed
     */
    public function getAttribute($attribute, $resolution = null, $default = false)
    {
        $value = null;

        if (!$this->hasAttribute($attribute))
            return $value;

        if ($this->canGetProperty($attribute, false, false)) {
            $getter = 'get' . $attribute;
            $value = $this->$getter($resolution, $default);
        } else {
            $value = $this->getAttributeRaw($attribute, $resolution);
        }

        return $value;
    }

    /**
     * Устанавливает значение атрибута напрямую в массив.
     * @param string $attribute
     * @param mixed $value
     * @param Resolution|null $resolution
     * @param boolean $correct
     * @return $this
     */
    protected function setAttributeRaw($attribute, $value, $resolution = null, $correct = true)
    {
        if ($correct)
            $value = $this->attributeCorrect(
                $attribute,
                $value,
                $resolution,
                AttributeCorrectEvent::OPERATION_SET
            );

        if ($resolution instanceof Resolution) {
            $resolution = $resolution->getWidth();
        } else {
            $resolution = null;
        }

        if ($resolution === null)
            $resolution = '*';

        $this->_attributes[$resolution][$attribute] = $value;

        return $this;
    }

    /**
     * Устанавливает значение атрибута.
     * @param string $attribute
     * @param mixed $value
     * @param Resolution|null $resolution
     * @return $this
     */
    public function setAttribute($attribute, $value, $resolution = null)
    {
        if (!$this->hasAttribute($attribute))
            return $this;

        if ($this->canSetProperty($attribute, false, false)) {
            $setter = 'set' . $attribute;
            $this->$setter($value, $resolution);
        } else {
            $this->setAttributeRaw($attribute, $value, $resolution);
        }

        return $this;
    }

    public function unsetAttribute($attribute, $resolution = null)
    {

    }

    /**
     * Вызывается при корректировки атрибута
     * @param string $attribute
     * @param mixed $value
     * @param Resolution|null $resolution
     * @param string $operation
     * @return mixed
     */
    public function attributeCorrect($attribute, $value, $resolution, $operation)
    {
        if (
            $attribute == 'display' ||
            $attribute == 'locked' ||
            $attribute == 'indents'
        ) {
            $value = Type::toBoolean($value);
        } else if ($attribute == 'container') {
            $values = static::getContainersValues();
            $value = ArrayHelper::fromRange($values, $value);
        } else if (
            $attribute == 'x' ||
            $attribute == 'y'
        ) {
            $value = Type::toFloat($value);
        } else if (
            $attribute == 'width' ||
            $attribute == 'height' ||
            $attribute == 'indentTop' ||
            $attribute == 'indentBottom' ||
            $attribute == 'indentLeft' ||
            $attribute == 'indentRight'
        ) {
            if ($value !== null)
                $value = Type::toFloat($value);
        } else if (
            $attribute == 'xMeasure' ||
            $attribute == 'yMeasure' ||
            $attribute == 'widthMeasure' ||
            $attribute == 'heightMeasure' ||
            $attribute == 'indentTopMeasure' ||
            $attribute == 'indentBottomMeasure' ||
            $attribute == 'indentLeftMeasure' ||
            $attribute == 'indentRightMeasure'
        ) {
            $value = ArrayHelper::fromRange(['px', '%'], $value);
        } else if (
            $attribute == 'xAxis' ||
            $attribute == 'yAxis'
        ) {
            if ($attribute == 'xAxis') {
                $values = static::getXAxisesValues();
            } else {
                $values = static::getYAxisesValues();
            }

            $value = ArrayHelper::fromRange($values, $value);
        }

        $event = new AttributeCorrectEvent([
            'attribute' => $attribute,
            'resolution' => $resolution,
            'value' => $value,
            'operation' => $operation,
            'sender' => $this
        ]);

        $this->trigger(self::EVENT_ATTRIBUTE_CORRECT, $event);

        return $event->value;
    }

    /**
     * @param array $values
     * @param Resolution|null $resolution
     */
    public function setAttributes($values, $resolution = null)
    {
        if (!Type::isArrayable($values))
            return $this;

        foreach ($values as $key => $value)
            $this->setAttribute($key, $value, $resolution);

        return $this;
    }

    /**
     * @param Resolution|null $resolution
     */
    public function getAttributes($values, $resolution = null, $default = false)
    {
        $value = [];

        return $value;
    }

    /**
     * Возвращает структуру элемента.
     * @param array|null $resolutions Разрешения.
     * @return array
     */
    public function getStructure($resolutions = null)
    {
        $result = [];
        $result['code'] = $this->getCode();
        $result['order'] = $this->order;

        if (!Type::isArray($resolutions)) {
            $result['attributes'] = $this->_attributes;
        } else {
            $result['attributes'] = [];

            foreach ($resolutions as $resolution) {
                $attributes = ArrayHelper::getValue($this->_attributes, $resolution);

                if (!Type::isArray($attributes))
                    $attributes = [];

                $result['attributes'][$resolution] = $attributes;
            }
        }

        return $result;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __get($name)
    {
        $value = null;

        if ($this->hasAttribute($name)) {
            $value = $this->getAttribute($name);
        } else {
            $value = parent::__get($name);
        }

        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws UnknownPropertyException
     */
    public function __set($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->setAttribute($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        if ($this->hasAttribute($name))
            return true;

        return parent::__isset($name);
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        if ($this->hasAttribute($name)) {
            $this->setAttribute($name, null);
        } else {
            parent::__unset($name);
        }
    }

    /**
     * Возвращает директорию View файлов.
     * @return Path
     */
    public function getViewsDirectory()
    {
        return $this->getDirectory()->add('views');
    }

    /**
     * Собирает класс для элемента.
     * @param Block $block
     * @param integer $id
     * @param string|null $name
     * @return string
     */
    public function makeStyleClass($block, $id, $name = null)
    {
        $class = $block->makeStyleClass('element');
        $class .= '-'.$id;

        if (!empty($name))
            $class .= '-'.$name;

        return $class;
    }

    /**
     * Возвращает верстку.
     * @param boolean $static
     * @param boolean $out
     * @param Block $block
     * @param integer $id
     * @return string|null
     */
    public function render($static = true, $out = false, $block = null, $id = null)
    {
        $path = $static ? 'static' : 'dynamic';
        $path = $this->getViewsDirectory()->add($path.'.php');
        $content = $this->getMetaContent($path, true, [
            'block' => $block,
            'id' => $id
        ]);

        if ($out) {
            echo $content;
        } else {
            return $content;
        }

        return null;
    }
}