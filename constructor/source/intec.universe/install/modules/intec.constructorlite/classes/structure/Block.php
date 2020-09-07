<?php
namespace intec\constructor\structure;

use intec\constructor\structure\block\Resolution;
use intec\constructor\structure\block\Resources;
use intec\Core;
use intec\core\base\InvalidParamException;
use intec\core\base\BaseObject;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;
use intec\constructor\structure\block\Element;
use intec\constructor\structure\block\Elements;
use intec\constructor\structure\block\Resolutions;

/**
 * Class Block
 * @property integer $id Идентификатор блока.
 * @package intec\constructor\structure
 */
class Block extends BaseObject
{
    /**
     * Счетчик созданных элементов.
     * @var integer
     */
    protected static $counter = 0;

    /**
     * Уникальный идентификатор блока.
     * @var integer
     */
    protected $_id;
    /**
     * Элементы блока.
     * @var Elements
     */
    protected $_elements;
    /**
     * Разрешения блока.
     * @var Resolutions
     */
    protected $_resolutions;
    /**
     * Ресурсы блока.
     * @var Resources
     */
    protected $_resources;
    /**
     * Высота блока по умолчанию.
     * @var integer
     */
    protected $_height;

    /**
     * Класс по умолчанию.
     * @var string
     */
    public $class;

    /**
     * Создает блок из массива.
     * @param array $data
     * @param Resources $resources
     * @return static
     */
    public static function from($data, $resources)
    {
        $instance = new static($resources);

        $resolutions = ArrayHelper::getValue($data, 'resolutions');
        $elements = ArrayHelper::getValue($data, 'elements');

        if (Type::isArray($resolutions))
            foreach ($resolutions as $resolution)
                $instance->getResolutions()->add(Resolution::from($resolution, $instance));

        if (Type::isArray($elements))
            foreach ($elements as $element)
                $instance->getElements()->add(Element::from($element, $instance));

        return $instance;
    }

    /**
     * Block constructor.
     * @param Resources $resources
     */
    public function __construct($resources)
    {
        if (!($resources instanceof Resources))
            throw new InvalidParamException('Resources is not a "'.Resources::className().'" instance.');

        $this->_id = static::$counter;
        $this->_elements = new Elements();
        $this->_resolutions = new Resolutions([], true);
        $this->_resources = $resources;

        static::$counter++;
        parent::__construct([]);
    }

    /**
     * Возвращает идентификатор блока.
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Устанавливает идентификатор блока.
     * @param integer $id Новый идентификатор.
     * @return $this
     */
    public function setId($id)
    {
        $id = Type::toInteger($id);

        if ($id >= 0)
            $this->_id = $id;

        return $this;
    }

    /**
     * Возвращает элементы блока.
     * @return Elements
     */
    public function getElements()
    {
        return $this->_elements;
    }

    /**
     * Возвращает разрешения блока.
     * @return Resolutions
     */
    public function getResolutions()
    {
        return $this->_resolutions;
    }

    /**
     * Возвращает ресурсы блока.
     * @return Resources
     */
    public function getResources()
    {
        return $this->_resources;
    }

    /**
     * Возвращает структуру блока.
     * @return array
     */
    public function getStructure()
    {
        $result = [];
        $result['resolutions'] = [];
        $result['elements'] = [];

        foreach ($this->_resolutions as $resolution) {
            $result['resolutions'][] = $resolution->getStructure();
        }

        $resolutions = ['*'];
        $resolutions = ArrayHelper::merge(
            $resolutions,
            $this->_resolutions->getWidthValues()
        );

        /** @var Element $element */
        foreach ($this->_elements as $element) {
            $result['elements'][] = $element->getStructure($resolutions);
        }

        return $result;
    }

    /**
     * Собирает класс для блока.
     * @param string|null $name Класс.
     * @return string
     */
    public function makeStyleClass($name = null)
    {
        $class = !empty($this->class) ? $this->class : 'block';
        $class .= '-'.$this->getId();

        if (!empty($name))
            $class .= '-'.$name;

        return $class;
    }

    /**
     * Подключает заголовки блока.
     */
    public function includeHeaders()
    {
        Core::$app->web->css->addString($this->getStyle());

        /** @var Element $element */
        foreach ($this->_elements as $element)
            $element->includeHeaders();
    }

    /**
     * Создает стили блока.
     * @return null|string
     */
    public function getStyle()
    {
        if ($this->_resolutions->isEmpty())
            return null;

        $style = [];
        $resolutionDefault = $this->_resolutions->getMinimum();
        $width = $resolutionDefault->getWidth();
        $height = $resolutionDefault->getHeight();
        $containers = Element::getContainers();


        $style[] = '.'.$this->makeStyleClass().'{'.
            Html::cssStyleFromArray([
                'position' => 'relative',
                'display' => 'block',
                'min-width' => $width.'px',
                'z-index' => 0
            ]).
        '}';
        $style[] = '.'.$this->makeStyleClass('wrapper').'{'.
            Html::cssStyleFromArray([
                'position' => 'relative',
                'display' => 'block',
                'width' => $width !== null ? $width.'px' : null,
                'height' => $height.'px',
                'margin' => '0 auto'
            ]).
        '}';
        $style[] = '.'.$this->makeStyleClass('elements').'{'.
            Html::cssStyleFromArray([
                'position' => 'absolute',
                'display' => 'block',
                'top' => 0,
                'left' => 0,
                'width' => '100%',
                'height' => '100%'
            ]).
        '}';

        /** @var Resolution $resolution */
        foreach ($this->_resolutions as $resolution) {
            $style[] = '@media all and (min-width: '.$resolution->getWidth().'px){'.
                '.'.$this->makeStyleClass('wrapper').' {'.
                    Html::cssStyleFromArray([
                        'width' => $resolution->getWidth().'px',
                        'height' => $resolution->getHeight().'px'
                    ]).
                '}'.
            '}';
        }

        /**
         * @param Element $element
         * @param integer $id
         * @param Resolution|null $resolution
         * @return string
         */
        $getElementStyle = function ($element, $id, $resolution = null) use (&$containers, &$resolutionDefault) {
            $style = [];

            if ($resolution === null) {
                $resolution = $resolutionDefault;
                $style[] = '.'.$element->makeStyleClass($this, $id).'{'.
                    Html::cssStyleFromArray([
                        'position' => 'absolute',
                        'width' => '100%',
                        'height' => '100%',
                        '-webkit-box-direction' => 'normal',
                        '-moz-box-direction' => 'normal',
                        '-webkit-box-orient' => 'horizontal',
                        '-moz-box-orient' => 'horizontal',
                        '-webkit-flex-direction' => 'row',
                        '-ms-flex-direction' => 'row',
                        'flex-direction' => 'row',
                        '-webkit-flex-wrap' => 'nowrap',
                        '-ms-flex-wrap' => 'nowrap',
                        'flex-wrap' => 'nowrap',
                        'visibility' => 'hidden'
                    ]).
                '}';

                $style[] = '.'.$element->makeStyleClass($this, $id, 'wrapper').'{'.
                    Html::cssStyleFromArray([
                        'position' => 'relative',
                        '-webkit-box-ordinal-group' => 1,
                        '-moz-box-ordinal-group' => 1,
                        '-webkit-order' => 0,
                        '-ms-flex-order' => 0,
                        'order' => 0,
                        '-webkit-box-flex' => 0,
                        '-moz-box-flex' => 0,
                        '-webkit-flex' => '0 1 auto',
                        '-ms-flex' => '0 1 auto',
                        'flex' => '0 1 auto',
                        '-webkit-align-self' => 'auto',
                        '-ms-flex-item-align' => 'auto',
                        'align-self' => 'auto',
                        'visibility' => 'visible',
                        'z-index' => $element->order + 1
                    ]).
                '}';

                $style[] = '.'.$element->makeStyleClass($this, $id, 'wrapper-2').'{'.
                    Html::cssStyleFromArray([
                        'position' => 'relative',
                        'width' => '100%',
                        'height' => '100%',
                        '-webkit-box-sizing' => 'border-box',
                        '-moz-box-sizing' => 'border-box',
                        'box-sizing' => 'border-box'
                    ]).
                '}';

                $style[] = '.'.$element->makeStyleClass($this, $id, 'wrapper-3').'{'.
                    Html::cssStyleFromArray([
                        'position' => 'relative',
                        'width' => '100%',
                        'height' => '100%'
                    ]).
                '}';
            }

            $display = $element->getAttribute(
                'display',
                $resolution
            );
            $container = $element->getAttribute(
                'container',
                $resolution
            );
            $xAxis = $element->getAttribute(
                'xAxis',
                $resolution
            );
            $x = $element->getAttribute(
                'x',
                $resolution
            );

            if ($x != 0)
                $x .= $element->getAttribute(
                    'xMeasure',
                    $resolution
                );

            $yAxis = $element->getAttribute(
                'yAxis',
                $resolution
            );
            $y = $element->getAttribute(
                'y',
                $resolution
            );

            if ($y != 0)
                $y .= $element->getAttribute(
                    'yMeasure',
                    $resolution
                );

            $width = $element->getAttribute(
                'width',
                $resolution
            );
            $height =  $element->getAttribute(
                'height',
                $resolution
            );

            if ($width !== null) {
                $width .= $element->getAttribute(
                    'widthMeasure',
                    $resolution
                );
            } else {
                $width = 'auto';
            }

            if ($height !== null) {
                $height .= $element->getAttribute(
                    'heightMeasure',
                    $resolution
                );
            } else {
                $height = 'auto';
            }

            $indents = $element->getAttribute(
                'indents',
                $resolution
            );
            $indentTop = $element->getAttribute(
                'indentTop',
                $resolution
            );
            $indentBottom = $element->getAttribute(
                'indentBottom',
                $resolution
            );
            $indentLeft = $element->getAttribute(
                'indentLeft',
                $resolution
            );
            $indentRight = $element->getAttribute(
                'indentRight',
                $resolution
            );

            if ($indents && $indentTop !== null) {
                $indentTop .= $element->getAttribute(
                    'indentTopMeasure',
                    $resolution
                );
            } else {
                $indentTop = 0;
            }

            if ($indents && $indentBottom !== null) {
                $indentBottom .= $element->getAttribute(
                    'indentBottomMeasure',
                    $resolution
                );
            } else {
                $indentBottom = 0;
            }

            if ($indents && $indentLeft !== null) {
                $indentLeft .= $element->getAttribute(
                    'indentLeftMeasure',
                    $resolution
                );
            } else {
                $indentLeft = 0;
            }

            if ($indents && $indentRight !== null) {
                $indentRight .= $element->getAttribute(
                    'indentRightMeasure',
                    $resolution
                );
            } else {
                $indentRight = 0;
            }

            $alignment = [
                '-webkit-box-pack' => 'start',
                '-moz-box-pack' => 'start',
                '-webkit-justify-content' => 'flex-start',
                '-ms-flex-pack' => 'start',
                'justify-content' => 'flex-start',

                '-webkit-box-align' => 'start',
                '-moz-box-align' => 'start',
                '-webkit-align-items' => 'flex-start',
                '-ms-flex-align' => 'start',
                'align-items' => 'flex-start'
            ];

            if ($xAxis === Element::X_AXIS_CENTER) {
                $alignment['-webkit-box-pack'] = 'center';
                $alignment['-moz-box-pack'] = 'center';
                $alignment['-webkit-justify-content'] = 'center';
                $alignment['-ms-flex-pack'] = 'center';
                $alignment['justify-content'] = 'center';
            } else if ($xAxis === Element::X_AXIS_RIGHT) {
                $alignment['-webkit-box-pack'] = 'end';
                $alignment['-moz-box-pack'] = 'end';
                $alignment['-webkit-justify-content'] = 'flex-end';
                $alignment['-ms-flex-pack'] = 'end';
                $alignment['justify-content'] = 'flex-end';
            }

            if ($yAxis === Element::Y_AXIS_CENTER) {
                $alignment['-webkit-box-align'] = 'center';
                $alignment['-moz-box-align'] = 'center';
                $alignment['-webkit-align-items'] = 'center';
                $alignment['-ms-flex-align'] = 'center';
                $alignment['align-items'] = 'center';
            } else if ($yAxis === Element::Y_AXIS_BOTTOM) {
                $alignment['-webkit-box-align'] = 'end';
                $alignment['-moz-box-align'] = 'end';
                $alignment['-webkit-align-items'] = 'flex-end';
                $alignment['-ms-flex-align'] = 'end';
                $alignment['align-items'] = 'flex-end';
            }

            $style[] = '.'.$element->makeStyleClass($this, $id).'{'.
                Html::cssStyleFromArray($alignment).
            '}';

            $style[] = '.'.$element->makeStyleClass($this, $id, 'wrapper').'{'.
                Html::cssStyleFromArray([
                    'top' => $y,
                    'left' => $x,
                    'width' => $width,
                    'height' => $height
                ]).
            '}';

            $style[] = '.'.$element->makeStyleClass($this, $id, 'wrapper-2').'{'.
                Html::cssStyleFromArray([
                    'padding-top' => $indentTop,
                    'padding-bottom' => $indentBottom,
                    'padding-left' => $indentLeft,
                    'padding-right' => $indentRight
                ]).
            '}';

            foreach ($containers as $key => $name) {
                $style[] =
                    '.'.$this->makeStyleClass('elements').
                    '.'.$this->makeStyleClass('elements-'.$key).
                    ' .'.$element->makeStyleClass($this, $id).'{'.
                    Html::cssStyleFromArray([
                        'display' => $container == $key && $display ? [
                            '-webkit-box',
                            '-moz-box',
                            '-ms-flexbox',
                            '-webkit-flex',
                            'flex'
                        ] : 'none'
                    ]).
                '}';
            }

            return implode('', $style);
        };

        $index = 0;

        /** @var Element $element */
        foreach ($this->_elements as $element) {
            $elementStyle = $getElementStyle($element, $index);
            $elementStyle .= $element->getStyle($this, $index, null);
            $elementStyle .= $element->getStyle($this, $index, $resolutionDefault);

            $style[] = $elementStyle;

            /** @var Resolution $resolution */
            foreach ($this->_resolutions as $resolution) {
                $elementStyle = $getElementStyle($element, $index, $resolution);
                $elementStyle .= $element->getStyle($this, $index, $resolution);

                $style[] = '@media all and (min-width: '.$resolution->getWidth().'px) {'.
                    $elementStyle.
                '}';
            }

            $index++;
        }

        return implode('', $style);
    }

    /**
     * Создает верстку блока.
     * @param bool $out
     * @return string|null
     */
    public function render($out = false)
    {
        if ($this->_resolutions->isEmpty())
            return null;

        /**
         * @param Element $element
         * @param integer $id
         * @return string
         */
        $renderElement = function ($element, $id) {
            $html = [];
            $html[] = Html::beginTag('div', [
                'class' => $element->makeStyleClass($this, $id)
            ]);
            $html[] = Html::beginTag('div', [
                'class' => $element->makeStyleClass($this, $id, 'wrapper')
            ]);
            $html[] = Html::beginTag('div', [
                'class' => $element->makeStyleClass($this, $id, 'wrapper-2')
            ]);
            $html[] = Html::beginTag('div', [
                'class' => $element->makeStyleClass($this, $id, 'wrapper-3')
            ]);
            $html[] = $element->render(true, false,$this, $id);
            $html[] = Html::endTag('div');
            $html[] = Html::endTag('div');
            $html[] = Html::endTag('div');
            $html[] = Html::endTag('div');

            return implode('', $html);
        };

        $html = [];
        $html[] = Html::beginTag('div', [
            'class' => $this->makeStyleClass()
        ]);
        $html[] = Html::beginTag('div', [
            'class' => $this->makeStyleClass('wrapper')
        ]);
        $html[] = Html::beginTag('div', [
            'class' =>
                $this->makeStyleClass('elements').' '.
                $this->makeStyleClass('elements-'.Element::CONTAINER_GRID)
        ]);

        $index = 0;

        /** @var Element $element */
        foreach ($this->_elements as $element) {
            $html[] = $renderElement($element, $index);
            $index++;
        }

        $html[] = Html::endTag('div');
        $html[] = Html::endTag('div');

        $html[] = Html::beginTag('div', [
            'class' =>
                $this->makeStyleClass('elements').' '.
                $this->makeStyleClass('elements-'.Element::CONTAINER_WINDOW)
        ]);

        $index = 0;

        /** @var Element $element */
        foreach ($this->_elements as $element) {
            $html[] = $renderElement($element, $index);
            $index++;
        }

        $html[] = Html::endTag('div');
        $html[] = Html::endTag('div');
        $html = implode('', $html);

        if ($out) {
            echo $html;
        } else {
            return $html;
        }

        return null;
    }
}