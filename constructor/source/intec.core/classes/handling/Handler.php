<?php
namespace intec\core\handling;

use intec\core\base\Exception;
use intec\core\base\BaseObject;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * Class Handler
 * @property string $directory
 * @property string $namespace
 * @property string $separator
 * @property string $extension
 * @property string $prefix
 * @package intec\core\handling
 */
class Handler extends BaseObject
{
    /**
     * @var string
     */
    protected $_directory;
    /**
     * @var string
     */
    protected $_namespace;
    /**
     * @var string
     */
    protected $_separator = '.';
    /**
     * @var string
     */
    protected $_extension = 'php';
    /**
     * @var string
     */
    protected $_prefix = 'Actions';

    /**
     * Handler constructor.
     * @param string $directory
     * @param string $namespace
     */
    public function __construct($directory, $namespace)
    {
        parent::__construct([]);

        $this->_directory = FileHelper::normalizePath($directory);
        $this->_namespace = Type::toString($namespace);
    }

    /**
     * Возвращает директорию, из которой происходит подключение.
     * @return string
     */
    public function getDirectory()
    {
        return $this->_directory;
    }

    /**
     * Возвращает пространство имен, из которго происходит подключение.
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Устанавливает разделитель для действия.
     * @param string $separator
     * @return $this
     */
    public function setSeparator($separator)
    {
        $this->_separator = $separator;
        return $this;
    }

    /**
     * Возвращает разделитель для действия.
     * @return string
     */
    public function getSeparator()
    {
        return $this->_separator;
    }

    /**
     * Устанавливает расширение подключаемого файла.
     * @param string $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->_extension = $extension;
        return $this;
    }

    /**
     * Возвращает расширение подключаемого файла.
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }

    /**
     * Устанавливает префикс подключаемого файла.
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }

    /**
     * Возвращает префикс подключаемого файла.
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * Возвращает действие.
     * @param string $action
     * @return string
     */
    public function getAction($action)
    {
        $action = Type::toString($action);

        if (empty($action))
            return null;

        $action = explode($this->_separator, $action);
        $actionLength = count($action);

        if ($actionLength > 1) {
            return end($action);
        } else {
            return $action[0];
        }
    }

    /**
     * Возвращает расположение относительно данного обработчика
     * в виде массива с частями пути.
     * @param string $action
     * @return array|null
     */
    public function getPathParts($action)
    {
        $action = Type::toString($action);

        if (empty($action))
            return null;

        $action = explode($this->_separator, $action);
        $actionLength = count($action);

        if ($actionLength > 1) {
            $pathParts = ArrayHelper::slice($action, 0, $actionLength - 1);
            $pathLength = count($pathParts);
            $pathParts[$pathLength - 1] = StringHelper::toUpperCharacter($pathParts[$pathLength - 1]);
        } else {
            $pathParts = [StringHelper::toUpperCharacter($action[0])];
        }

        return $pathParts;
    }

    /**
     * Возвращает класс с действиями по его имени и пути.
     * @param array $pathParts
     * @return Actions|null
     * @throws Exception
     */
    public function getActions($pathParts)
    {
        if (!Type::isArrayable($pathParts))
            return null;

        if (empty($pathParts))
            return null;

        $name = $this->_namespace.'\\'.implode('\\', $pathParts).$this->_prefix;
        $path = $this->_directory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $pathParts).
            $this->_prefix.'.'.$this->_extension;

        $getClass = function ($name) {
            try {
                return new \ReflectionClass($name);
            } catch (\Exception $exception) {
                return null;
            }
        };

        $class = $getClass($name);

        if ($class === null)
            include($path);

        /** @var \ReflectionClass $class */
        $class = $getClass($name);

        if ($class === null)
            throw new Exception('Cannot find actions class "'.$name.'"');

        if (!$class->isSubclassOf(Actions::className()))
            throw new Exception('Class "'.$class->getName().'" is not "'.Actions::className().'"');

        /** @var Actions $actions */
        $actions = $class->newInstance();

        return $actions;
    }

    /**
     * Обрабатывает действие.
     * @param $action
     * @param mixed $data
     * @return mixed|null
     * @throws Exception
     */
    public function handle($action, $data = null)
    {
        $action = Type::toString($action);

        if (empty($action))
            return null;

        $pathParts = $this->getPathParts($action);
        $action = $this->getAction($action);
        $actions = $this->getActions($pathParts);
        $actions->data = $data;

        return $actions->run($this->getAction($action));
    }
}