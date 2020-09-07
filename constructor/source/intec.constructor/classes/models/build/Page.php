<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\base\Component;
use intec\core\base\Collection;
use intec\core\base\InvalidParamException;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;

class Page extends Component
{
    /**
     * Сборка.
     * @var Build
     */
    protected $_build;
    /**
     * Путь до страницы.
     * @var string
     */
    protected $_path;
    /**
     * Сайт страницы.
     * @var string
     */
    protected $_site;
    /**
     * Коллекция свойств.
     * @var Collection
     */
    protected $_properties;

    /**
     * Исходный путь.
     */
    const PATH_RAW = 'raw';
    /**
     * Путь относительно корня сайта.
     */
    const PATH_RELATIVE = 'relative';
    /**
     * Абсолютный путь.
     */
    const PATH_ABSOLUTE = 'absolute';

    /**
     * Page constructor.
     * @param Build $build
     * @param string $path
     * @param string $site
     */
    public function __construct($build, $path = null, $site = null)
    {
        parent::__construct([]);

        if (!$build instanceof Build)
            throw new InvalidParamException('Invalid Build for '.self::className());

        $this->_build = $build;

        if ($site === null)
            $site = SITE_DIR;

        $site = trim($site, '/');

        $this->_path = $path;
        $this->_site = $site;
    }

    /**
     * Возвращает сборку.
     * @return Build
     */
    public function getBuild()
    {
        return $this->_build;
    }

    /**
     * @param string $relative
     * @param string $separator
     * @return string
     */
    public function getPath($relative = self::PATH_RELATIVE, $separator = DIRECTORY_SEPARATOR)
    {
        $path = null;

        if ($relative == self::PATH_RAW) {
            $path = $this->_path;
        } else if ($relative == self::PATH_ABSOLUTE) {
            $path = $this->_site.
                $separator.$this->_path;
        } else {
            $path = Core::getAlias('@root').
                $separator.$this->_site.
                $separator.$this->_path;
        }

        return FileHelper::normalizePath($path, $separator);
    }

    protected function getFiles($prefix = null)
    {
        if ($prefix === null)
            return [];

        $result = [];
        $directory = Core::getAlias('@root').DIRECTORY_SEPARATOR.$this->_site;
        $parts = $this->getPath(self::PATH_RAW);
        $parts = StringHelper::explode($parts, DIRECTORY_SEPARATOR);

        for ($level = 0; $level <= count($parts); $level++) {
            $last = $level == count($parts);

            if (!$last) {
                if ($level == 0) {
                    $path = $directory;
                } else {
                    $path = ArrayHelper::slice($parts, 0, $level);
                    $path = $directory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $path);
                }

                $file = $path.DIRECTORY_SEPARATOR.'.'.$prefix.'.php';
            } else {
                $path = ArrayHelper::slice($parts, 0, $level - 1);
                $path = $directory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $path);
                $file = FileHelper::getFileNameWithoutExtension($parts[$level - 1]).'.'.$prefix.'.php';
                $file = $path.DIRECTORY_SEPARATOR.$file;
            }

            if (FileHelper::isFile($file))
                $result[] = $file;
        }

        return $result;
    }

    /**
     * Возвращает коллекцию свойств.
     * @param bool $reset
     * @return Collection
     */
    public function getProperties($reset = false)
    {
        if ($this->_properties === null || $reset) {
            $this->_properties = new Collection();
            $files = $this->getFiles('properties');

            foreach ($files as $file)
                $this->_properties->setRange(include($file));
        }

        return $this->_properties;
    }

    /**
     * Выполняет файлы со скриптами.
     * @param mixed $data Любые данные для скрипта.
     */
    public function execute($data = null)
    {
        foreach ($this->getFiles('script') as $file)
            include($file);
    }
}