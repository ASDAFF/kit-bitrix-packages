<?php
namespace intec\constructor\models;

use Bitrix\Main\Localization\Loc;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;

Loc::loadMessages(__FILE__);

/**
 * Class Block
 * @property integer $id
 * @property string $name
 * @property array $data
 * @package intec\constructor\models
 */
class Block extends ActiveRecord
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
        return 'constructor_blocks';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'data' => [
                'class' => 'intec\core\behaviors\FieldArray',
                'attribute' => 'data'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Loc::getMessage('intec.constructor.models.block.attributes.labels.id'),
            'name' => Loc::getMessage('intec.constructor.models.block.attributes.labels.name'),
            'structure' => Loc::getMessage('intec.constructor.models.block.attributes.labels.structure')
        ];
    }
}