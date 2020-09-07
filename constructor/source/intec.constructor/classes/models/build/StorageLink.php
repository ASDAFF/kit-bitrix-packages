<?php
namespace intec\constructor\models\build;

use Bitrix\Main\Localization\Loc;
use intec\constructor\models\build\template\Block;
use intec\constructor\models\build\template\Component;
use intec\constructor\models\build\template\Variator;
use intec\constructor\models\build\template\variator\Variant;
use intec\constructor\models\build\template\Widget;
use intec\core\db\ActiveQuery;
use intec\core\db\ActiveRecord;
use intec\constructor\models\build\template\Container;

Loc::loadMessages(__FILE__);

/**
 * Class StorageLink
 * @property integer $storageId
 * @property string $storageType
 * @property integer $elementId
 * @property string $elementType
 * @package intec\constructor\models\build
 * @future Измененная система привязки элементов к их хранилищам
 */
class StorageLink extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_storages_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['storageId', 'elementId'], 'integer'],
            [['storageType', 'elementType'], 'integer'],
            [['elementId', 'elementType'], 'unique', 'targetAttribute' => ['elementId', 'elementType']],
            [['storageId', 'storageType', 'elementId', 'elementType'], 'required']
        ];
    }

    /**
     * Реляция. Возвращает привязанный блок.
     * @param boolean $result Возвращать результат.
     * @return Block|ActiveQuery|null
     */
    public function getBlock($result = false)
    {
        return $this->relation(
            'block',
            $this->hasOne(Block::className(), ['id' => 'elementId']),
            $result
        );
    }

    /**
     * Содержит ли блок.
     * @return boolean
     */
    public function hasBlock()
    {
        $result = false;

        if ($this->elementType == Block::TYPE && !empty($this->elementId)) {
            $block = $this->getBlock(true);
            $result = !empty($block);
        }

        return $result;
    }

    /**
     * Реляция. Возвращает привязанный компонент.
     * @param boolean $result Возвращать результат.
     * @return Component|ActiveQuery|null
     */
    public function getComponent($result = false)
    {
        return $this->relation(
            'component',
            $this->hasOne(Component::className(), ['id' => 'elementId']),
            $result
        );
    }

    /**
     * Содержит ли компонент.
     * @return boolean
     */
    public function hasComponent()
    {
        $result = false;

        if ($this->elementType == Component::TYPE && !empty($this->elementId)) {
            $component = $this->getComponent(true);
            $result = !empty($component);
        }

        return $result;
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
            $this->hasOne(Container::className(), ['id' => 'elementId']),
            $result
        );
    }

    /**
     * Содержит ли контейнер.
     * @return boolean
     */
    public function hasContainer()
    {
        $result = false;

        if ($this->elementType == Container::TYPE && !empty($this->elementId)) {
            $container = $this->getContainer(true);
            $result = !empty($container);
        }

        return $result;
    }

    /**
     * Реляция. Возвращает привязанный вариатор.
     * @param boolean $result Возвращать результат.
     * @return Variator|ActiveQuery|null
     */
    public function getVariator($result = false)
    {
        return $this->relation(
            'variator',
            $this->hasOne(Variator::className(), ['id' => 'elementId']),
            $result
        );
    }

    /**
     * Содержит ли вариатор.
     * @return boolean
     */
    public function hasVariator()
    {
        $result = false;

        if ($this->elementType == Variator::TYPE && !empty($this->elementId)) {
            $variator = $this->getVariator(true);
            $result = !empty($variator);
        }

        return $result;
    }

    /**
     * Реляция. Возвращает привязанный вариант.
     * @param boolean $result Возвращать результат.
     * @return Variant|ActiveQuery|null
     */
    public function getVariant($result = false)
    {
        return $this->relation(
            'variant',
            $this->hasOne(Variant::className(), ['id' => 'elementId']),
            $result
        );
    }

    /**
     * Содержит ли вариант.
     * @return boolean
     */
    public function hasVariant()
    {
        $result = false;

        if ($this->elementType == Variant::TYPE && !empty($this->elementId)) {
            $variant = $this->getVariant(true);
            $result = !empty($variant);
        }

        return $result;
    }

    /**
     * Реляция. Возвращает привязанный виджет.
     * @param boolean $result Возвращать результат.
     * @return Widget|ActiveQuery|null
     */
    public function getWidget($result = false)
    {
        return $this->relation(
            'widget',
            $this->hasOne(Widget::className(), ['id' => 'elementId']),
            $result
        );
    }

    /**
     * Содержит ли виджет.
     * @return boolean
     */
    public function hasWidget()
    {
        $result = false;

        if ($this->elementType == Widget::TYPE && !empty($this->elementId)) {
            $widget = $this->getWidget(true);
            $result = !empty($widget);
        }

        return $result;
    }

    /**
     * Реляция. Возвращает зону хранения.
     * @param boolean $result Возвращать результат.
     * @return Area|ActiveQuery|null
     */
    public function getStorageArea($result = false)
    {
        return $this->relation(
            'storageArea',
            $this->hasOne(Area::className(), ['id' => 'storageId']),
            $result
        );
    }

    /**
     * Является ли хранилище зоной.
     * @return boolean
     */
    public function isStorageArea()
    {
        $result = false;

        if ($this->storageType == Area::TYPE && !empty($this->storageId)) {
            $area = $this->getStorageArea(true);
            $result = !empty($area);
        }

        return $result;
    }

    /**
     * Реляция. Возвращает зону хранения.
     * @param boolean $result Возвращать результат.
     * @return Area|ActiveQuery|null
     */
    public function getStorageTemplate($result = false)
    {
        return $this->relation(
            'storageArea',
            $this->hasOne(Area::className(), ['id' => 'storageId']),
            $result
        );
    }

    /**
     * Является ли хранилище шаблоном.
     * @return boolean
     */
    public function isStorageTemplate()
    {
        $result = false;

        if ($this->storageType == Template::TYPE && !empty($this->storageId)) {
            $template = $this->getStorageTemplate(true);
            $result = !empty($template);
        }

        return $result;
    }
}