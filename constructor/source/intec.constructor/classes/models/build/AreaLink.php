<?php
namespace intec\constructor\models\build;

use Bitrix\Main\Localization\Loc;
use intec\core\db\ActiveQuery;
use intec\core\db\ActiveRecord;
use intec\constructor\models\build\template\Container;

Loc::loadMessages(__FILE__);

/**
 * Class AreaLink
 * @property integer $areaId
 * @property integer $containerId
 * @package intec\constructor\models\build
 */
class AreaLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_areas_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['areaId', 'containerId'], 'integer'],
            [['areaId', 'containerId'], 'required']
        ];
    }

    /**
     * Реляция. Возвращает привязанную зону.
     * @param boolean $result Возвращать результат.
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