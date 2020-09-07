<?php
namespace intec\constructor\models\build\template;

use Bitrix\Main\Localization\Loc;
use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;

Loc::loadMessages(__FILE__);

/**
 * Class ContainerLink
 * @property integer $containerId
 * @property integer $parentId
 * @property string $parentType
 * @package intec\constructor\models\build\template
 */
class ContainerLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_templates_containers_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['containerId', 'parentId'], 'integer'],
            [['parentType'], 'string'],
            [['containerId', 'parentId', 'parentType'], 'required']
        ];
    }

    /**
     * Реляция. Возвращает привязанный контейнер.
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
}