<?php
namespace intec\constructor\models\build\template;

use Bitrix\Main\Localization\Loc;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveRecords;
use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\variator\Variant;

/**
 * Class Variator
 * @property integer $id
 * @property integer $templateId
 * @property integer $areaId
 * @property integer $containerId
 * @property integer $variant
 * @package intec\constructor\models\build\template
 */
class Variator extends ActiveRecord implements Exchangeable
{
    const TYPE = 'variator';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустой вариатор.
     * @return static
     */
    public static function create()
    {
        $instance = new static();
        $instance->loadDefaultValues();
        $instance->templateId = null;
        $instance->areaId = null;
        $instance->containerId = null;

        $instance->populateRelation('variants', []);

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_templates_variators';
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $variants = $this->getVariants(true);

        foreach ($variants as $variant) {
            /** @var Variant $variant */
            $variant->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'templateId' => ['templateId', 'integer'],
            'areaId' => ['areaId', 'integer'],
            'containerId' => ['containerId', 'integer'],
            'variant' => ['variant', 'integer'],
            'variantDefault' => ['variant', 'default', 'value' => 0],
            'required' => [['containerId', 'variant'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Loc::getMessage('intec.constructor.models.template.variator.attributes.labels.id'),
            'templateId' => Loc::getMessage('intec.constructor.models.template.variator.attributes.labels.templateId'),
            'containerId' => Loc::getMessage('intec.constructor.models.template.variator.attributes.labels.containerId'),
            'variant' => Loc::getMessage('intec.constructor.models.template.variator.attributes.labels.variant')
        ];
    }

    /**
     * Реляция. Возвращает зону к которой принадлежит вариатор.
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
     * Реляция. Возвращает шаблон к которому принадлежит вариатор.
     * @param boolean $result Возвращать результат.
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
     * Реляция. Возвращает контейнер вариатора.
     * @param boolean $result Возвращать результат.
     * @return Container|ActiveQuery|null
     */
    public function getContainer($result = false)
    {
        return $this->relation(
            'container',
            $this->hasOne(Container::className(), ['id' => 'containerId']),
            $result
        );
    }

    /**
     * Реляция. Возвращает варианты вариатора.
     * @param boolean $result Возвращать результат.
     * @param boolean $collection Возвращаеть коллекцию.
     * @param boolean $ordered Упорядочить коллекцию.
     * @return ActiveRecords|Variant[]|ActiveQuery|null
     */
    public function getVariants($result = false, $collection = true, $ordered = false)
    {
        $return = $this->relation(
            'variants',
            $this->hasMany(Variant::className(), ['variatorId' => 'id']),
            $result,
            $collection
        );

        if ($result && $collection && $ordered) {
            /** @var ActiveRecords $return */
            $return->sortBy('order')->reindex();
        }

        return $return;
    }

    /**
     * Возвращает текущий выбранный вариант, если доступен.
     * @return Variant|null
     */
    public function getVariant()
    {
        if ($this->variant === null)
            return null;

        $variants = $this->getVariants(true, true, true);

        if ($this->variant >= $variants->getCount())
            return null;

        return $variants->get($this->variant);
    }

    /**
     * Возвращает структуру вариатора.
     * @return array
     */
    public function getStructure()
    {
        $structure = [];
        $structure['id'] = $this->id;
        $structure['variant'] = $this->variant;
        $structure['variants'] = [];

        $variants = $this->getVariants(true, true, true);

        /** @var Variant $variant */
        foreach ($variants as $variant)
            $structure['variants'][] = $variant->getStructure();

        return $structure;
    }

    /**
     * @inheritdoc
     */
    public function export(&$indexes = null)
    {
        $result = $this->toArray();
        $variants = $this->getVariants(true);
        $variants->sortBy('order', SORT_ASC);

        unset($result['id']);
        unset($result['templateId']);
        unset($result['areaId']);
        unset($result['containerId']);

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
            $indexes['variants'] = [];
        } else {
            $indexes = null;
        }

        $result['variants'] = [];

        if (!empty($variants))
            foreach ($variants as $variant) {
                $variantIndexes = $indexes !== null ? [] : null;

                $result['variants'][] = $variant->export($variantIndexes);

                if ($variantIndexes !== null)
                    $indexes['variants'][] = $variantIndexes;
            }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data, &$indexes = null)
    {
        $dataVariants = ArrayHelper::getValue($data, 'variants');

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'id' || $attribute === 'templateId' || $attribute === 'areaId' || $attribute === 'containerId')
                continue;

            $this->setAttribute($attribute, null);
        }

        unset($data['id']);
        unset($data['templateId']);
        unset($data['areaId']);
        unset($data['containerId']);

        $this->load($data, '');
        $result = $this->save();

        if ($result) {
            $variants = $this->getVariants(true);

            foreach ($variants as $variant)
                $variant->delete();

            $variants = [];

            if (Type::isArray($indexes)) {
                $indexes['id'] = $this->id;
                $indexes['variants'] = [];
            } else {
                $indexes = null;
            }

            if (Type::isArray($dataVariants)) {
                foreach ($dataVariants as $dataVariant) {
                    $variantIndexes = $indexes !== null ? [] : null;
                    $variant = Variant::create();
                    $variant->templateId = $this->templateId;
                    $variant->areaId = $this->areaId;
                    $variant->variatorId = $this->id;

                    if ($variant->import($dataVariant, $variantIndexes)) {
                        $variants[] = $variant;

                        if ($variantIndexes !== null)
                            $indexes['variants'][] = $variantIndexes;
                    }
                }
            }

            $this->populateRelation('variants', $variants);
        }

        return $result;
    }
}