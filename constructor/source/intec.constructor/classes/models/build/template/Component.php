<?php
namespace intec\constructor\models\build\template;
IncludeModuleLangFile(__FILE__);

use intec\core\db\ActiveRecord;
use intec\core\db\ActiveQuery;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Json;

/**
 * Class Component
 * @property string $code
 * @property string $template
 * @property array $properties
 * @package intec\constructor\models\build\template
 */
class Component extends Element
{
    const TYPE = 'component';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустой компонент.
     * @return static
     */
    public static function create()
    {
        $instance = new static();
        $instance->loadDefaultValues();
        $instance->templateId = null;
        $instance->areaId = null;
        $instance->containerId = null;

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'constructor_builds_templates_components';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'properties' => [
                'class' => 'intec\core\behaviors\FieldArray',
                'attribute' => 'properties'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['code'] = ['code', 'string', 'max' => 255];
        $rules['template'] = ['template', 'string', 'max' => 255];
        $rules['required'][0][] = 'code';

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getStructure()
    {
        $structure = parent::getStructure();
        $structure['code'] = $this->code;
        $structure['template'] = $this->template;
        $structure['properties'] = $this->properties;

        return $structure;
    }

    /**
     * @inheritdoc
     */
    public function import($data, &$indexes = null)
    {
        $result = parent::import($data, $indexes);

        if ($result) {
            $this->properties = ArrayHelper::getValue($data, 'properties');
            $this->save();
        }

        return $result;
    }

    /**
     * Отрисовывает виджет.
     * @param bool $out
     * @return null|string
     */
    public function render($out = false)
    {
        /** @var \CMain $APPLICATION */
        global $APPLICATION;

        $result = null;

        if (!$out)
            ob_start();

        $APPLICATION->IncludeComponent(
            $this->code,
            $this->template,
            $this->properties,
            false,
            array(
                'HIDE_ICON' => 'Y'
            ));

        if (!$out) {
            $result = ob_get_contents();
            ob_end_clean();
        }

        return $result;
    }
}