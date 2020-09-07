<?php
namespace intec\core\di;

use intec\Core;
use intec\core\base\InvalidConfigException;

/**
 * Класс, содержащий информацию для инициализации какого-либо объекта.
 * Class Instance
 * @package intec\core\di
 * @since 1.0.0
 */
class Instance
{
    /**
     * Идентификатор компонента, наименование класса, интерфейса или алиаса.
     * @var string
     * @since 1.0.0
     */
    public $id;


    /**
     * Конструктор.
     * @param string $id Идентификатор.
     * @since 1.0.0
     */
    protected function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Создает новый экземпляр данного класса.
     * @param string $id Идентификатор.
     * @return Instance Новый экземпляр данного класса.
     * @since 1.0.0
     */
    public static function of($id)
    {
        return new static($id);
    }

    /**
     * Разрешает указанную ссылку на фактический объект и гарантирует, что она имеет указанный тип.
     * @param object|string|array|static $reference Объект или ссылка на желаемый объект.
     * @param string $type Наименование класса или интерфейса, с которым будет сверяться тип объекта. Если `null`, проверки типа не будет.
     * @param ServiceLocator|Container $container Контейнер, будет вызван [[get()]].
     * @return object Объект.
     * @throws InvalidConfigException Если сслыка на объект неверна.
     * @since 1.0.0
     */
    public static function ensure($reference, $type = null, $container = null)
    {
        if (is_array($reference)) {
            $class = isset($reference['class']) ? $reference['class'] : $type;
            if (!$container instanceof Container) {
                $container = Core::$container;
            }
            unset($reference['class']);
            return $container->get($class, [], $reference);
        } elseif (empty($reference)) {
            throw new InvalidConfigException('The required component is not specified.');
        }

        if (is_string($reference)) {
            $reference = new static($reference);
        } elseif ($type === null || $reference instanceof $type) {
            return $reference;
        }

        if ($reference instanceof self) {
            try {
                $component = $reference->get($container);
            } catch(\ReflectionException $e) {
                throw new InvalidConfigException('Failed to instantiate component or class "' . $reference->id . '".', 0, $e);
            }
            if ($type === null || $component instanceof $type) {
                return $component;
            } else {
                throw new InvalidConfigException('"' . $reference->id . '" refers to a ' . get_class($component) . " component. $type is expected.");
            }
        }

        $valueType = is_object($reference) ? get_class($reference) : gettype($reference);
        throw new InvalidConfigException("Invalid data type: $valueType. $type is expected.");
    }

    /**
     * Возвращает актуальный объект для данного экземпляра.
     * @param ServiceLocator|Container $container Контейнер.
     * @return object Актуальный объект данного экземпляра.
     * @since 1.0.0
     */
    public function get($container = null)
    {
        if ($container) {
            return $container->get($this->id);
        }
        if (Core::$app && Core::$app->has($this->id)) {
            return Core::$app->get($this->id);
        } else {
            return Core::$container->get($this->id);
        }
    }
}
