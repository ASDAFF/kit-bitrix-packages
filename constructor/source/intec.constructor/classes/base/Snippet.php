<?php
namespace intec\constructor\base;

use intec\constructor\base\snippet\Language;
use intec\constructor\base\snippet\Meta;
use intec\core\base\Component;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;

/**
 * Class Snippet
 * @property integer $id
 * @property string $code
 * @property string $namespace
 * @property string $directory
 * @property Language $language
 * @package intec\constructor\base
 */
abstract class Snippet extends Component
{
    /**
     * Код снипета.
     * @var string
     */
    protected $_code;
    /**
     * Директория снипета.
     * @var string
     */
    protected $_directory;
    /**
     * Объект языкозависимых строк снипета.
     * @var Language
     */
    protected $_language;

    /**
     * Возвращает наименование снипета.
     * @return string
     */
    public abstract function getName();

    /**
     * Возвращает код снипета.
     * @return string|null
     */
    public function getCode()
    {
        if ($this->_code === null)
            $this->_code = $this->getDirectory()->getName();

        return $this->_code;
    }

    /**
     * Возвращает директорию снипета.
     * @return Path
     */
    public function getDirectory($relative = false)
    {
        if ($this->_directory === null) {
            $this->_directory = (new \ReflectionClass($this))->getFileName();
            $this->_directory = Path::getDirectoryFrom($this->_directory);
        }

        $directory = Path::from($this->_directory);

        if ($relative)
            $directory = $directory->toRelative();

        return $directory;
    }

    /**
     * Возвращает объект языкозависимых строк снипета.
     * @return Language
     */
    public function getLanguage()
    {
        if ($this->_language === null)
            $this->_language = new Language($this);

        return $this->_language;
    }

    /**
     * Собирает путь до файла ресурса.
     * @param string $path Путь файла.
     * @param null $prefix Префикс файла.
     * @param null $extension Расширение файла.
     * @param boolean $relative Относительный путь.
     * @param string $separator Разделитель.
     * @return string
     */
    protected function getMetaPath($path, $prefix = null, $extension = null, $relative = false, $separator = DIRECTORY_SEPARATOR)
    {
        $path = $this->getDirectory($relative)
                ->asAbsolute()
                ->getValue($separator).$separator.$path;

        if (!empty($prefix))
            $path .= '.'.$prefix;

        if (!empty($extension))
            $path .= '.'.$extension;

        return $path;
    }

    /**
     * Подключает файл.
     * @param string $path Путь файла.
     * @param array $parameters Параметры.
     */
    protected function renderMetaContent($path, $parameters = [])
    {
        if (!Type::isArray($parameters))
            $parameters = [];

        $render = function () use ($path, $parameters) {
            extract($parameters, EXTR_SKIP);
            include($path);
        };

        if (FileHelper::isFile($path))
            $render();
    }

    /**
     * Достает контент из файла или поджключает его, и возвращает напечатанный контент.
     * @param string $path Путь файла.
     * @param boolean $include Подключать через include.
     * @param array $parameters Параметры.
     * @return string
     */
    protected function getMetaContent($path, $include = false, $parameters = [])
    {
        $content = null;

        if (!$include) {
            if (FileHelper::isFile($path))
                $content = FileHelper::getFileData($path);
        } else {
            ob_start();

            $this->renderMetaContent($path, $parameters);
            $content = ob_get_contents();

            ob_end_clean();
        }

        return $content;
    }

    /**
     * Возвращает результат отработки файла.
     * @param string $path Путь файла.
     * @param array $parameters Параметры.
     * @return string
     */
    protected function getMetaResult($path, $parameters = [])
    {
        $result = null;

        if (!Type::isArray($parameters))
            $parameters = [];

        $execute = function () use ($path, $parameters) {
            extract($parameters, EXTR_SKIP);
            return include($path);
        };

        if (FileHelper::isFile($path))
            $result = $execute();

        return $result;
    }
}