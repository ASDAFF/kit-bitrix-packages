<?php
namespace intec\constructor\models\build\template;
IncludeModuleLangFile(__FILE__);

use intec\constructor\models\block\Template;
use intec\constructor\structure\Block as Model;
use intec\constructor\structure\block\Resources;
use intec\constructor\structure\BlockInterface as ModelInterface;
use intec\constructor\structure\BlockInterface;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

/**
 * Class Block
 * @property string $name
 * @property array $data
 * @package intec\constructor\models\build\template
 */
class Block extends Element implements ModelInterface
{
    const TYPE = 'block';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Создает пустой блок.
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
        return 'constructor_builds_templates_blocks';
    }

    /**
     * Возвращает префикс директории внутри шаблона.
     * @return string
     */
    public static function getResourcesDirectoryPrefix()
    {
        return 'images/blocks';
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $directory = $this->getResourcesDirectory();

            if ($directory !== null)
                FileHelper::removeDirectory($this->getResourcesDirectory()->value);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'properties' => [
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
        $rules = parent::rules();
        $rules['name'] = ['name', 'string', 'max' => 255];
        $rules['required'][0][] = 'name';

        return $rules;
    }

    /**
     * Возвращает директорию ресурсов блока.
     * @return Path
     */
    public function getResourcesDirectory()
    {
        if (empty($this->id))
            return null;

        $directory = null;

        if ($this->isRelationPopulated('template') || $this->templateId !== null)
            $directory = $this->getTemplate(true);

        if (empty($directory) && ($this->isRelationPopulated('area') || $this->areaId !== null))
            $directory = $this->getArea(true);

        if (empty($directory))
            return null;

        $directory = $directory->getBuild(true);

        if (empty($directory))
            return null;

        $directory = $directory->getDirectory();
        $directory = Path::from($directory)
            ->add(static::getResourcesDirectoryPrefix())
            ->add($this->id);

        return $directory;
    }

    /**
     * @inheritdoc
     */
    public function getResources()
    {
        if (empty($this->id))
            return null;

        return new Resources($this->getResourcesDirectory());
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getStructure()
    {
        $structure = parent::getStructure();
        $structure['name'] = $this->name;

        return $structure;
    }

    /**
     * @return Model|null
     */
    public function getModel()
    {
        if (empty($this->id))
            return null;

        $model = Model::from(
            $this->data,
            $this->getResources()
        );
        $model->class = 'constructor-block';
        $model->setId($this->id);

        return $model;
    }

    /**
     * Экспортирует блок в шаблон.
     * @param Template $template
     */
    public function exportTo($template)
    {
        if (empty($this->id))
            return false;

        if (!($template instanceof Template))
            return false;

        $template->name = $this->name;
        $template->data = $this->data;

        if (!$template->save())
            return false;

        $directoryFrom = $this->getResourcesDirectory();
        $directoryTo = $template->getResourcesDirectory();

        FileHelper::removeDirectory($directoryTo->value);

        if (FileHelper::isDirectory($directoryFrom->value))
            FileHelper::copyDirectory(
                $directoryFrom->value,
                $directoryTo->value
            );

        return true;
    }

    /**
     * Импортирует блок из шаблона или другого блока.
     * @param BlockInterface $object
     * @return boolean
     */
    public function importFrom($object)
    {
        if (!($object instanceof BlockInterface))
            return false;

        $resources = $object->getResources();
        $directoryFrom = $resources->getDirectory();
        $this->data = $object->getData();

        if ($object instanceof static) {
            $this->name = $object->name;
        } else if ($object instanceof Template) {
            $this->name = $object->name;
        }

        if (!$this->save())
            return false;

        $directoryTo = $this->getResourcesDirectory();

        FileHelper::removeDirectory($directoryTo->value);

        if (FileHelper::isDirectory($directoryFrom->value))
            FileHelper::copyDirectory(
                $directoryFrom->value,
                $directoryTo->value
            );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function import($data, &$indexes = null)
    {
        $result = parent::import($data, $indexes);

        if ($result) {
            $this->data = ArrayHelper::getValue($data, 'data');
            $this->save();
        }

        return $result;
    }

    /**
     * @param boolean $out
     * @param boolean $headers
     * @return string|null
     */
    public function render($out = false, $headers = false)
    {
        $model = $this->getModel();

        if (empty($model))
            return null;

        if ($headers)
            $model->includeHeaders();

        return $model->render($out);
    }
}