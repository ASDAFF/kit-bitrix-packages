<?php
namespace intec\constructor\models\build\template\variator;

use Bitrix\Main\Localization\Loc;
use intec\constructor\models\build\Area;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\ContainerLink;
use intec\constructor\models\build\template\Variator;

/**
 * Class Variant
 * @property integer $id
 * @property string $code
 * @property integer $templateId
 * @property integer $areaId
 * @property integer $variatorId
 * @property integer $order
 * @property integer $name
 * @package intec\constructor\models\build\template
 */
class Variant extends ActiveRecord implements Exchangeable
{
    const TYPE = 'variatorVariant';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустой вариант.
     * @return static
     */
    public static function create()
    {
        $instance = new static();
        $instance->templateId = null;
        $instance->areaId = null;
        $instance->variatorId = null;
        $instance->populateRelation('container', null);

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_templates_variators_variants';
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $container = $this->getContainer(true);

        if ($container)
            $container->delete();
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
    public function rules()
    {
        return [
            'code' => [['code'], 'string', 'max' => 255],
            'templateId' => ['templateId', 'integer'],
            'areaId' => ['areaId', 'integer'],
            'variatorId' => ['variatorId', 'integer'],
            'order' => ['order', 'integer'],
            'orderDefault' => ['order', 'default', 'value' => 0],
            'name' => ['name', 'string', 'max' => 255],
            'required' => [['variatorId', 'order'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Loc::getMessage('intec.constructor.models.template.variator.variant.attributes.labels.id'),
            'templateId' => Loc::getMessage('intec.constructor.models.template.variator.variant.attributes.labels.templateId'),
            'containerId' => Loc::getMessage('intec.constructor.models.template.variator.variant.attributes.labels.containerId'),
            'order' => Loc::getMessage('intec.constructor.models.template.variator.variant.attributes.labels.order'),
            'name' => Loc::getMessage('intec.constructor.models.template.variator.variant.attributes.labels.name')
        ];
    }

    /**
     * Реляция. Возвращает зону к которой принадлежит вариант.
     * @param bool $result
     * @return Area|ActiveQuery|null
     */
    public function getArea($result = false)
    {
        return $this->relation(
            'area',
            $this->hasOne(Area::className(), ['id' => 'areaId']),
            $result
        );
    }

    /**
     * Реляция. Возвращает шаблон к которому принадлежит вариант вариатора.
     * @param bool $result
     * @return Template|ActiveQuery|null
     */
    public function getTemplate($result = false)
    {
        return $this->relation(
            'template',
            $this->hasOne(Template::className(), ['id' => 'templateId']),
            $result
        );
    }

    /**
     * Реляция. Возвращает вариатор, которому принадлежит вариант.
     * @param bool $result
     * @return Variator|ActiveQuery|null
     */
    public function getVariator($result = false)
    {
        return $this->relation(
            'variator',
            $this->hasOne(Variator::className(), ['id' => 'variatorId']),
            $result
        );
    }

    /**
     * Реляция. Возвращает корневой контейнер варианта.
     * @param bool $result
     * @return Container|ActiveQuery|null
     */
    public function getContainer($result = false)
    {
        return $this->relation(
            'container',
            $this->hasOne(Container::className(), ['id' => 'containerId'])
                ->viaTable(ContainerLink::tableName(), ['parentId' => 'id'], function($query) {
                    /** @var ActiveQuery $query */
                    $query->andWhere(['parentType' => static::TYPE]);
                }),
            $result
        );
    }

    /**
     * Возвращает структуру варианта.
     * @return array
     */
    public function getStructure()
    {
        $structure = [];
        $structure['id'] = $this->id;
        $structure['code'] = $this->code;
        $structure['order'] = $this->order;
        $structure['name'] = $this->name;
        $structure['container'] = null;

        $container = $this->getContainer(true);

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
        $container = $this->getContainer(true);

        unset($result['id']);
        unset($result['templateId']);
        unset($result['areaId']);
        unset($result['variatorId']);

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
            $indexes['container'] = null;
        } else {
            $indexes = null;
        }

        $result['container'] = null;

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
            if ($attribute === 'id' || $attribute === 'templateId' || $attribute === 'areaId' || $attribute === 'variatorId')
                continue;

            $this->setAttribute($attribute, null);
        }

        unset($data['id']);
        unset($data['templateId']);
        unset($data['areaId']);
        unset($data['variatorId']);

        $this->load($data, '');
        $result = $this->save();

        if ($result) {
            $container = $this->getContainer(true);

            if ($container)
                $container->delete();

            $container = null;

            if (Type::isArray($indexes)) {
                $indexes['id'] = $this->id;
                $indexes['container'] = null;
            } else {
                $indexes = null;
            }

            if (Type::isArray($dataContainer)) {
                $containerIndexes = $indexes !== null ? [] : null;
                $container = Container::create();
                $container->templateId = $this->templateId;
                $container->areaId = $this->areaId;

                if ($container->import($dataContainer, $containerIndexes)) {
                    $link = $container->getLink(true);

                    if (empty($link)) {
                        $link = new ContainerLink();
                        $link->containerId = $container->id;
                    }

                    $link->parentId = $this->id;
                    $link->parentType = static::TYPE;
                    $link->save();

                    if ($containerIndexes !== null)
                        $indexes['container'] = $containerIndexes;
                }
            }

            $this->populateRelation('container', $container);
        }

        return $result;
    }
}