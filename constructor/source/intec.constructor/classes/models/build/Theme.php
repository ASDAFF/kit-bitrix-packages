<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\constructor\models\build\theme\Value;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\Build;

/**
 * Class Theme
 * @property int $buildId
 * @property string $code
 * @property string $name
 * @property int $sort
 * @package intec\constructor\models\build
 */
class Theme extends ActiveRecord implements Exchangeable
{
    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_themes';
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $values = $this->getValues(true);

        foreach ($values as $value) {
            $value->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buildId', 'sort'], 'integer'],
            [['code'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 255],
            [['sort'], 'default', 'value' => 500],
            [['code'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/'],
            [['buildId', 'code'], 'unique', 'targetAttribute' => ['buildId', 'code']],
            [['buildId', 'code', 'name', 'sort'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'buildId' => GetMessage('intec.constructor.models.build.theme.attributes.labels.buildId'),
            'code' => GetMessage('intec.constructor.models.build.theme.attributes.labels.code'),
            'name' => GetMessage('intec.constructor.models.build.theme.attributes.labels.name'),
            'sort' => GetMessage('intec.constructor.models.build.theme.attributes.labels.sort')
        ];
    }

    /**
     * Реляция. Возвращает сборку темы.
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
     * Реляция. Возвращает свойства, связанные со сборкой темы.
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
     * Реляция. Возвращает значения темы.
     * @param bool $result
     * @param bool $collection
     * @return Value[]|ActiveRecords|ActiveQuery|null
     */
    public function getValues($result = false, $collection = true)
    {
        return $this->relation(
            'values',
            $this->hasMany(Value::className(), [
                'buildId' => 'buildId',
                'themeCode' => 'code'
            ]),
            $result,
            $collection
        );
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $result = $this->toArray();
        $result['values'] = [];

        unset($result['buildId']);

        /** @var ActiveRecords $values */
        $values = $this->getValues(true);

        /** @var Value $value */
        foreach ($values as $value)
            $result['values'][] = $value->export();

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data)
    {
        $dataValues = ArrayHelper::getValue($data, 'values');

        unset($data['buildId']);
        unset($data['values']);

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'buildId')
                continue;

            $this->setAttribute($attribute, null);
        }

        $this->load($data, '');

        if (!$this->save())
            return false;

        $clean = [];
        $clean['values'] = $this->getValues(true);

        foreach ($clean as $items) {
            foreach ($items as $item) {
                /** @var ActiveRecord $item */
                $item->delete();
            }
        }

        unset($clean);

        $values = [];

        if (Type::isArray($dataValues))
            foreach ($dataValues as $dataValue) {
                $value = new Value();
                $value->buildId = $this->buildId;
                $value->themeCode = $this->code;

                if ($value->import($dataValue))
                    $values[] = $value;
            }

        $this->populateRelation('values', $values);

        return true;
    }
}