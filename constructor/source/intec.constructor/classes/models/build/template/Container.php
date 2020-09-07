<?php
namespace intec\constructor\models\build\template;
IncludeModuleLangFile(__FILE__);

use intec\constructor\models\build\Area;
use intec\constructor\models\build\AreaLink;
use intec\Core;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Inflector;
use intec\core\helpers\Type;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\build\ConditionsTrait;
use intec\constructor\models\build\ConditionsInterface;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\container\StyleBackgroundTrait;
use intec\constructor\models\build\template\container\StyleBorderColorTrait;
use intec\constructor\models\build\template\container\StyleBorderRadiusTrait;
use intec\constructor\models\build\template\container\StyleBorderStyleTrait;
use intec\constructor\models\build\template\container\StyleBorderTrait;
use intec\constructor\models\build\template\container\StyleBorderWidthTrait;
use intec\constructor\models\build\template\container\StyleFloatTrait;
use intec\constructor\models\build\template\container\StyleMarginTrait;
use intec\constructor\models\build\template\container\StyleOpacityTrait;
use intec\constructor\models\build\template\container\StyleOverflowTrait;
use intec\constructor\models\build\template\container\StylePaddingTrait;
use intec\constructor\models\build\template\container\StylePositionTrait;
use intec\constructor\models\build\template\container\StyleSideTrait;
use intec\constructor\models\build\template\container\StyleSizeTrait;
use intec\constructor\models\build\template\container\StyleTextTrait;
use intec\constructor\models\build\template\variator\Variant;

/**
 * Class Container
 * @property int $id
 * @property string $code
 * @property int $templateId
 * @property int $areaId
 * @property string $type
 * @property int $display
 * @property int $order
 * @property array $properties
 * @property array $condition
 * @property string $script
 * @property string $idAttribute
 * @property string $classAttribute
 * @property array $style
 * @property int|float|string|null $styleTop
 * @property int|float|string|null $styleRight
 * @property int|float|string|null $styleBottom
 * @property int|float|string|null $styleLeft
 * @property int|float|string|null $styleMargin
 * @property int|float|string|null $styleMarginTop
 * @property int|float|string|null $styleMarginRight
 * @property int|float|string|null $styleMarginBottom
 * @property int|float|string|null $styleMarginLeft
 * @property string $styleMarginSummary
 * @property int|float|string|null $stylePadding
 * @property int|float|string|null $stylePaddingTop
 * @property int|float|string|null $stylePaddingRight
 * @property int|float|string|null $stylePaddingBottom
 * @property int|float|string|null $stylePaddingLeft
 * @property string $stylePaddingSummary
 * @property int|float|string|null $styleWidth
 * @property int|float|string|null $styleHeight
 * @property string|null $styleBackgroundColor
 * @property array $styleAttribute
 * @package intec\constructor\models\build\template
 */
class Container extends ActiveRecord implements Exchangeable, ConditionsInterface
{
    use ConditionsTrait;
    use StyleBackgroundTrait;
    use StyleBorderColorTrait;
    use StyleBorderRadiusTrait;
    use StyleBorderStyleTrait;
    use StyleBorderTrait;
    use StyleBorderWidthTrait;
    use StyleFloatTrait;
    use StyleMarginTrait;
    use StyleOpacityTrait;
    use StyleOverflowTrait;
    use StylePaddingTrait;
    use StylePositionTrait;
    use StyleSideTrait;
    use StyleSizeTrait;
    use StyleTextTrait;

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустой контейнер.
     * @return static
     */
    public static function create()
    {
        $instance = new static();
        $instance->loadDefaultValues();
        $instance->templateId = null;
        $instance->areaId = null;
        $instance->condition = [
            'type' => 'group',
            'operator' => 'and',
            'conditions' => [],
            'result' => 1
        ];

        $instance->populateRelation('component', null);
        $instance->populateRelation('widget', null);
        $instance->populateRelation('block', null);
        $instance->populateRelation('area', null);
        $instance->populateRelation('variator', null);
        $instance->populateRelation('containers', []);

        return $instance;
    }

    /**
     * Тип контейнера
     */
    const TYPE_NORMAL = 'normal';       // обычный
    const TYPE_ABSOLUTE = 'absolute';   // абсолютный

    /**
     * Возвращает список доступных типов.
     * @return array
     */
    public static function getTypes()
    {
        return [
            static::TYPE_NORMAL => GetMessage('intec.constructor.models.template.container.type.normal'),
            static::TYPE_ABSOLUTE => GetMessage('intec.constructor.models.template.container.type.absolute')
        ];
    }

    /**
     * Возвращает список доступных значений типов.
     * @return array
     */
    public static function getTypesValues()
    {
        $values = static::getTypes();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Сторона
     */
    const STYLE_SIDE_TOP = 'top';           // верхняя
    const STYLE_SIDE_RIGHT = 'right';       // правая
    const STYLE_SIDE_BOTTOM = 'bottom';     // нижняя
    const STYLE_SIDE_LEFT = 'left';         // левая

    /**
     * Возвращает список сторон стилей.
     * @return array
     */
    public static function getStyleSides()
    {
        return [
            static::STYLE_SIDE_TOP => GetMessage('intec.constructor.models.template.container.style.side.top'),
            static::STYLE_SIDE_RIGHT => GetMessage('intec.constructor.models.template.container.style.side.right'),
            static::STYLE_SIDE_BOTTOM => GetMessage('intec.constructor.models.template.container.style.side.bottom'),
            static::STYLE_SIDE_LEFT => GetMessage('intec.constructor.models.template.container.style.side.left')
        ];
    }

    /**
     * Возвращает список значений сторон стилей.
     * @return array
     */
    public static function getStyleSidesValues()
    {
        $values = static::getStyleSides();
        return ArrayHelper::getKeys($values);
    }

    /**
     * Размещение
     */
    const STYLE_FLOAT_NONE = 'none';    // нет
    const STYLE_FLOAT_RIGHT = 'right';  // справа
    const STYLE_FLOAT_LEFT = 'left';    // слева

    /**
     * Возвращает список параметра стилей float.
     * @return array
     */
    public static function getStyleFloats()
    {
        return [
            static::STYLE_FLOAT_NONE => GetMessage('intec.constructor.models.template.container.style.float.none'),
            static::STYLE_FLOAT_RIGHT => GetMessage('intec.constructor.models.template.container.style.float.right'),
            static::STYLE_FLOAT_LEFT => GetMessage('intec.constructor.models.template.container.style.float.left')
        ];
    }

    /**
     * Возвращает список значений параметра стилей float.
     * @return array
     */
    public static function getStyleFloatsValues()
    {
        $values = static::getStyleFloats();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Стиль рамки
     */
    const STYLE_BORDER_STYLE_NONE = 'none';         // нет
    const STYLE_BORDER_STYLE_DOTTED = 'dotted';     // точечный
    const STYLE_BORDER_STYLE_DASHED = 'dashed';     // пунктирный
    const STYLE_BORDER_STYLE_SOLID = 'solid';       // сплошной
    const STYLE_BORDER_STYLE_DOUBLE = 'double';     // двойной сплошной
    const STYLE_BORDER_STYLE_GROOVE = 'groove';     // с границей
    const STYLE_BORDER_STYLE_RIDGE = 'ridge';       // с ребром
    const STYLE_BORDER_STYLE_INSET = 'inset';       // с верхним ребром
    const STYLE_BORDER_STYLE_OUTSET = 'outset';     // с нижним ребром

    /**
     * Возвращает список параметра стилей border-style.
     * @return array
     */
    public static function getStyleBorderStyles()
    {
        return [
            static::STYLE_BORDER_STYLE_NONE => GetMessage('intec.constructor.models.template.container.style.border.style.none'),
            static::STYLE_BORDER_STYLE_DOTTED => GetMessage('intec.constructor.models.template.container.style.border.style.dotted'),
            static::STYLE_BORDER_STYLE_DASHED => GetMessage('intec.constructor.models.template.container.style.border.style.dashed'),
            static::STYLE_BORDER_STYLE_SOLID => GetMessage('intec.constructor.models.template.container.style.border.style.solid'),
            static::STYLE_BORDER_STYLE_DOUBLE => GetMessage('intec.constructor.models.template.container.style.border.style.double'),
            static::STYLE_BORDER_STYLE_GROOVE => GetMessage('intec.constructor.models.template.container.style.border.style.groove'),
            static::STYLE_BORDER_STYLE_RIDGE => GetMessage('intec.constructor.models.template.container.style.border.style.ridge'),
            static::STYLE_BORDER_STYLE_INSET => GetMessage('intec.constructor.models.template.container.style.border.style.inset'),
            static::STYLE_BORDER_STYLE_OUTSET => GetMessage('intec.constructor.models.template.container.style.border.style.outset')
        ];
    }

    /**
     * Возвращает список значений параметра стилей border-style.
     * @return array
     */
    public static function getStyleBorderStylesValues()
    {
        $values = static::getStyleBorderStyles();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Границы
     */
    const STYLE_OVERFLOW_VISIBLE = 'visible';   // видимые
    const STYLE_OVERFLOW_HIDDEN = 'hidden';     // спрятанные
    const STYLE_OVERFLOW_SCROLL = 'scroll';     // с прокруткой
    const STYLE_OVERFLOW_AUTO = 'auto';         // автоматически
    const STYLE_OVERFLOW_INHERIT = 'inherit';   // как у родителя

    /**
     * Возвращает список параметра стилей overflow.
     * @return array
     */
    public static function getStyleOverflows()
    {
        return [
            static::STYLE_OVERFLOW_VISIBLE => GetMessage('intec.constructor.models.template.container.style.overflow.visible'),
            static::STYLE_OVERFLOW_HIDDEN => GetMessage('intec.constructor.models.template.container.style.overflow.hidden'),
            static::STYLE_OVERFLOW_SCROLL => GetMessage('intec.constructor.models.template.container.style.overflow.scroll'),
            static::STYLE_OVERFLOW_AUTO => GetMessage('intec.constructor.models.template.container.style.overflow.auto'),
            static::STYLE_OVERFLOW_INHERIT => GetMessage('intec.constructor.models.template.container.style.overflow.inherit')
        ];
    }

    /**
     * Возвращает список значений параметра стилей overflow.
     * @return array
     */
    public static function getStyleOverflowsValues()
    {
        $values = static::getStyleOverflows();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Повторения
     */
    const STYLE_BACKGROUND_REPEAT_INHERIT = 'inherit';   // По родителю
    const STYLE_BACKGROUND_REPEAT_REPEAT = 'repeat';     // Повторять
    const STYLE_BACKGROUND_REPEAT_REPEAT_X = 'repeat-x';     // Повторять по-горизонтали
    const STYLE_BACKGROUND_REPEAT_REPEAT_Y = 'repeat-y';         // Повторять по-вертикали
    const STYLE_BACKGROUND_REPEAT_NO_REPEAT = 'no-repeat';   // Не повторять

    /**
     * Возвращает список параметра стилей background-repeat.
     * @return array
     */
    public static function getStyleBackgroundRepeats()
    {
        return [
            static::STYLE_BACKGROUND_REPEAT_INHERIT => GetMessage('intec.constructor.models.template.container.style.background-repeat.inherit'),
            static::STYLE_BACKGROUND_REPEAT_REPEAT => GetMessage('intec.constructor.models.template.container.style.background-repeat.repeat'),
            static::STYLE_BACKGROUND_REPEAT_REPEAT_X => GetMessage('intec.constructor.models.template.container.style.background-repeat.repeat-x'),
            static::STYLE_BACKGROUND_REPEAT_REPEAT_Y => GetMessage('intec.constructor.models.template.container.style.background-repeat.repeat-y'),
            static::STYLE_BACKGROUND_REPEAT_NO_REPEAT => GetMessage('intec.constructor.models.template.container.style.background-repeat.no-repeat')
        ];
    }

    /**
     * Возвращает список значений параметра стилей background-repeat.
     * @return array
     */
    public static function getStyleBackgroundRepeatsValues()
    {
        $values = static::getStyleBackgroundRepeats();
        $values = ArrayHelper::getKeys($values);
        return $values;
    }

    /**
     * Тип: Контейнер
     */
    const TYPE = 'container';

    /**
     * @return Containers
     */
    public static function find()
    {
        return Core::createObject(ContainerQuery::className(), [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_templates_containers';
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $areaLink = $this->getAreaLink(true);
        $link = $this->getLink(true);
        $children = $this->getContainers(true);
        $component = $this->getComponent(true);
        $widget = $this->getWidget(true);
        $block = $this->getBlock(true);
        $variator = $this->getVariator(true);

        if ($areaLink)
            $areaLink->delete();

        if ($link)
            $link->delete();

        foreach ($children as $child) {
            /** @var Container $child */
            $child->delete();
        }

        if ($component)
            $component->delete();

        if ($widget)
            $widget->delete();

        if ($block)
            $block->delete();

        if ($variator)
            $variator->delete();
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if ($this->code !== null && Type::isNumeric($this->code))
            $this->code = Type::toString($this->code);

        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'properties' => [
                'class' => 'intec\core\behaviors\FieldArray',
                'attribute' => 'properties'
            ],
            'condition' => [
                'class' => 'intec\core\behaviors\FieldArray',
                'attribute' => 'condition'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $typesValues = static::getTypesValues();

        return [
            [['templateId', 'areaId', 'order'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['type', 'properties', 'script'], 'string'],
            [['display'], 'boolean', 'strict' => false],
            [['type'], 'default', 'value' => self::TYPE_NORMAL],
            [['type'], 'in', 'range' => $typesValues],
            [['display'], 'default', 'value' => 1],
            [['order'], 'default', 'value' => 0],
            [['type', 'display', 'order'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => GetMessage('intec.constructor.models.template.container.attributes.labels.id'),
            'templateId' => GetMessage('intec.constructor.models.template.container.attributes.labels.templateId'),
            'parentId' => GetMessage('intec.constructor.models.template.container.attributes.labels.parentId'),
            'type' => GetMessage('intec.constructor.models.template.container.attributes.labels.type'),
            'display' => GetMessage('intec.constructor.models.template.container.attributes.labels.display'),
            'order' => GetMessage('intec.constructor.models.template.container.attributes.labels.order'),
        ];
    }

    /**
     * Выполняет скрипт контейнера.
     * @param bool $withChildren Выполнять у дочерних контейнеров.
     */
    public function execute($withChildren = false)
    {
        if (!empty($this->script)) {
            eval($this->script);
        }

        if ($withChildren) {
            if ($this->hasArea()) {
                $area = $this->getArea(true);
                $container = $area->getContainer();

                if (!empty($container))
                    $container->execute(true);
            } else if ($this->hasVariator()) {
                $variator = $this->getVariator(true);
                $variant = $variator->getVariant();

                if (!empty($variant)) {
                    $container = $variant->getContainer(true);

                    if (!empty($container))
                        $container->execute(true);
                }
            } else {
                $containers = $this->getContainers(true);

                foreach ($containers as $container)
                    $container->execute(true);
            }
        }
    }

    /**
     * Определяет, отображается ли контейнер при заданных параметрах, или нет.
     * @param string|null $directory
     * @param string|null $path
     * @param string|null $url
     * @param array|null $parametersGet
     * @param array|null $parametersPage
     * @param array|null $parametersTemplate
     * @return bool
     */
    public function isDisplayed($directory = null, $path = null, $url = null, $parametersGet = null, $parametersPage = null, $parametersTemplate = null)
    {
        if (!$this->display)
            return false;

        return $this->isConditioned(
            $directory,
            $path,
            $url,
            $parametersGet,
            $parametersPage,
            $parametersTemplate
        );
    }

    /**
     * Реляция. Возвращает зону в которой хранится контейнер.
     * @param bool $result
     * @return Area|ActiveQuery|null
     */
    public function getStorageArea($result = false)
    {
        return $this->relation(
            'storageArea',
            $this->hasOne(Area::className(), ['id' => 'areaId']),
            $result
        );
    }

    /**
     * Реляция. Возвращает шаблон в котором хранится контейнер.
     * @param bool $result
     * @return Template|ActiveQuery|null
     */
    public function getStorageTemplate($result = false)
    {
        return $this->relation(
            'storageTemplate',
            $this->hasOne(Template::className(), ['id' => 'templateId']),
            $result
        );
    }

    /**
     * Реляция. Возвращает привязку контейнера.
     * @param bool $result
     * @return ContainerLink|ActiveQuery|null
     */
    public function getLink($result = false)
    {
        return $this->relation(
            'link',
            $this->hasOne(ContainerLink::className(), ['containerId' => 'id']),
            $result
        );
    }

    /**
     * Реляция. Возвращает дочерние контейнеры.
     * @param bool $result
     * @param bool $collection
     * @return Container[]|ActiveQuery|null
     */
    public function getContainers($result = false, $collection = true)
    {
        return $this->relation(
            'containers',
            $this->hasMany(static::className(), ['id' => 'containerId'])
                ->viaTable(ContainerLink::tableName(), ['parentId' => 'id'], function($query) {
                    /** @var ActiveQuery $query */
                    $query->andWhere(['parentType' => static::TYPE]);
                }),
            $result,
            $collection
        );
    }

    /**
     * Возвращает корневой контейнер.
     * @return Container
     */
    public function getRoot()
    {
        $parent = $this->getParent();

        if ($parent instanceof static) {
            return $parent->getRoot();
        } else if ($parent instanceof Variant) {
            $variator = $parent->getVariator(true);

            if (!empty($variator)) {
                $container = $variator->getContainer(true);

                if (!empty($container))
                    return $container->getRoot();
            }
        }

        return $this;
    }

    /**
     * Реляция. Возвращает родительский контейнер.
     * @param bool $result
     * @return Container|ActiveQuery|null
     */
    public function getParentContainer($result = false)
    {
        return $this->relation(
            'parentContainer',
            $this->hasOne(static::className(), ['id' => 'parentId'])
                ->viaTable(ContainerLink::tableName(), ['containerId' => 'id'], function($query) {
                    /** @var ActiveQuery $query */
                    $query->andWhere(['parentType' => static::TYPE]);
                }),
            $result
        );
    }

    /**
     * Реляция. Возвращает родительский вариант.
     * @param bool $result
     * @return Variant|ActiveQuery|null
     */
    public function getParentVariant($result = false)
    {
        return $this->relation(
            'parentVariant',
            $this->hasOne(static::className(), ['id' => 'parentId'])
                ->viaTable(ContainerLink::tableName(), ['containerId' => 'id'], function($query) {
                    /** @var ActiveQuery $query */
                    $query->andWhere(['parentType' => Variant::TYPE]);
                }),
            $result
        );
    }

    /**
     * Возвращает родителя.
     * @return Container|Variant|null
     */
    public function getParent()
    {
        $parent = $this->getParentContainer(true);

        if ($parent instanceof static)
            return $parent;

        $parent = $this->getParentVariant(true);

        if ($parent instanceof Variant)
            return $parent;

        return null;
    }

    /**
     * Возвращает значение свойства по ключу.
     * @param string $key
     * @return null
     */
    public function getProperty($key)
    {
        $key = explode('.', $key);
        return ArrayHelper::getValue($this->properties, $key);
    }

    /**
     * Возвращает все свойства.
     * @return array
     */
    public function getProperties()
    {
        if (Type::isArray($this->properties))
            return $this->properties;

        return [];
    }

    /**
     * Устанавливает значение свойства по ключу.
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function setProperty($key, $value)
    {
        $key = explode('.', $key);
        $properties = $this->properties;
        $result = ArrayHelper::setValue($properties, $key, $value, false);
        $this->properties = $properties;
        return $result;
    }

    /**
     * Устанавливает несколько значений.
     * @param array $values
     * @return bool
     */
    public function setProperties($values)
    {
        $changed = false;

        if (!Type::isArrayable($values))
            return false;

        foreach ($values as $key => $value)
            $changed = $this->setProperty($key, $value) || $changed;

        return $changed;
    }

    /**
     * Удаляет значение свойства по ключу.
     * @param string $key
     */
    public function removeProperty($key)
    {
        $key = explode('.', $key);
        ArrayHelper::unsetValue($this->properties, $key);
    }

    /**
     * Удаляет значения свойств по ключам.
     * @param $keys
     */
    public function removeProperties($keys)
    {
        if (!Type::isArrayable($keys))
            return;

        foreach ($keys as $key)
            $this->removeProperty($key);
    }

    /**
     * Реляция. Возвращает зону данного контейнера.
     * @param bool $result
     * @return Area|ActiveQuery|null
     */
    public function getArea($result = false)
    {
        return $this->relation(
            'area',
            $this->hasOne(Area::className(), ['id' => 'areaId'])
                ->viaTable(AreaLink::tableName(), ['containerId' => 'id']),
            $result
        );
    }

    /**
     * Реляция. Возвращает связь зоны для данного контейнера.
     * @param bool $result
     * @return AreaLink|ActiveQuery|null
     */
    public function getAreaLink($result = false)
    {
        return $this->relation(
            'areaLink',
            $this->hasOne(AreaLink::className(), ['containerId' => 'id']),
            $result
        );
    }

    /**
     * Проверяет, существует ли у контейнера зона.
     * @return bool
     */
    public function hasArea()
    {
        $area = $this->getArea(true);
        return !empty($area);
    }

    /**
     * Реляция. Возвращает блок данного контейнера.
     * @param bool $result
     * @return Block|ActiveQuery|null
     */
    public function getBlock($result = false)
    {
        return $this->relation(
            'block',
            $this->hasOne(Block::className(), ['containerId' => 'id']),
            $result
        );
    }

    /**
     * Проверяет, существует ли у контейнера блок.
     * @return bool
     */
    public function hasBlock()
    {
        $block = $this->getBlock(true);
        return !empty($block);
    }

    /**
     * Реляция. Возвращает компонент данного контейнера.
     * @param bool $result
     * @return Component|ActiveQuery|null
     */
    public function getComponent($result = false)
    {
        return $this->relation(
            'component',
            $this->hasOne(Component::className(), ['containerId' => 'id']),
            $result
        );
    }

    /**
     * Проверяет, существует ли у контейнера компонент.
     * @return bool
     */
    public function hasComponent()
    {
        $component = $this->getComponent(true);
        return !empty($component);
    }

    /**
     * Реляция. Возвращает виджет данного контейнера.
     * @param bool $result
     * @return Widget|ActiveQuery|null
     */
    public function getWidget($result = false)
    {
        return $this->relation(
            'widget',
            $this->hasOne(Widget::className(), ['containerId' => 'id']),
            $result
        );
    }

    /**
     * Проверяет, существует ли у контейнера виджет.
     * @return bool
     */
    public function hasWidget()
    {
        $widget = $this->getWidget(true);
        return !empty($widget);
    }

    /**
     * Реляция. Возвращает вариатор данного контейнера.
     * @param bool $result
     * @return Variator|ActiveQuery|null
     */
    public function getVariator($result = false)
    {
        return $this->relation(
            'variator',
            $this->hasOne(Variator::className(), ['containerId' => 'id']),
            $result
        );
    }

    /**
     * Проверяет, существует ли у контейнера вариатор.
     * @return bool
     */
    public function hasVariator()
    {
        $variator = $this->getVariator(true);
        return !empty($variator);
    }

    /**
     * Возвращает структуру контейнера.
     * @return array
     */
    public function getStructure()
    {
        $structure = [];
        $structure['id'] = $this->id;
        $structure['code'] = $this->code;
        $structure['type'] = $this->type;
        $structure['display'] = Type::toBoolean($this->display);
        $structure['order'] = $this->order;
        $structure['condition'] = $this->condition;
        $structure['script'] = $this->script;
        $structure['properties'] = $this->properties;
        $structure['area'] = null;
        $structure['component'] = null;
        $structure['widget'] = null;
        $structure['block'] = null;
        $structure['variator'] = null;
        $structure['conatainers'] = [];

        if ($this->hasComponent()) {
            $component = $this->getComponent(true);
            $structure['component'] = $component->getStructure();
        } else if ($this->hasWidget()) {
            $widget = $this->getWidget(true);
            $structure['widget'] = $widget->getStructure();
        } else if ($this->hasBlock()) {
            $block = $this->getBlock(true);
            $structure['block'] = $block->getStructure();
        } else if ($this->hasVariator()) {
            $variator = $this->getVariator(true);
            $structure['variator'] = $variator->getStructure();
        } else if ($this->hasArea()) {
            $area = $this->getArea(true);
            $structure['area'] = $area->getStructure();
        } else {
            $containers = $this->getContainers(true);
            /** @var Containers $containers */
            $containers->sortBy('order');

            /** @var Container $container */
            foreach ($containers as $container)
                $structure['containers'][] = $container->getStructure();
        }

        return $structure;
    }

    /**
     * Возвращает идентификатор контейнера.
     * @return string|null
     */
    public function getIdAttribute()
    {
        return $this->getProperty('id');
    }

    /**
     * Устанавливает идентификатор контейнера.
     * @param string|null $value
     * @return string|null
     */
    public function setIdAttribute($value)
    {
        return $this->setProperty('id', $value);
    }

    /**
     * Возвращает класс контейнера.
     * @return string|null
     */
    public function getClassAttribute()
    {
        return $this->getProperty('class');
    }

    /**
     * Устанавливает класс контейнера.
     * @param string|null $value
     * @return string|null
     */
    public function setClassAttribute($value)
    {
        return $this->setProperty('class', $value);
    }

    /**
     * Возвращает значение свойства с единицей измерения.
     * @param string $property
     * @param bool $asArray
     * @param array $measures
     * @return string|null
     */
    protected function getPropertyMeasured($property, $asArray = false, $measures = ['px', '%'])
    {
        $value = $this->getProperty($property.'.value');
        $measure = $this->getProperty($property.'.measure');

        if (!ArrayHelper::isIn($measure, $measures))
            $measure = ArrayHelper::getFirstValue($measures);

        if (Type::isNumeric($value)) {
            if ($asArray) {
                return [
                    'value' => $value,
                    'measure' => $measure
                ];
            }

            return $value.$measure;
        }

        if ($asArray) {
            return [
                'value' => null,
                'measure' => null
            ];
        }

        return null;
    }

    /**
     * Устанавливает значение свойства с единицей измерения.
     * @param string $property
     * @param int|float $value
     * @param string $measure
     * @param array $measures
     * @return bool
     */
    protected function setPropertyMeasured($property, $value, $measure, $measures = ['px', '%'])
    {
        $changed = false;

        if ($value !== false) {
            if (Type::isNumeric($value)) {
                $value = Type::toFloat($value);
                $changed = $this->setProperty($property . '.value', $value);
            } else {
                $changed = $this->setProperty($property . '.value', null);
                $this->setProperty($property.'.measure', null);
            }
        }

        if ($measure !== false) {
            if ($measure === null) {
                $changed = $this->setProperty($property.'.measure', null) || $changed;
            } else {
                if (!ArrayHelper::isIn($measure, $measures))
                    $measure = ArrayHelper::getFirstValue($measures);

                $changed = $this->setProperty($property.'.measure', $measure) || $changed;
            }
        }

        return $changed;
    }

    /**
     * Создает название из свойства css свойство объекта.
     * @param string $name
     * @return string
     */
    protected function getStylePropertyName($name)
    {
        return 'Style'.Inflector::id2camel($name, '-');
    }

    /**
     * Возвращает значение свойства по названию css свойства.
     * @param string $name
     * @return mixed|null
     */
    public function getStyleProperty($name)
    {
        if (!$this->canGetStyleProperty($name))
            return null;

        $name = 'get'.$this->getStylePropertyName($name);
        $class = new \ReflectionClass(static::className());
        $method = $class->getMethod($name);

        return $method->invoke($this);
    }

    /**
     * Устанавливает значение свойства по названию css свойства.
     * @param string $name
     * @param mixed $arguments
     * @return mixed|null
     */
    public function setStyleProperty($name, $arguments)
    {
        if (!$this->canSetStyleProperty($name))
            return false;

        $name = 'set'.$this->getStylePropertyName($name);
        $class = new \ReflectionClass(static::className());
        $method = $class->getMethod($name);
        $parameters = $method->getParameters();
        $value = null;

        if (count($parameters) === 1) {
            if (Type::isArrayable($arguments)) {
                $value = [ArrayHelper::getFirstValue($arguments)];
            } else {
                $value = [$arguments];
            }
        } else if (Type::isArrayable($arguments)) {
            $value = [];

            foreach ($parameters as $parameter) {
                if (ArrayHelper::keyExists($parameter->getName(), $arguments)) {
                    $value[] = $arguments[$parameter->getName()];
                    continue;
                } else if ($parameter->isDefaultValueAvailable()) {
                    $value[] = $parameter->getDefaultValue();
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }

        return $method->invokeArgs($this, $value);
    }

    /**
     * Проверяет наличие css свойства в контейнере.
     * @param string $name
     * @return bool
     */
    public function hasStyleProperty($name)
    {
        return $this->canSetStyleProperty($name) || $this->canGetStyleProperty($name);
    }

    /**
     * Проверяет, возможно ли установить css свойство.
     * @param string $name
     * @return bool
     */
    public function canSetStyleProperty($name)
    {
        return $this->canGetProperty(
            $this->getStylePropertyName($name),
            false, false
        );
    }

    /**
     * Проверяет, возможно ли считать css свойство.
     * @param string $name
     * @return bool
     */
    public function canGetStyleProperty($name)
    {
        return $this->canGetProperty(
            $this->getStylePropertyName($name),
            false, false
        );
    }

    /**
     * Возвращает стили для контейнера.
     * @return array
     */
    public function getStyle()
    {
        $result = [];
        $result['position'] = $this->getStylePosition();
        $result['top'] = $this->getStyleTop();
        $result['right'] = $this->getStyleRight();
        $result['bottom'] = $this->getStyleBottom();
        $result['left'] = $this->getStyleLeft();
        $result['width'] = $this->getStyleWidth();
        $result['min-width'] = $this->getStyleMinWidth();
        $result['max-width'] = $this->getStyleMaxWidth();
        $result['height'] = $this->getStyleHeight();
        $result['min-height'] = $this->getStyleMinHeight();
        $result['max-height'] = $this->getStyleMaxHeight();
        $result['float'] = $this->getStyleFloat();
        $result['opacity'] = $this->getStyleOpacity();
        $result['overflow'] = null;
        $result['overflow-x'] = $this->getStyleOverflowX();
        $result['overflow-y'] = $this->getStyleOverflowY();
        $result['color'] = $this->getStyleColor();
        $result['font-family'] = $this->getStyleFontFamily();

        if (!empty($result['font-family']))
            $result['font-family'] = '\''.$result['font-family'].'\', sans-serif';

        $result['font-size'] = $this->getStyleFontSize();
        $result['line-height'] = $this->getStyleLineHeight();
        $result['letter-spacing'] = $this->getStyleLetterSpacing();
        $result['text-transform'] = $this->getStyleTextTransform();

        if ($result['overflow-x'] === null || $result['overflow-y'] === null)
            $result['overflow'] = $this->getStyleOverflow();

        if ($this->getStyleMargin()) {
            $result['margin'] = $this->getStyleMarginSummary();
        } else {
            $result['margin-top'] = $this->getStyleMarginTop();
            $result['margin-right'] = $this->getStyleMarginRight();
            $result['margin-bottom'] = $this->getStyleMarginBottom();
            $result['margin-left'] = $this->getStyleMarginLeft();
        }

        if ($this->getStylePadding()) {
            $result['padding'] = $this->getStylePaddingSummary();
        } else {
            $result['padding-top'] = $this->getStylePaddingTop();
            $result['padding-right'] = $this->getStylePaddingRight();
            $result['padding-bottom'] = $this->getStylePaddingBottom();
            $result['padding-left'] = $this->getStylePaddingLeft();
        }

        $result['background-color'] = $this->getStyleBackgroundColor();
        $result['background-position'] = $this->getStyleBackgroundPosition();
        $result['background-size'] = $this->getStyleBackgroundSize();
        $result['background-repeat'] = $this->getStyleBackgroundRepeat();

        if ($this->getStyleBackgroundImage()) {
            $result['background-image'] = 'url(\''.$this->getStyleBackgroundImage().'\')';
        }

        if ($this->getStyleBorderRadius()) {
            $result['border-radius'] = $this->getStyleBorderRadiusSummary();
        } else {
            $result['border-top-left-radius'] = $this->getStyleBorderTopLeftRadius();
            $result['border-top-right-radius'] = $this->getStyleBorderTopRightRadius();
            $result['border-bottom-right-radius'] = $this->getStyleBorderBottomRightRadius();
            $result['border-bottom-left-radius'] = $this->getStyleBorderBottomLeftRadius();
        }

        if ($this->getStyleBorder()) {
            $result['border'] = $this->getStyleBorder();
        } else {
            $result['border-width'] = $this->getStyleBorderWidth();
            $result['border-style'] = $this->getStyleBorderStyle();
            $result['border-color'] = $this->getStyleBorderColor();
        }

        if ($this->getStyleBorderTop()) {
            $result['border'] = $this->getStyleBorderTop();
        } else {
            $result['border-top-width'] = $this->getStyleBorderTopWidth();
            $result['border-top-style'] = $this->getStyleBorderTopStyle();
            $result['border-top-color'] = $this->getStyleBorderTopColor();
        }

        if ($this->getStyleBorderRight()) {
            $result['border-right'] = $this->getStyleBorderRight();
        } else {
            $result['border-right-width'] = $this->getStyleBorderRightWidth();
            $result['border-right-style'] = $this->getStyleBorderRightStyle();
            $result['border-right-color'] = $this->getStyleBorderRightColor();
        }

        if ($this->getStyleBorderBottom()) {
            $result['border-bottom'] = $this->getStyleBorderBottom();
        } else {
            $result['border-bottom-width'] = $this->getStyleBorderBottomWidth();
            $result['border-bottom-style'] = $this->getStyleBorderBottomStyle();
            $result['border-bottom-color'] = $this->getStyleBorderBottomColor();
        }

        if ($this->getStyleBorderLeft()) {
            $result['border-left'] = $this->getStyleBorderLeft();
        } else {
            $result['border-left-width'] = $this->getStyleBorderLeftWidth();
            $result['border-left-style'] = $this->getStyleBorderLeftStyle();
            $result['border-left-color'] = $this->getStyleBorderLeftColor();
        }

        foreach ($result as $key => $value)
            if ($value === null)
                unset($result[$key]);

        return $result;
    }

    /**
     * Возвращает стили для контейнера в виде строки.
     * @return string
     */
    public function getStyleAttribute()
    {
        return Html::cssStyleFromArray($this->getStyle());
    }

    /**
     * @inheritdoc
     */
    public function export(&$indexes = null)
    {
        $result = $this->toArray();

        unset($result['id']);
        unset($result['templateId']);
        unset($result['areaId']);
        unset($result['parentId']);

        $result['area'] = null;
        $result['component'] = null;
        $result['widget'] = null;
        $result['block'] = null;
        $result['variator'] = null;
        $result['containers'] = [];

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
            $indexes['containers'] = [];
            $indexes['component'] = null;
            $indexes['widget'] = null;
            $indexes['block'] = null;
            $indexes['variator'] = null;
        } else {
            $indexes = null;
        }

        if ($this->hasComponent()) {
            $componentIndexes = $indexes !== null ? [] : null;
            $component = $this->getComponent(true);
            $result['component'] = $component->export($componentIndexes);

            if ($componentIndexes !== null)
                $indexes['component'] = $componentIndexes;
        } else if ($this->hasWidget()) {
            $widgetIndexes = $indexes !== null ? [] : null;
            $widget = $this->getWidget(true);
            $result['widget'] = $widget->export($widgetIndexes);

            if ($widgetIndexes !== null)
                $indexes['widget'] = $widgetIndexes;
        } else if ($this->hasBlock()) {
            $blockIndexes = $indexes !== null ? [] : null;
            $block = $this->getBlock(true);
            $result['block'] = $block->export($blockIndexes);

            if ($blockIndexes !== null)
                $indexes['block'] = $blockIndexes;
        } else if ($this->hasVariator()) {
            $variatorIndexes = $indexes !== null ? [] : null;
            $variator = $this->getVariator(true);
            $result['variator'] = $variator->export($variatorIndexes);

            if ($variatorIndexes !== null)
                $indexes['variator'] = $variatorIndexes;
        } else if ($this->hasArea()) {
            $area = $this->getArea(true);
            $result['area'] = $area->code;
        } else {
            /** @var Containers $containers */
            $containers = $this->getContainers(true);
            $containers->sortBy('order', SORT_ASC);

            foreach ($containers as $container) {
                $containerIndexes = $indexes !== null ? [] : null;
                $result['containers'][] = $container->export($containerIndexes);

                if ($containerIndexes !== null)
                    $indexes['containers'][] = $containerIndexes;
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data, &$indexes = null)
    {
        $dataArea = ArrayHelper::getValue($data, 'area');
        $dataComponent = ArrayHelper::getValue($data, 'component');
        $dataWidget = ArrayHelper::getValue($data, 'widget');
        $dataBlock = ArrayHelper::getValue($data, 'block');
        $dataVariator = ArrayHelper::getValue($data, 'variator');
        $dataContainers = ArrayHelper::getValue($data, 'containers');

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'id' || $attribute === 'templateId' || $attribute === 'areaId')
                continue;

            $this->setAttribute($attribute, null);
        }

        unset($data['id']);
        unset($data['templateId']);
        unset($data['areaId']);

        $this->load($data, '');
        $this->condition = ArrayHelper::getValue($data, 'condition');
        $this->properties = ArrayHelper::getValue($data, 'properties');

        if (!$this->save())
            return false;

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
            $indexes['containers'] = [];
            $indexes['component'] = null;
            $indexes['widget'] = null;
            $indexes['block'] = null;
            $indexes['variator'] = null;
        } else {
            $indexes = null;
        }

        if ($this->hasComponent()) {
            $component = $this->getComponent(true);
            $component->delete();
        } else if ($this->hasWidget()) {
            $widget = $this->getComponent(true);
            $widget->delete();
        } else if ($this->hasBlock()) {
            $block = $this->getBlock(true);
            $block->delete();
        } else if ($this->hasVariator()) {
            $variator = $this->getVariator(true);
            $variator->delete();
        } else if ($this->hasArea()) {
            $link = $this->getAreaLink(true);
            $link->delete();
        } else {
            $containers = $this->getContainers(true);

            /** @var Container $container */
            foreach ($containers as $container)
                $container->delete();
        }

        $area = null;
        $component = null;
        $widget = null;
        $block = null;
        $containers = [];

        if (Type::isArray($dataComponent)) {
            $componentIndexes = $indexes !== null ? [] : null;
            $component = Component::create();
            $component->templateId = $this->templateId;
            $component->areaId = $this->areaId;
            $component->containerId = $this->id;

            if (!$component->import($dataComponent, $componentIndexes))
                $component = null;

            if ($componentIndexes !== null)
                $indexes['component'] = $componentIndexes;
        } else if (Type::isArray($dataWidget)) {
            $widgetIndexes = $indexes !== null ? [] : null;
            $widget = Widget::create();
            $widget->templateId = $this->templateId;
            $widget->areaId = $this->areaId;
            $widget->containerId = $this->id;

            if (!$widget->import($dataWidget, $widgetIndexes))
                $widget = null;

            if ($widgetIndexes !== null)
                $indexes['widget'] = $widgetIndexes;
        } else if (Type::isArray($dataBlock)) {
            $blockIndexes = $indexes !== null ? [] : null;
            $block = Block::create();
            $block->templateId = $this->templateId;
            $block->areaId = $this->areaId;
            $block->containerId = $this->id;

            if (!$block->import($dataBlock, $blockIndexes))
                $block = null;

            if ($blockIndexes !== null)
                $indexes['block'] = $blockIndexes;
        } else if (Type::isArray($dataVariator)) {
            $variatorIndexes = $indexes !== null ? [] : null;
            $variator = Variator::create();
            $variator->templateId = $this->templateId;
            $variator->areaId = $this->areaId;
            $variator->containerId = $this->id;

            if (!$variator->import($dataVariator, $variatorIndexes))
                $variator = null;

            if ($variatorIndexes !== null)
                $indexes['variator'] = $variatorIndexes;
        } else if (!empty($dataArea)) {
            $build = null;

            if (!empty($this->templateId)) {
                $template = $this->getStorageTemplate(true);

                if (!empty($template))
                    $build = $template->getBuild(true);
            } else if (!empty($this->areaId)) {
                $area = $this->getStorageArea(true);

                if (!empty($area))
                    $build = $area->getBuild(true);
            }

            if (!empty($build)) {
                $area = Area::find()
                    ->where([
                        'code' => $dataArea,
                        'buildId' => $build->id
                    ])
                    ->one();
                /** @var Area $area */

                if (!empty($area)) {
                    $areaLink = $this->getAreaLink(true);

                    if (empty($areaLink)) {
                        $areaLink = new AreaLink();
                        $areaLink->containerId = $this->id;
                    }

                    $areaLink->areaId = $area->id;
                    $areaLink->save();
                }
            }
        } else if (Type::isArray($dataContainers)) {
            foreach ($dataContainers as $dataContainer) {
                $containerIndexes = $indexes !== null ? [] : null;
                $container = static::create();
                $container->templateId = $this->templateId;
                $container->areaId = $this->areaId;

                if ($container->import($dataContainer, $containerIndexes)) {
                    $containers[] = $container;
                    $link = $container->getLink(true);

                    if (empty($link)) {
                        $link = new ContainerLink();
                        $link->containerId = $container->id;
                    }

                    $link->parentId = $this->id;
                    $link->parentType = static::TYPE;
                    $link->save();

                    if ($containerIndexes !== null)
                        $indexes['containers'][] = $containerIndexes;
                }
            }
        }

        $this->populateRelation('component', $component);
        $this->populateRelation('widget', $widget);
        $this->populateRelation('block', $block);
        $this->populateRelation('variator', $variator);
        $this->populateRelation('containers', $containers);

        return true;
    }
}