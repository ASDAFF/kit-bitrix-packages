<?php
namespace intec\core\base;

use intec\Core;
use intec\core\di\ServiceLocator;

/**
 * Модуль приложения.
 * @property array $aliases Список алиасов, которые должны быть проинициализированы. Только для записи.
 * @property string $basePath Базовая директория модуля.
 * @property array $modules Список дочерних модулей проиндексированных по идентификатору.
 * @property string $uniqueId Уникальный идентификатор модуля. Только для чтения.
 * @property string $version Версия модуля.
 * @since 1.0.0
 */
class Module extends ServiceLocator
{
    /**
     * @var array Параметры модуля.
     * @since 1.0.0
     */
    public $params = [];

    /**
     * @var string Идентификатор модуля [[module|parent]].
     * @since 1.0.0
     */
    public $id;

    /**
     * @var Module Родительский модуль. Если `null` то модуль корневой.
     * @since 1.0.0
     */
    public $module;

    /**
     * @var string Корневая директория модуля.
     * @since 1.0.0
     */
    private $_basePath;

    /**
     * @var array Дочерние модули.
     * @since 1.0.0
     */
    private $_modules = [];

    /**
     * @var string|callable Версия текущего модуля.
     * @since 1.0.0
     */
    private $_version;


    /**
     * Конструктор.
     * @param string $id Идентификатор модуля.
     * @param Module $parent Родительский модуль.
     * @param array $config Настройки для инициализации модуля.
     * @since 1.0.0
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->id = $id;
        $this->module = $parent;
        parent::__construct($config);
    }

    /**
     * Возвращает экземпляр модуля из приложения.
     * Если модуль не инициализирован или его не существует, будет возвращено `null`.
     * @return static|null Экземпляр текущего модуля или `null`.
     * @since 1.0.0
     */
    public static function getInstance()
    {
        $class = get_called_class();
        return isset(Core::$app->loadedModules[$class]) ? Core::$app->loadedModules[$class] : null;
    }

    /**
     * Устанавливает новый экземпляр данного класса.
     * @param Module|null $instance Экземпляр данного модуля или `null`.
     * Если `null`, то экземпляр будет удален из списка загруженных модулей.
     * @since 1.0.0
     */
    public static function setInstance($instance)
    {
        if ($instance === null) {
            unset(Core::$app->loadedModules[get_called_class()]);
        } else {
            Core::$app->loadedModules[get_class($instance)] = $instance;
        }
    }

    /**
     * Возвращает уникальный идентификатор модуля.
     * @return string Уникальный идентификатор модуля.
     * @since 1.0.0
     */
    public function getUniqueId()
    {
        return $this->module ? ltrim($this->module->getUniqueId() . '/' . $this->id, '/') : $this->id;
    }

    /**
     * Возвращает корневую директорию модуля.
     * @return string Корневая директория модуля.
     * @since 1.0.0
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass($this);
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
    }

    /**
     * Устанавливает корневую директорию модуля.
     * @param string $path Путь до корневой директории.
     * @throws InvalidParamException Если директория не существует.
     * @since 1.0.0
     */
    public function setBasePath($path)
    {
        $path = Core::getAlias($path);

        if (is_dir($path)) {
            $this->_basePath = $path;
        } else {
            throw new InvalidParamException("The directory does not exist: $path");
        }
    }

    /**
     * Возвращает версию модуля.
     * @return string Версия модуля.
     * @since 1.0.0
     */
    public function getVersion()
    {
        if ($this->_version === null) {
            $this->_version = $this->defaultVersion();
        } else {
            if (!is_scalar($this->_version)) {
                $this->_version = call_user_func($this->_version, $this);
            }
        }
        return $this->_version;
    }

    /**
     * Устанавливает текущую версию модуля.
     * @param string|callable $version Версия модуля.
     * @since 1.0.0
     */
    public function setVersion($version)
    {
        $this->_version = $version;
    }

    /**
     * Версия модуля по умолчанию.
     * @return string Версия модуля.
     * @since 1.0.0
     */
    protected function defaultVersion()
    {
        if ($this->module === null) {
            return '1.0';
        }
        return $this->module->getVersion();
    }

    /**
     * Определяет алиасы.
     * Метод вызывает [[Core::setAlias()]] для регистрации алиасов.
     * @param array $aliases Список алиасов.
     * @since 1.0.0
     */
    public function setAliases($aliases)
    {
        foreach ($aliases as $name => $alias) {
            Core::setAlias($name, $alias);
        }
    }

    /**
     * Проверяет, существует ли модуль в текущем модуле.
     * @param string $id Идентификатор модуля.
     * @return bool Модуль существует.
     * @since 1.0.0
     */
    public function hasModule($id)
    {
        if (($pos = strpos($id, '/')) !== false) {
            // sub-module
            $module = $this->getModule(substr($id, 0, $pos));

            return $module === null ? false : $module->hasModule(substr($id, $pos + 1));
        }
        return isset($this->_modules[$id]);
    }

    /**
     * Возвращает модуль по идентификатору.
     * @param string $id Идентификатор модуля.
     * @param bool $load Загрузить модуль, если он еще не загружен.
     * @return Module|null Экземпляр модуля, если `null`, то модуля несуществует.
     * @see hasModule()
     * @since 1.0.0
     */
    public function getModule($id, $load = true)
    {
        if (($pos = strpos($id, '/')) !== false) {
            // sub-module
            $module = $this->getModule(substr($id, 0, $pos));

            return $module === null ? null : $module->getModule(substr($id, $pos + 1), $load);
        }

        if (isset($this->_modules[$id])) {
            if ($this->_modules[$id] instanceof Module) {
                return $this->_modules[$id];
            } elseif ($load) {
                /* @var $module Module */
                $module = Core::createObject($this->_modules[$id], [$id, $this]);
                $module->setInstance($module);
                return $this->_modules[$id] = $module;
            }
        }

        return null;
    }

    /**
     * Добавляет модуль к текущему модулю.
     * @param string $id Идентификатор модуля.
     * @param Module|array|null $module Модуль, объявление модуля или `null`.
     * @since 1.0.0
     */
    public function setModule($id, $module)
    {
        if ($module === null) {
            unset($this->_modules[$id]);
        } else {
            $this->_modules[$id] = $module;
        }
    }

    /**
     * Возвращает модули текущего модуля.
     * @param bool $loadedOnly Возвращать только загруженные.
     * @return array Модули проиндексированные по идентификатору.
     * @since 1.0.0
     */
    public function getModules($loadedOnly = false)
    {
        if ($loadedOnly) {
            $modules = [];
            foreach ($this->_modules as $module) {
                if ($module instanceof Module) {
                    $modules[] = $module;
                }
            }

            return $modules;
        }
        return $this->_modules;
    }

    /**
     * Добавляет несколько модулеуй.
     * @param array $modules Модули.
     * Вид: [Идентификатор => Модуль, объявление модуля].
     * @since 1.0.0
     */
    public function setModules($modules)
    {
        foreach ($modules as $id => $module) {
            $this->_modules[$id] = $module;
        }
    }
}
