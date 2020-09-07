<?php
namespace intec\constructor\models\build\property;
IncludeModuleLangFile(__FILE__);

use intec\constructor\models\build\Property;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\constructor\base\Exchangeable;
use intec\core\helpers\ArrayHelper;

/**
 * Class Value
 * @property int $buildId
 * @property string $propertyCode
 * @property string $value
 * @package intec\constructor\models\build\property
 */
abstract class Value extends ActiveRecord implements Exchangeable
{
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->value = $this->bring($this->value);

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
            'buildId' => ['buildId', 'integer'],
            'propertyCode' => ['propertyCode', 'string', 'max' => 128],
            'value' => ['value', 'string'],
            'unique' => [['buildId', 'propertyCode'], 'unique', 'targetAttribute' => ['buildId', 'propertyCode']],
            'required' => [['buildId', 'propertyCode'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'buildId' => GetMessage('intec.constructor.models.build.property.value.attributes.labels.buildId'),
            'propertyCode' => GetMessage('intec.constructor.models.build.property.value.attributes.labels.propertyCode'),
            'name' => GetMessage('intec.constructor.models.build.property.value.attributes.labels.name')
        ];
    }

    /**
     * Реляция. Возвращает привязанное свойство к значению.
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
     * Приводит значение к типу привязанного свойства.
     * @param mixed $value Значение, которое необходимо привести к типу привязанного свойства.
     * @return mixed Приведенное значение.
     */
    public function bring($value)
    {
        $property = $this->getProperty(true);

        if ($property)
            return $property->bring($value);

        return $value;
    }
}