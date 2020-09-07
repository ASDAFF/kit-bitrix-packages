<?php
namespace intec\core\di;

use ReflectionClass;
use intec\Core;
use intec\core\base\Component;
use intec\core\base\InvalidConfigException;
use intec\core\base\NotInstantiableException;
use intec\core\helpers\ArrayHelper;

/**
 * Класс инкапсулятор для хранения объектов.
 * Class Container
 * @package intec\core\di
 * @since 1.0.0
 */
class Container extends Component
{
    /**
     * Одиночные объекты, проиндексированные по типу.
     * @var array
     * @since 1.0.0
     */
    private $_singletons = [];
    /**
     * Объявления объектов, проиндексированные по типу.
     * @var array
     * @since 1.0.0
     */
    private $_definitions = [];
    /**
     * Параметры конструктора, индексированные по типам объектов.
     * @var array
     * @since 1.0.0
     */
    private $_params = [];
    /**
     * Кешированные ReflectionClass объекты индексированные по
     * именам классов/интерфейсов.
     * @var array
     * @since 1.0.0
     */
    private $_reflections = [];
    /**
     * Кешированные зависимости ндексированные по именам классов/интерфейсов.
     * Каждое имя класса ассоциируется со списком пармаетров конструктора или значений по умолчанию.
     * @var array
     * @since 1.0.0
     */
    private $_dependencies = [];

    /**
     * Возвращает экземпляр запрашиваемого класса.
     * @param string $class Имя класса или алиас, зарегистрированный через [[set()]]
     * или [[setSingleton()]].
     * @param array $params Список параметровк конструктора.
     * @param array $config Список пар ключ-значение для инициализации свойств.
     * @return object Экземпляр класса.
     * @throws InvalidConfigException Если класс не может быть создан или неверное объявление.
     * @throws NotInstantiableException Если класс интерфейс или является абстрактным.
     * @since 1.0.0
     */
    public function get($class, $params = [], $config = [])
    {
        if (isset($this->_singletons[$class])) {
            // singleton
            return $this->_singletons[$class];
        } elseif (!isset($this->_definitions[$class])) {
            return $this->build($class, $params, $config);
        }

        $definition = $this->_definitions[$class];

        if (is_callable($definition, true)) {
            $params = $this->resolveDependencies($this->mergeParams($class, $params));
            $object = call_user_func($definition, $this, $params, $config);
        } elseif (is_array($definition)) {
            $concrete = $definition['class'];
            unset($definition['class']);

            $config = array_merge($definition, $config);
            $params = $this->mergeParams($class, $params);

            if ($concrete === $class) {
                $object = $this->build($class, $params, $config);
            } else {
                $object = $this->get($concrete, $params, $config);
            }
        } elseif (is_object($definition)) {
            return $this->_singletons[$class] = $definition;
        } else {
            throw new InvalidConfigException('Unexpected object definition type: ' . gettype($definition));
        }

        if (array_key_exists($class, $this->_singletons)) {
            $this->_singletons[$class] = $object;
        }

        return $object;
    }

    /**
     * Регистрирует класс в контейнере.
     * @param string $class Имя класса, интерфейса или алиас.
     * @param mixed $definition Объявление класса.
     * @param array $params Список параметров конструктора при инициализации,
     * когда вызывается [[get()]].
     * @return $this Контейнер.
     * @since 1.0.0
     */
    public function set($class, $definition = [], array $params = [])
    {
        $this->_definitions[$class] = $this->normalizeDefinition($class, $definition);
        $this->_params[$class] = $params;
        unset($this->_singletons[$class]);
        return $this;
    }

    /**
     * Регистрирует класс и помечает его как одиночный.
     * @param string $class Имя класса, интерфейса или алиас.
     * @param mixed $definition Объявление класса.
     * @param array $params Список параметров конструктора при инициализации,
     * когда вызывается [[get()]].
     * @return $this Контейнер.
     * @see set()
     * @since 1.0.0
     */
    public function setSingleton($class, $definition = [], array $params = [])
    {
        $this->_definitions[$class] = $this->normalizeDefinition($class, $definition);
        $this->_params[$class] = $params;
        $this->_singletons[$class] = null;
        return $this;
    }

    /**
     * Возвращает значение, которое отражает наличие объявления класса.
     * @param string $class Имя класса, интерфейса или алиас.
     * @return bool Контейнер имеет объявление класса.
     * @see set()
     * @since 1.0.0
     */
    public function has($class)
    {
        return isset($this->_definitions[$class]);
    }

    /**
     * Возвращает значение, которое отражает наличие объявления одиночного класса.
     * @param string $class Имя класса, интерфейса или алиас.
     * @param bool $checkInstance Проверять экземпляр.
     * @return bool Контейнер имеет объявление класса.
     * @see set()
     * @since 1.0.0
     */
    public function hasSingleton($class, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_singletons[$class]) : array_key_exists($class, $this->_singletons);
    }

    /**
     * Удаляет класс или одиночный класс из контейнера.
     * @param string $class Имя класса, интерфейс или алиас.
     * @since 1.0.0
     */
    public function clear($class)
    {
        unset($this->_definitions[$class], $this->_singletons[$class]);
    }

    /**
     * Производит нормализацию объявления класса.
     * @param string $class Имя класса.
     * @param string|array|callable $definition Объявление класса.
     * @return array Нормализованное объявление класса.
     * @throws InvalidConfigException Если объявление некорректно.
     * @since 1.0.0
     */
    protected function normalizeDefinition($class, $definition)
    {
        if (empty($definition)) {
            return ['class' => $class];
        } elseif (is_string($definition)) {
            return ['class' => $definition];
        } elseif (is_callable($definition, true) || is_object($definition)) {
            return $definition;
        } elseif (is_array($definition)) {
            if (!isset($definition['class'])) {
                if (strpos($class, '\\') !== false) {
                    $definition['class'] = $class;
                } else {
                    throw new InvalidConfigException("A class definition requires a \"class\" member.");
                }
            }
            return $definition;
        } else {
            throw new InvalidConfigException("Unsupported definition type for \"$class\": " . gettype($definition));
        }
    }

    /**
     * Возвращает список объявлений классов, проиндексированных по именам классов.
     * @return array Список объявлений классов.
     * @since 1.0.0
     */
    public function getDefinitions()
    {
        return $this->_definitions;
    }

    /**
     * Создает экземпляр определенного класса.
     * @param string $class Имя класса.
     * @param array $params Параметры конструктора.
     * @param array $config Конфигурация, которая будет применена.
     * @return object Новый экземпляр класса.
     * @throws NotInstantiableException Если класс интерфейс или абстрактный.
     * @since 1.0.0
     */
    protected function build($class, $params, $config)
    {
        /* @var $reflection ReflectionClass */
        list ($reflection, $dependencies) = $this->getDependencies($class);

        foreach ($params as $index => $param) {
            $dependencies[$index] = $param;
        }

        $dependencies = $this->resolveDependencies($dependencies, $reflection);
        if (!$reflection->isInstantiable()) {
            throw new NotInstantiableException($reflection->name);
        }
        if (empty($config)) {
            return $reflection->newInstanceArgs($dependencies);
        }

        if (!empty($dependencies) && $reflection->implementsInterface('intec\core\base\Configurable')) {
            // set $config as the last parameter (existing one will be overwritten)
            $dependencies[count($dependencies) - 1] = $config;
            return $reflection->newInstanceArgs($dependencies);
        } else {
            $object = $reflection->newInstanceArgs($dependencies);
            foreach ($config as $name => $value) {
                $object->$name = $value;
            }
            return $object;
        }
    }

    /**
     * Объеденяет пользовательские параметры с параметрами объявленного класса через [[set()]].
     * @param string $class Имя класса.
     * @param array $params Параметры.
     * @return array Все параметры класса.
     * @since 1.0.0
     */
    protected function mergeParams($class, $params)
    {
        if (empty($this->_params[$class])) {
            return $params;
        } elseif (empty($params)) {
            return $this->_params[$class];
        } else {
            $ps = $this->_params[$class];
            foreach ($params as $index => $value) {
                $ps[$index] = $value;
            }
            return $ps;
        }
    }

    /**
     * Возвращает зависимости класса.
     * @param string $class Имя класса, интерфейс или алиас.
     * @return array Зависимости класса.
     * @since 1.0.0
     */
    protected function getDependencies($class)
    {
        if (isset($this->_reflections[$class])) {
            return [$this->_reflections[$class], $this->_dependencies[$class]];
        }

        $dependencies = [];
        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $param) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    $c = $param->getClass();
                    $dependencies[] = Instance::of($c === null ? null : $c->getName());
                }
            }
        }

        $this->_reflections[$class] = $reflection;
        $this->_dependencies[$class] = $dependencies;

        return [$reflection, $dependencies];
    }

    /**
     * Разрешает зависимости.
     * @param array $dependencies Зависимости.
     * @param ReflectionClass $reflection Класс Reflection, ассоциированный с зависимостями.
     * @return array Разрешенные зависимости.
     * @throws InvalidConfigException Если зависимость не может быть разрешена.
     * @since 1.0.0
     */
    protected function resolveDependencies($dependencies, $reflection = null)
    {
        foreach ($dependencies as $index => $dependency) {
            if ($dependency instanceof Instance) {
                if ($dependency->id !== null) {
                    $dependencies[$index] = $this->get($dependency->id);
                } elseif ($reflection !== null) {
                    $name = $reflection->getConstructor()->getParameters()[$index]->getName();
                    $class = $reflection->getName();
                    throw new InvalidConfigException("Missing required parameter \"$name\" when instantiating \"$class\".");
                }
            }
        }
        return $dependencies;
    }

    /**
     * Вызывает функции обратного вызова с разрешением зависимостей.
     * @param callable $callback Функция обратного вызова.
     * @param array $params Параметры.
     * @return mixed Данные.
     * @since 1.0.0
     */
    public function invoke(callable $callback, $params = [])
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $this->resolveCallableDependencies($callback, $params));
        } else {
            return call_user_func_array($callback, $params);
        }
    }

    /**
     * Разрешает зависимости для функций обратного вызова.
     * @param callable $callback Функция обратного вызова.
     * @param array $params Параметры.
     * @return array Разрешенные зависимости.
     * @throws InvalidConfigException Зависимость не может быть разрешена.
     * @throws NotInstantiableException Если зависимость интерфейс или абстрактный класс.
     * @since 1.0.0
     */
    public function resolveCallableDependencies(callable $callback, $params = [])
    {
        if (is_array($callback)) {
            $reflection = new \ReflectionMethod($callback[0], $callback[1]);
        } else {
            $reflection = new \ReflectionFunction($callback);
        }

        $args = [];

        $associative = ArrayHelper::isAssociative($params);

        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            if (($class = $param->getClass()) !== null) {
                $className = $class->getName();
                if ($associative && isset($params[$name]) && $params[$name] instanceof $className) {
                    $args[] = $params[$name];
                    unset($params[$name]);
                } elseif (!$associative && isset($params[0]) && $params[0] instanceof $className) {
                    $args[] = array_shift($params);
                } elseif (isset(Core::$app) && Core::$app->has($name) && ($obj = Core::$app->get($name)) instanceof $className) {
                    $args[] = $obj;
                } else {
                    // If the argument is optional we catch not instantiable exceptions
                    try {
                        $args[] = $this->get($className);
                    } catch (NotInstantiableException $e) {
                        if ($param->isDefaultValueAvailable()) {
                            $args[] = $param->getDefaultValue();
                        } else {
                            throw $e;
                        }
                    }

                }
            } elseif ($associative && isset($params[$name])) {
                $args[] = $params[$name];
                unset($params[$name]);
            } elseif (!$associative && count($params)) {
                $args[] = array_shift($params);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } elseif (!$param->isOptional()) {
                $funcName = $reflection->getName();
                throw new InvalidConfigException("Missing required parameter \"$name\" when calling \"$funcName\".");
            }
        }

        foreach ($params as $value) {
            $args[] = $value;
        }
        return $args;
    }

    /**
     * Устанавливает объявления классов.
     * @param array $definitions Список объявлений классов.
     * @since 1.0.0
     */
    public function setDefinitions(array $definitions)
    {
        foreach ($definitions as $class => $definition) {
            if (count($definition) === 2 && array_values($definition) === $definition) {
                $this->set($class, $definition[0], $definition[1]);
                continue;
            }

            $this->set($class, $definition);
        }
    }

    /**
     * Устанавливает объявления одиночных классов.
     * @param array $singletons Список объявлений одиночных классов.
     * @since 1.0.0
     */
    public function setSingletons(array $singletons)
    {
        foreach ($singletons as $class => $definition) {
            if (count($definition) === 2 && array_values($definition) === $definition) {
                $this->setSingleton($class, $definition[0], $definition[1]);
                continue;
            }

            $this->setSingleton($class, $definition);
        }
    }
}
