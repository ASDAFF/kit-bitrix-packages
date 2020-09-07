<?php
namespace intec\constructor\models\build\template;
IncludeModuleLangFile(__FILE__);

use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\constructor\base\Exchangeable;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\core\helpers\Type;

/**
 * Class Widget
 * @property int $id
 * @property int $templateId
 * @property int $areaId
 * @property int $containerId
 * @package intec\constructor\models\build\template
 */
abstract class Element extends ActiveRecord implements Exchangeable
{
    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'templateId' => ['templateId', 'integer'],
            'areaId' => ['areaId', 'integer'],
            'containerId' => ['containerId', 'integer'],
            'required' => [['containerId'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => GetMessage('intec.constructor.models.template.element.attributes.labels.id'),
            'templateId' => GetMessage('intec.constructor.models.template.element.attributes.labels.templateId'),
            'containerId' => GetMessage('intec.constructor.models.template.element.attributes.labels.containerId')
        ];
    }

    /**
     * Реляция. Возвращает зону к которой принадлежит элемент.
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
     * Реляция. Возвращает шаблон к которому принадлежит элемент.
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
     * Реляция. Возвращает контейнер виджета.
     * @param bool $result
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
     * Возвращает структуру в виде массива.
     * @return array
     */
    public function getStructure()
    {
        $structure = [];
        $structure['id'] = $this->id;

        return $structure;
    }

    /**
     * Отрисовывает содержимое элемента и возвращает его в виде строки.
     * @return string
     */
    public abstract function render();

    /**
     * @inheritdoc
     */
    public function export(&$indexes = null)
    {
        $result = $this->toArray();

        unset($result['id']);
        unset($result['templateId']);
        unset($result['areaId']);
        unset($result['containerId']);

        if (Type::isArray($indexes)) {
            $indexes['id'] = $this->id;
        } else {
            $indexes = null;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function import($data, &$indexes = null)
    {
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
            if (Type::isArray($indexes)) {
                $indexes['id'] = $this->id;
            } else {
                $indexes = null;
            }
        }

        return $result;
    }
}