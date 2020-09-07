<?php
namespace intec\constructor\structure\widget;

use Bitrix\Main\Localization\Loc;
use intec\constructor\base\Renderable;
use intec\constructor\base\Snippet;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template as BuildTemplate;
use intec\constructor\structure\SnippetHandleTrait;
use intec\constructor\structure\SnippetMetaTrait;
use intec\constructor\structure\Widget;
use intec\core\base\InvalidParamException;
use intec\core\helpers\ArrayHelper;
use intec\core\io\Path;

Loc::loadMessages(__FILE__);

/**
 * Class Template
 * @package intec\constructor\structure\widget
 */
class Template extends Snippet implements Renderable
{
    use SnippetMetaTrait {
        getSettings as protected getTraitSettings;
    }
    use SnippetHandleTrait {
        getData as protected getTraitData;
    }

    /**
     * Виджет шаблона.
     * @var Widget
     */
    protected $_widget;
    /**
     * Сборка шаблона или null, если шаблон публичный.
     * @var Build|null
     */
    protected $_build;

    /**
     * Template constructor.
     * @param Widget $widget
     * @param string $directory
     * @param Build|null $build
     */
    public function __construct($widget, $directory, $build = null)
    {
        if (!($widget instanceof Widget))
            throw new InvalidParamException('Widget is not a "'.Widget::className().'" instance');

        if (!($build instanceof Build))
            $build = null;

        $this->_widget = $widget;
        $this->_directory = $directory;
        $this->_build = $build;

        parent::__construct([]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return '';
    }

    /**
     * Возвращает виджет шаблона.
     * @return Widget
     */
    public function getWidget()
    {
        return $this->_widget;
    }

    /**
     * Возвращает сборку шаблона.
     * @return Build|null
     */
    public function getBuild()
    {
        return $this->_build;
    }

    /**
     * @inheritdoc
     */
    public function getSettings()
    {
        $settings = $this->getWidget()->getSettings();
        $settings .= $this->getTraitSettings();

        return $settings;
    }

    /**
     * @inheritdoc
     */
    public function getData($properties, $build, $template)
    {
        $data = $this->getWidget()->getData(
            $properties,
            $build,
            $template
        );

        $data = ArrayHelper::merge($data, $this->getTraitData(
            $properties,
            $build,
            $template
        ));

        return $data;
    }

    /**
     * Возвращает путь до файла данных.
     * @return string
     */
    public function getDataPath()
    {
        return $this->getMetaPath('data.php');
    }

    /**
     * @inheritdoc
     */
    public function data($properties, $build, $template)
    {
        return $this->getMetaResult($this->getDataPath(), [
            'properties' => $properties,
            'build' => $build,
            'template' => $template
        ]);
    }

    /**
     * Возвращает путь до файла обработки.
     * @return string
     */
    public function getHandlerPath()
    {
        return $this->getMetaPath('handler.php');
    }

    /**
     * @inheritdoc
     */
    protected function handle($parameters, $properties, $build, $template)
    {
        return $this->getMetaResult($this->getHandlerPath(), [
            'parameters' => $parameters,
            'properties' => $properties,
            'build' => $build,
            'template' => $template
        ]);
    }

    /**
     * Возвращает директорию View файлов.
     * @return Path
     */
    public function getViewsDirectory()
    {
        return $this->getDirectory()->add('views');
    }

    /**
     * @param array $properties
     * @param Build $build
     * @param BuildTemplate $template
     * @param boolean $static
     * @param boolean $out
     * @return string|null
     */
    public function render($properties = [], $build = null, $template = null, $static = true, $out = false)
    {
        if (!$template instanceof BuildTemplate)
            return null;

        if (!$build instanceof Build)
            $build = $template->getBuild(true);

        if (!$build instanceof Build)
            return null;

        $data = $this->getData($properties, $build, $template);
        $path = $static ? 'static' : 'dynamic';
        $path = $this->getViewsDirectory()->add($path.'.php');
        $parameters = [
            'properties' => $properties,
            'build' => $build,
            'template' => $template,
            'data' => $data
        ];

        if ($out) {
            $this->renderMetaContent($path, $parameters);
        } else {
            return $this->getMetaContent($path, true, $parameters);
        }

        return null;
    }
}