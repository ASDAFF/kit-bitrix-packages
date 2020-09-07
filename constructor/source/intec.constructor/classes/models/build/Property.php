<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\constructor\models\build\property\Enum;
use intec\core\db\ActiveQuery;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\Build;
use intec\constructor\models\build\template\Value as TemplateValue;
use intec\constructor\models\build\theme\Value as ThemeValue;

/**
 * Class Property
 * @property int $buildId
 * @property string $code
 * @property string $name
 * @property int $sort
 * @property string $type
 * @property string $default
 * @package intec\constructor\models\build
 */
class Property extends ActiveRecord implements Exchangeable
{
    /** @var array $cache */
    protected static $cache = [];

    /** Тип: Логическое значение */
    const TYPE_BOOLEAN = 'boolean';
    /** Тип: Строка */
    const TYPE_STRING = 'string';
    /** Тип: Целое число */
    const TYPE_INTEGER = 'integer';
    /** Тип: Дробное число */
    const TYPE_FLOAT = 'float';
    /** Тип: Список */
    const TYPE_ENUM = 'enum';
    /** Тип: Цвет */
    const TYPE_COLOR = 'color';

    /**
     * Возвращает список доступных типов.
     * @return array
     */
    public static function getTypes()
    {
        return [
            static::TYPE_BOOLEAN => GetMessage('intec.constructor.models.build.property.type.boolean'),
            static::TYPE_STRING => GetMessage('intec.constructor.models.build.property.type.string'),
            static::TYPE_INTEGER => GetMessage('intec.constructor.models.build.property.type.integer'),
            static::TYPE_FLOAT => GetMessage('intec.constructor.models.build.property.type.float'),
            static::TYPE_ENUM => GetMessage('intec.constructor.models.build.property.type.enum'),
            static::TYPE_COLOR => GetMessage('intec.constructor.models.build.property.type.color')
        ];
    }

    /**
     * Возвращает список доступных значений типов.
     * @return array
     */
    public static function getTypesValues()
    {
        $types = static::getTypes();
        $types = ArrayHelper::getKeys($types);
        return $types;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_properties';
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $values = $this->getTemplatesValues(true);

        foreach ($values as $value)
            $value->delete();

        $values = $this->getThemesValues(true);

        foreach ($values as $value)
            $value->delete();

        $enums = $this->getEnums(true);

        foreach ($enums as $enum)
            $enum->delete();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->default = $this->bring($this->default);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $typesValues = static::getTypesValues();

        return [
            [['buildId', 'sort'], 'integer'],
            [['code'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 255],
            [['type', 'default'], 'string'],
            [['sort'], 'default', 'value' => 500],
            [['type'], 'in', 'range' => $typesValues],
            [['code'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/'],
            [['buildId', 'code'], 'unique', 'targetAttribute' => ['code', 'buildId']],
            [['buildId', 'code', 'name', 'type', 'sort'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'buildId' => GetMessage('intec.constructor.models.build.property.attributes.labels.buildId'),
            'code' => GetMessage('intec.constructor.models.build.property.attributes.labels.code'),
            'name' => GetMessage('intec.constructor.models.build.property.attributes.labels.name'),
            'sort' => GetMessage('intec.constructor.models.build.property.attributes.labels.sort'),
            'type' => GetMessage('intec.constructor.models.build.property.attributes.labels.type'),
            'default' => GetMessage('intec.constructor.models.build.property.attributes.labels.default')
        ];
    }

    /**
     * Приводит значение к типу данного свойства.
     * @param mixed $value Значение, которое необходимо привести к данному типу.
     * @return mixed Приведенное значение.
     */
    public function bring($value)
    {
        switch ($this->type) {
            case static::TYPE_BOOLEAN: { return Type::toBoolean($value) ? 1 : 0; }
            case static::TYPE_STRING: { return Type::toString($value); }
            case static::TYPE_INTEGER: { return Type::toInteger($value); }
            case static::TYPE_FLOAT: { return Type::toFloat($value); }
            case static::TYPE_ENUM: {
                /** @var ActiveRecords $enums */
                $enums = $this->getEnums(true);
                $enums->indexBy('code');

                if (!$enums->exists($value))
                    $value = null;

                return $value;
            }
            case static::TYPE_COLOR: {
                if (RegExp::isMatchBy('/#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})/', $value)) {
                    return $value;
                } else {
                    return '#fff';
                }
            }
            default: { return $value; }
        }
    }

    /**
     * Возвращает название типа данного свойства.
     * @return string|null
     */
    public function getType()
    {
        return ArrayHelper::getValue(self::getTypes(), $this->type);
    }

    /**
     * Реляция. Возвращает сборку к которой принадлежит свойство.
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
     * Реляция. Возвращает значения перечисления данного свойства.
     * @param bool $result
     * @param bool $collection
     * @return Enum[]|ActiveQuery|null
     */
    public function getEnums($result = false, $collection = true)
    {
        return $this->relation(
            'enums',
            $this->hasMany(Enum::className(), [
                'buildId' => 'buildId',
                'propertyCode' => 'code'
            ])->orderBy('sort'),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает значения свойства для всех шаблонов.
     * @param bool $result
     * @param bool $collection
     * @return TemplateValue[]|ActiveQuery|null
     */
    public function getTemplatesValues($result = false, $collection = true)
    {
        return $this->relation(
            'templatesValues',
            $this->hasMany(TemplateValue::className(), [
                'buildId' => 'buildId',
                'propertyCode' => 'code'
            ]),
            $result,
            $collection
        );
    }

    /**
     * Реляция. Возвращает значения свойства для всех тем.
     * @param bool $result
     * @param bool $collection
     * @return ThemeValue[]|ActiveQuery|null
     */
    public function getThemesValues($result = false, $collection = true)
    {
        return $this->relation(
            'themesValues',
            $this->hasMany(ThemeValue::className(), [
                'buildId' => 'buildId',
                'propertyCode' => 'code'
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

        unset($result['buildId']);

        if ($this->type === self::TYPE_ENUM) {
            $result['enums'] = [];
            $enums = $this->getEnums(true);

            foreach ($enums as $enum)
                $result['enums'][] = $enum->export();
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data)
    {
        $dataEnums = ArrayHelper::getValue($data, 'enums');

        unset($data['buildId']);
        unset($data['enums']);

        foreach ($this->attributes() as $attribute) {
            if ($attribute === 'buildId')
                continue;

            $this->setAttribute($attribute, null);
        }

        $this->load($data, '');

        if (!$this->save())
            return false;

        $clean = [];

        if ($this->type === self::TYPE_ENUM)
            $clean['enums'] = $this->getEnums(true);

        foreach ($clean as $items) {
            foreach ($items as $item) {
                /** @var ActiveRecord $item */
                $item->delete();
            }
        }

        unset($clean);

        if ($this->type === self::TYPE_ENUM) {
            $enums = [];

            if (Type::isArray($dataEnums))
                foreach ($dataEnums as $dataEnum) {
                    $enum = new Enum();
                    $enum->buildId = $this->buildId;
                    $enum->propertyCode = $this->code;

                    if ($enum->import($dataEnum))
                        $enums[] = $enum;
                }

            $this->populateRelation('enums', $enums);
            $this->default = ArrayHelper::getValue($data, 'default');
            $this->save();
        }

        return true;
    }
}