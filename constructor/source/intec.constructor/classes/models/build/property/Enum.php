<?php
namespace intec\constructor\models\build\property;
IncludeModuleLangFile(__FILE__);

use intec\constructor\models\build\Property;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\Build;

/**
 * Class Enum
 * @property int $buildId
 * @property string $propertyCode
 * @property string $code
 * @property string $name
 * @property int $sort
 * @package intec\constructor\models\build
 */
class Enum extends ActiveRecord implements Exchangeable
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
        return 'constructor_builds_properties_enums';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buildId', 'sort'], 'integer'],
            [['propertyCode', 'code'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 255],
            [['sort'], 'default', 'value' => 500],
            [['propertyCode', 'code'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/'],
            [['buildId', 'propertyCode', 'code'], 'unique', 'targetAttribute' => ['buildId', 'propertyCode', 'code']],
            [['buildId', 'propertyCode', 'code', 'name', 'sort'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'buildId' => GetMessage('intec.constructor.models.build.property.enum.attributes.labels.buildId'),
            'propertyCode' => GetMessage('intec.constructor.models.build.property.enum.attributes.labels.propertyCode'),
            'code' => GetMessage('intec.constructor.models.build.property.enum.attributes.labels.code'),
            'name' => GetMessage('intec.constructor.models.build.property.enum.attributes.labels.name'),
            'sort' => GetMessage('intec.constructor.models.build.property.enum.attributes.labels.sort')
        ];
    }

    /**
     * Реляция. Возвращает сборку к которой принадлежит свойство перечисления.
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
     * Реляция. Возвращает свойство данного значения перечисления.
     * @param bool $result
     * @return Property|ActiveQuery|null
     */
    public function getProperty($result = false)
    {
        return $this->relation(
            'property',
            $this->hasOne(Property::className(), [
                'buildId' => 'buildId',
                'code' => 'propertyCode'
            ]),
            $result
        );
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $result = $this->toArray();

        unset($result['buildId']);
        unset($result['propertyCode']);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data)
    {
        unset($data['buildId']);
        unset($data['propertyCode']);

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'buildId' || $attribute === 'propertyCode')
                continue;

            $this->setAttribute($attribute, null);
        }

        $this->load($data, '');

        if (!$this->save())
            return false;

        return true;
    }
}