<?php
namespace intec\constructor\structure;

use intec\Core;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Type;

/**
 * Trait SnippetMetaTrait
 * @package intec\constructor\structure
 */
trait SnippetMetaTrait
{
    /**
     * Возвращает путь до стилей.
     * @param string|null $prefix
     * @param boolean $relative
     * @param string $separator
     * @return string
     */
    public function getStylePath($prefix = null, $relative = false, $separator = DIRECTORY_SEPARATOR)
    {
        return $this->getMetaPath('style', $prefix, 'css', $relative, $separator);
    }

    /**
     * Проверяет на существование файла со стилями.
     * @param string|null $prefix
     * @return boolean
     */
    public function hasStyle($prefix = null)
    {
        return FileHelper::isFile($this->getStylePath($prefix));
    }

    /**
     * Возвращает путь до файла скрипта.
     * @param string|null $prefix
     * @param boolean $relative
     * @param string $separator
     * @return string
     */
    public function getScriptPath($prefix = null, $relative = false, $separator = DIRECTORY_SEPARATOR)
    {
        return $this->getMetaPath('script', $prefix, 'js', $relative, $separator);
    }

    /**
     * Проверяет на существование файла со скриптами.
     * @param string|null $prefix
     * @return boolean
     */
    public function hasScript($prefix = null)
    {
        return FileHelper::isFile($this->getScriptPath($prefix));
    }

    /**
     * Возвращает путь до файла заголовков.
     * @param string|null $prefix
     * @param boolean $relative
     * @param string $separator
     * @return string
     */
    public function getHeadersPath($prefix = null, $relative = false, $separator = DIRECTORY_SEPARATOR)
    {
        return $this->getMetaPath('headers', $prefix, 'php', $relative, $separator);
    }

    /**
     * Проверяет на существование файла заголовков.
     * @param string|null $prefix
     * @return boolean
     */
    public function hasHeaders($prefix = null)
    {
        return FileHelper::isFile($this->getHeadersPath($prefix));
    }

    /**
     * Подключает заголовки.
     * @param array $prefixes
     */
    public function includeHeaders($prefixes = [])
    {
        if (!Type::isArray($prefixes))
            $prefixes = [];

        if ($this->hasStyle())
            Core::$app->web->css->addFile('/'.$this->getStylePath(null, true));

        if ($this->hasScript())
            Core::$app->web->js->addFile('/'.$this->getScriptPath(null, true));

        $include = function ($prefix = null) {
            include($this->getHeadersPath($prefix));
        };

        if ($this->hasHeaders())
            $include();

        foreach ($prefixes as $prefix) {
            if ($this->hasStyle($prefix))
                Core::$app->web->css->addFile('/'.$this->getStylePath($prefix, true));

            if ($this->hasScript($prefix))
                Core::$app->web->css->addFile('/'.$this->getScriptPath($prefix, true));

            if ($this->hasHeaders($prefix))
                $include($prefix);
        }
    }

    /**
     * Возвращает путь до файла настроек.
     * @param string|null $prefix
     * @return string
     */
    public function getSettingsPath()
    {
        return $this->getMetaPath('settings', null, 'php');
    }

    /**
     * Проверяет на существование файла c настройками.
     * @param string|null $prefix
     * @return bool
     */
    public function hasSettings()
    {
        return FileHelper::isFile($this->getSettingsPath());
    }

    /**
     * Возвращает содержимое настроек.
     * @param string|null $prefix
     * @return string
     */
    public function getSettings()
    {
        return $this->getMetaContent($this->getSettingsPath(), true);
    }

    /**
     * Возвращает путь до файла модели.
     * @param string|null $prefix
     * @return string
     */
    public function getModelPath()
    {
        return $this->getMetaPath('model', null, 'js');
    }

    /**
     * Проверяет на существование файла c моделью.
     * @param string|null $prefix
     * @return boolean
     */
    public function hasModel()
    {
        return FileHelper::isFile($this->getModelPath());
    }

    /**
     * Возвращает содержимое модели.
     * @return string|null
     */
    public function getModel()
    {
        return $this->getMetaContent($this->getModelPath());
    }
}