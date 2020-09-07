<?php
namespace intec\constructor\models\build;

use Bitrix\Main\Localization\Loc;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveRecords;
use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\Build;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Containers;

/**
 * Class Area
 * @property integer $id
 * @property string $code
 * @property integer $buildId
 * @property string $name
 * @property integer $sort
 * @property Template $template Текущий шаблон для зоны.
 * @property Container $parentContainer Текущий контейнер для зоны.
 * @package intec\constructor\models\build
 */
class Area extends ActiveRecord implements Exchangeable
{
    const TYPE = 'area';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустую зону.
     * @return static
     */
    public static function create()
    {
        $instance = new static();
        $instance->loadDefaultValues();
        $instance->buildId = null;

        $instance->populateRelation('containers', []);

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_areas';
    }

    /**
     * @var Template
     */
    protected $_template;
    /**
     * @var Container
     */
    protected $_container = false;
    /**
     * @var Container
     */
    protected $_parentContainer;

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'code' => [['code'], 'string', 'max' => 255],
            'buildId' => [['buildId'], 'integer'],
            'name' => [['name'], 'string', 'max' => 255],
            'sort' => [['sort'], 'integer'],
            'codeMatch' => [['code'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/'],
            'codeUnique' => [['code'], 'unique', 'targetAttribute' => ['code', 'buildId']],
            'sortDefault' => [['sort'], 'default', 'value' => 500],
            'required' => [['code', 'buildId', 'name', 'sort'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Loc::getMessage('intec.constructor.models.build.area.attributes.labels.id'),
            'code' => Loc::getMessage('intec.constructor.models.build.area.attributes.labels.code'),
            'buildId' => Loc::getMessage('intec.constructor.models.build.area.attributes.labels.buildId'),
            'name' => Loc::getMessage('intec.constructor.models.build.area.attributes.labels.name'),
            'sort' => Loc::getMessage('intec.constructor.models.build.area.attributes.labels.sort')
        ];
    }

    /**
     * Реляция. Возвращает сборку к которой принадлежит зона.
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
     * Возвращает текущий шаблон для зоны.
     * @return Template|null
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Устанавливает текущий шаблон для зоны.
     * @param Template|null $value
     * @return $this
     */
    public function setTemplate($value)
    {
        if (!($value instanceof Template))
            $value = null;

        $this->_template = $value;

        return $this;
    }

    /**
     * Возвращает текущий родительский контейнер для зоны.
     * @return Container|null
     */
    public function getParentContainer()
    {
        return $this->_parentContainer;
    }

    /**
     * Устанавливает текущий родительский контейнер для зоны.
     * @param Container|null $value
     * @return $this
     */
    public function setParentContainer($value)
    {
        if (!($value instanceof Container))
            $value = null;

        $this->_parentContainer = $value;

        return $this;
    }

    /**
     * Реляция. Возвращает контейнеры, содержащие зону.
     * @param boolean $result Возвращать результат.
     * @param bool $collection Возвращать результат в виде коллекции.
     * @return Container[]|ActiveRecords|ActiveQuery|null
     */
    public function getParentContainers($result = false, $collection = true)
    {
        return $this->relation(
            'parentContainers',
            $this->hasMany(Container::className(), ['id' => 'parentId'])
                ->viaTable(AreaLink::tableName(), ['areaId' => 'id']),
            $result,
            $collection
        );
    }

    /**
     * Возвращает текущий дочерний контейнер для зоны.
     * @var bool $reset Сбросить кеш.
     * @return Container|null
     */
    public function getContainer($reset = false)
    {
        if ($this->_container === false || $reset) {
            $this->_container = null;
            $containers = $this->getContainers(true);

            foreach ($containers as $container) {
                /** @var Container $container */
                $link = $container->getLink(true);

                if ($link === null) {
                    $this->_container = $container;
                    break;
                }
            }
        }

        return $this->_container;
    }

    /**
     * Устанавливает текущий дочерний контейнер для зоны.
     * @param Container|null $value
     * @return $this
     */
    public function setContainer($value)
    {
        if (!($value instanceof Container))
            $value = null;

        $this->_container = $value;

        return $this;
    }

    /**
     * Реляция. Возвращает контейнеры зоны.
     * @param bool $result
     * @param bool $collection
     * @return Container[]|ActiveRecords|ActiveQuery|null
     */
    public function getContainers($result = false, $collection = true)
    {
        return $this->relation(
            'containers',
            $this->hasMany(Container::className(), ['areaId' => 'id']),
            $result,
            $collection
        );
    }

    /**
     * Возвращает структуру зоны.
     * @return array
     */
    public function getStructure()
    {
        $structure = [];
        $structure['id'] = $this->id;
        $structure['name'] = $this->name;
        $structure['container'] = null;

        $container = $this->getContainer();

        if (!empty($container))
            $structure['container'] = $container->getStructure();

        return $structure;
    }

    /**
     * @inheritdoc
     */
    public function export(&$indexes = null)
    {
        $result = $this->toArray();

        unset($result['id']);
        unset($result['buildId']);

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
            $indexes['container'] = [];
        } else {
            $indexes = null;
        }

        $result['container'] = null;

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

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data, &$indexes = null)
    {
        $dataContainer = ArrayHelper::getValue($data, 'container');

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'id' || $attribute === 'buildId')
                continue;

            $this->setAttribute($attribute, null);
        }

        unset($data['id']);
        unset($data['buildId']);

        $this->load($data, '');

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
            $container->areaId = $this->id;

            if (!$container->import($dataContainer, $containerIndexes))
                $container = null;

            if ($containerIndexes !== null)
                $indexes['container'] = $containerIndexes;
        }

        return true;
    }
}