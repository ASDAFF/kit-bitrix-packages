<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\base\Collection;
use intec\core\db\ActiveQuery;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\Build;
use intec\constructor\models\build\template\Block;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Containers;
use intec\constructor\models\build\template\Component;
use intec\constructor\models\build\template\Value as TemplateValue;
use intec\constructor\models\build\template\Variator;
use intec\constructor\models\build\template\Widget;
use intec\constructor\models\build\theme\Value as ThemeValue;

/**
 * Class Template
 * @property int $id
 * @property int $buildId
 * @property string $code
 * @property int $active
 * @property int $default
 * @property string $themeCode
 * @property string $name
 * @property int $sort
 * @property array $condition
 * @property string $css
 * @property string $less
 * @property string $js
 * @property string $settings
 * @package intec\constructor\models\build
 */
class Template extends ActiveRecord implements Exchangeable, ConditionsInterface
{
    use ConditionsTrait;

    const TYPE = 'template';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустой шаблон.
     * @return static
     */
    public static function create()
    {
        $instance = new static();
        $instance->loadDefaultValues();
        $instance->buildId = null;
        $instance->condition = [
            'type' => 'group',
            'operator' => 'and',
            'conditions' => [],
            'result' => 1
        ];

        $instance->populateRelation('containers', []);
        $instance->populateRelation('values', []);

        return $instance;
    }

    /**
     * Коллекция свойств.
     * @var Collection
     */
    protected $_properties;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_templates';
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            /** @var Containers $containers */
            $containers = $this->getContainers(true);
            /** @var Container $container */
            $container = $containers->getRootContainer($this);

            if ($container) {
                $container->delete();
            } else if ($containers->count() > 0) {
                /** @var Container $container */
                foreach ($containers as $container)
                    $container->delete();
            }

            return true;
        }

        return false;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->default == 1) {
                /** @var ActiveRecords $templates */
                $templates = static::find()->where([
                    'buildId' => $this->buildId,
                    'default' => 1
                ])->all();

                /** @var static $template */
                foreach ($templates as $template) {
                    if ($template->id == $this->id)
                        continue;

                    $template->default = 0;
                    $template->save();
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'condition' => [
                'class' => 'intec\core\behaviors\FieldArray',
                'attribute' => 'condition'
            ],
            'settings' => [
                'class' => 'intec\core\behaviors\FieldArray',
                'attribute' => 'settings'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buildId', 'sort'], 'integer'],
            [['code', 'themeCode', 'name'], 'string', 'max' => 255],
            [['active', 'default'], 'boolean', 'strict' => false],
            [['settings', 'css', 'less', 'js'], 'string'],
            [['active'], 'default', 'value' => 1],
            [['default'], 'default', 'value' => 0],
            [['sort'], 'default', 'value' => 500],
            [['code'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/'],
            [['buildId', 'code'], 'unique', 'targetAttribute' => ['buildId', 'code']],
            [['buildId', 'code', 'active', 'default', 'name', 'sort'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => GetMessage('intec.constructor.models.build.attributes.labels.id'),
            'buildId' => GetMessage('intec.constructor.models.build.attributes.labels.buildId'),
            'code' => GetMessage('intec.constructor.models.build.attributes.labels.code'),
            'active' => GetMessage('intec.constructor.models.build.attributes.labels.active'),
            'default' => GetMessage('intec.constructor.models.build.attributes.labels.default'),
            'themeCode' => GetMessage('intec.constructor.models.build.attributes.labels.themeCode'),
            'name' => GetMessage('intec.constructor.models.build.attributes.labels.name'),
            'sort' => GetMessage('intec.constructor.models.build.attributes.labels.sort'),
            'css' => GetMessage('intec.constructor.models.build.attributes.labels.css'),
            'less' => GetMessage('intec.constructor.models.build.attributes.labels.less'),
            'js' => GetMessage('intec.constructor.models.build.attributes.labels.js'),
            'settings' => GetMessage('intec.constructor.models.build.attributes.labels.settings')
        ];
    }

    /**
     * Реляция. Возвращает сборку к которой принадлежит шаблон.
     * @param bool $result
     * @return Build|ActiveQuery|null
     */
    public function getBuild($result = false)
    {
        return $this->relation(
            'build',
            $this->hasOne(Build::className(), ['id' => 'buildId']),
            $result
        );
    }

    /**
     * Реляция. Возвращает свойства, связанные со сборкой шаблона.
     * @param bool $result
     * @param bool $collection
     * @return Property[]|ActiveRecords|ActiveQuery|null
     */
    public function getProperties($result = false, $collection = true)
    {
        return $this->relation(
            'properties',
            $this->hasMany(Property::className(), ['buildId' => 'buildId']),
            $result,
            $collection
        );
    }

    /**
     * Возвращает значение свойства по коду.
     * @param bool $theme Использовать тему.
     * - true - Брать тему шаблона.
     * - false - Не использовать.
     * - Theme - Указать тему.
     * @param bool $reset Получить настройки из базы заного.
     * @return Collection
     */
    public function getPropertiesValues($theme = true, $reset = false)
    {
        if ($this->_properties === null || $reset) {
            $this->_properties = new Collection();

            if (!$theme instanceof Theme)
                if ($theme === true)
                    $theme = $this->getTheme(true);

            if (!$theme instanceof Theme)
                $theme = null;

            /** @var ActiveRecords $theme */

            $properties = $this->getProperties(true);
            /** @var ActiveRecords $properties */

            $templateValues = $this->getValues(true);
            /** @var ActiveRecords $templateValues */
            $templateValues->indexBy('propertyCode');

            $themeValues = null;
            /** @var ActiveRecords $themeValues */

            if (!empty($theme)) {
                $themeValues = $theme->getValues(true);
                $themeValues->indexBy('propertyCode');
            }

            /** @var Property $property */
            foreach ($properties as $property) {
                $isSet = false;
                $value = null;

                if (!empty($themeValues)) {
                    if ($themeValues->exists($property->code)) {
                        /** @var ThemeValue $value */
                        $value = $themeValues->get($property->code);
                        $value = $value->value;
                        $isSet = true;
                    }
                }

                if (!$isSet) {
                    if ($templateValues->exists($property->code)) {
                        /** @var ThemeValue $value */
                        $value = $templateValues->get($property->code);
                        $value = $value->value;
                    } else {
                        $value = $property->default;
                    }
                }

                $this->_properties->set($property->code, $value);
            }
        }

        return $this->_properties;
    }

    /**
     * Реляция. Возвращает темы, связанные со сборкой шаблона.
     * @param bool $result
     * @param bool $collection
     * @return Theme[]|ActiveRecords|ActiveQuery|null
     */
    public function getThemes($result = false, $collection = true)
    {
        return $this->relation(
            'themes',
            $this->hasMany(Theme::className(), ['buildId' => 'buildId']),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает тему, связанную с шаблоном.
     * @param bool $result
     * @return Theme|ActiveQuery|null
     */
    public function getTheme($result = false)
    {
        return $this->relation(
            'theme',
            $this->hasOne(Theme::className(), [
                'buildId' => 'buildId',
                'code' => 'themeCode'
            ]),
            $result
        );
    }

    /**
     * Реляция. Возвращает блоки шаблона.
     * @param bool $result
     * @param bool $collection
     * @return Block[]|ActiveRecords|ActiveQuery|null
     */
    public function getBlocks($result = false, $collection = true)
    {
        return $this->relation(
            'blocks',
            $this->hasMany(Block::className(), ['templateId' => 'id']),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает контейнеры шаблона.
     * @param bool $result
     * @param bool $collection
     * @return Container[]|ActiveRecords|ActiveQuery|null
     */
    public function getContainers($result = false, $collection = true)
    {
        return $this->relation(
            'containers',
            $this->hasMany(Container::className(), ['templateId' => 'id']),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает компоненты шаблона.
     * @param bool $result
     * @param bool $collection
     * @return Component[]|ActiveRecords|ActiveQuery|null
     */
    public function getComponents($result = false, $collection = true)
    {
        return $this->relation(
            'components',
            $this->hasMany(Component::className(), ['templateId' => 'id']),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает значения свойств шаблона.
     * @param bool $result
     * @param bool $collection
     * @return TemplateValue[]|ActiveRecords|ActiveQuery|null
     */
    public function getValues($result = false, $collection = true)
    {
        return $this->relation(
            'values',
            $this->hasMany(TemplateValue::className(), [
                'templateId' => 'id',
                'buildId' => 'buildId'
            ]),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает вариаторы шаблона.
     * @param bool $result
     * @param bool $collection
     * @return Variator[]|ActiveRecords|ActiveQuery|null
     */
    public function getVariators($result = false, $collection = true)
    {
        return $this->relation(
            'variators',
            $this->hasMany(Variator::className(), ['templateId' => 'id']),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает виджеты шаблона.
     * @param bool $result
     * @param bool $collection
     * @return Widget[]|ActiveRecords|ActiveQuery|null
     */
    public function getWidgets($result = false, $collection = true)
    {
        return $this->relation(
            'widgets',
            $this->hasMany(Widget::className(), ['templateId' => 'id']),
            $result,
            $collection
        );
    }

    /**
     * Возвращает Less для шаблона.
     * @param bool $theme Использовать тему.
     * - true - Брать тему шаблона.
     * - false - Не использовать.
     * - array - Массив со свойствами.
     * - Theme - Указать тему.
     * @return string Less шаблона.
     */
    public function getLess($theme = true)
    {
        $less = Core::$app->web->less;
        $properties = null;

        $build = $this->getBuild(true);

        if (empty($build))
            return null;

        $value = $build->less."\r\n".$this->less;

        if (Type::isArray($theme)) {
            $properties = $theme;
        } else {
            $properties = $this->getPropertiesValues($theme)->asArray();
        }

        return $less->compile(
            $value,
            $properties
        );
    }

    /**
     * Возвращает стили Css для данного шаблона.
     * @return string
     */
    public function getCss()
    {
        $build = $this->getBuild(true);

        if (empty($build))
            return null;

        return $build->css."\r\n".$this->css;
    }

    /**
     * Возвращает Js для данного шаблона.
     * @return string
     */
    public function getJs()
    {
        $build = $this->getBuild(true);

        if (empty($build))
            return null;

        return $build->js."\r\n".$this->js;
    }

    /**
     * @inheritdoc
     */
    public function export(&$indexes = null)
    {
        $result = $this->toArray();
        $result['theme'] = $result['themeCode'];

        unset($result['id']);
        unset($result['buildId']);
        unset($result['themeCode']);

        $result['container'] = null;
        $result['values'] = [];

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
            $indexes['container'] = null;
        } else {
            $indexes = null;
        }

        $containers = $this->getContainers()
            ->with(['component', 'widget', 'block', 'variator', 'variator.variants'])
            ->all();
        /** @var Containers $containers */

        /** @var Container $container */
        $container = $containers->getTree(null, $this);

        if (!empty($container)) {
            $containerIndexes = $indexes !== null ? [] : null;
            $result['container'] = $container->export($containerIndexes);

            if ($containerIndexes !== null)
                $indexes['container'] = $containerIndexes;
        }

        /** @var ActiveRecords $values */
        $values = $this->getValues(true);

        /** @var TemplateValue $value */
        foreach ($values as $value)
            $result['values'][] = $value->export();

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data, &$indexes = null)
    {
        $dataContainer = ArrayHelper::getValue($data, 'container');
        $dataValues = ArrayHelper::getValue($data, 'values');
        $data['themeCode'] = ArrayHelper::getValue($data, 'theme');

        unset($data['id']);
        unset($data['buildId']);
        unset($data['theme']);

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'id' || $attribute === 'buildId')
                continue;

            $this->setAttribute($attribute, null);
        }

        $this->load($data, '');
        $this->condition = ArrayHelper::getValue($data, 'condition');
        $this->settings = ArrayHelper::getValue($data, 'settings');

        if (!$this->save())
            return false;

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
            $indexes['container'] = null;
        } else {
            $indexes = null;
        }

        $clean = [];
        $clean['containers'] = $this->getContainers()
            ->with(['component', 'widget', 'block', 'variator', 'variator.variants'])
            ->all();

        $clean['values'] = $this->getValues(true);

        foreach ($clean as $items) {
            foreach ($items as $item) {
                /** @var ActiveRecord $item */
                $item->delete();
            }
        }

        unset($clean);

        if (Type::isArray($dataContainer)) {
            $containerIndexes = $indexes !== null ? [] : null;
            $container = Container::create();
            $container->templateId = $this->id;

            if ($container->import($dataContainer, $containerIndexes)) {
                $link = $container->getLink(true);

                if (!empty($link))
                    $link->delete();
            } else {
                $container = null;
            }

            if ($containerIndexes !== null)
                $indexes['container'] = $containerIndexes;
        }

        $values = [];

        if (Type::isArray($dataValues))
            foreach ($dataValues as $dataValue) {
                $value = new TemplateValue();
                $value->buildId = $this->buildId;
                $value->templateId = $this->id;

                if ($value->import($dataValue))
                    $values[] = $value;
            }

        $this->populateRelation('values', $values);

        return true;
    }
}