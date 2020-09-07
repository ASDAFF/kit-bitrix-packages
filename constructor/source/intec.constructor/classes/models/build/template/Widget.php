<?php
namespace intec\constructor\models\build\template;

use Bitrix\Main\Localization\Loc;
use intec\constructor\structure\widget\Template;
use intec\constructor\structure\Widget as Model;
use intec\constructor\structure\Widgets as Models;
use intec\core\helpers\ArrayHelper;

Loc::loadMessages(__FILE__);

/**
 * Class Widget
 * @property string $code
 * @property string $template
 * @property array $properties
 * @package intec\constructor\models\build\template
 */
class Widget extends Element
{
    const TYPE = 'widget';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустой виджет.
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
        return 'constructor_builds_templates_widgets';
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
     * @return Model|null
     */
    public function getModel()
    {
        $models = Models::all();
        return $models->get($this->code);
    }

    /**
     * @return Template|null
     */
    public function getModelTemplate()
    {
        /** @var Model $model */
        $model = $this->getModel();

        if (empty($model))
            return null;

        $template = $this->getTemplate(true);

        if (empty($template))
            return null;

        return $model->getTemplate(
            $this->template,
            $template->getBuild(true)
        );
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        $template = $this->getModelTemplate();

        if (empty($template))
            return null;

        $buildTemplate = $this->getTemplate(true);

        if (empty($buildTemplate))
            return null;

        return $template->getData(
            $this->properties,
            $buildTemplate->getBuild(true),
            $buildTemplate
        );
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
        $structure['data'] = $this->getData();

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
     * @param bool $out
     * @param bool $headers
     * @return string|null
     */
    public function render($out = false, $headers = false)
    {
        $model = $this->getModel();
        $template = $this->getModelTemplate();

        if (empty($template))
            return null;

        $buildTemplate = $this->getTemplate(true);

        if (empty($buildTemplate))
            return null;

        if ($headers) {
            $model->includeHeaders();
            $template->includeHeaders();
        }

        return $template->render(
            $this->properties,
            $buildTemplate->getBuild(true),
            $buildTemplate,
            true,
            $out
        );
    }
}