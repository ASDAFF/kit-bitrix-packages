<?php
namespace intec\core\base;

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * Представляет собой коллекцию каких-либо элементов.
 * Class Collection
 * @property string|null $key
 * @property array $keys
 * @property integer $index
 * @property mixed $current
 * @property mixed $first
 * @property mixed $last
 * @property mixed|boolean $next
 * @property mixed|boolean $previous
 * @property integer $count
 * @package core\base
 */
class Collection extends BaseObject implements \IteratorAggregate, \ArrayAccess, \Countable, \Serializable
{
    protected $items = [];

    public static function from($items) {
        return new static($items);
    }

    /**
     * Collection конструктор.
     *
     * @param array $items
     */
    public function __construct($items = []) {
        parent::__construct([]);
        static::setRange($items);
    }

    /**
     * Добавляет в коллекцию.
     *
     * @param mixed $item
     * @return $this
     */
    public function add($item) {
        if ($this->verify($item))
            $this->items[] = $item;

        return $this;
    }

    /**
     * Добавляет диапазон значений.
     *
     * @param array|Collection $items
     * @return $this
     */
    public function addRange($items) {
        if (Type::isArrayable($items)) {
            foreach ($items as $item)
                if ($this->verify($item))
                    $this->items[] = $item;
        }

        return $this;
    }

    /**
     * Заменяет или устанавливает в коллекции.
     *
     * @param string $key
     * @param mixed $item
     * @return $this
     */
    public function set($key, $item) {
        if ($this->verify($item))
            $this->items[$key] = $item;

        return $this;
    }

    /**
     * Устанавливает диапазон значений, сохраняя ключи.
     *
     * @var array|Collection $items
     * @return $this
     */
    public function setRange($items) {
        if (Type::isArrayable($items) || $items instanceof Collection) {
            foreach ($items as $key => $item)
                if ($this->verify($item))
                    $this->items[$key] = $item;
        }

        return $this;
    }

    /**
     * Получает значение из коллекии по ключу.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get($key) {
        if ($this->exists($key)) {
            return $this[$key];
        }

        return null;
    }

    /**
     * Получает диапазон значений по ключам.
     *
     * @param array $keys
     * @param bool $collection
     * @param bool $existing Добавлять в результат только существующие.
     * @return Collection|array
     */
    public function getRange($keys, $collection = true, $existing = true) {
        $items = [];

        if (Type::isArrayable($keys)) {
            foreach ($keys as $key) {
                $key = Type::toString($key);

                if ($this->exists($key)) {
                    $items[$key] = $this->get($key);
                } else if (!$existing) {
                    $items[$key] = null;
                }
            }
        }

        if ($collection)
            return static::from($items);

        return $items;
    }

    /**
     * Возвращает массив из коллекции. Ответ от function - [
     *  key => Ключ (если null то автоматически),
     *  value => Значение,
     *  skip => Пропуск значения
     * ]
     *
     * @param \Closure|null $function
     * @return array
     */
    public function asArray($function = null) {
        $result = [];

        if ($function instanceof \Closure) {
            foreach ($this->items as $key => $item) {
                $return = $function($key, $item);

                if (ArrayHelper::getValue($return, 'skip'))
                    continue;

                $key = ArrayHelper::getValue($return, 'key');
                $value = ArrayHelper::getValue($return, 'value');

                if ($key === null) {
                    $result[] = $value;
                } else {
                    $result[Type::toString($key)] = $value;
                }
            }
        } else {
            return $this->items;
        }

        return $result;
    }

    /**
     * Удаляет из коллекции.
     *
     * @param mixed $item
     * @return $this
     */
    public function remove($item) {
        foreach ($this->items as $key => $collectionItem) {
            if ($item === $collectionItem) {
                unset($this->items[$key]);
            }
        }

        return $this;
    }

    /**
     * Удаляет из коллекции по ключу.
     *
     * @param string $key
     * @return $this
     */
    public function removeAt($key) {
        $key = Type::toString($key);

        if (ArrayHelper::keyExists($key, $this->items)) {
            unset($this->items[$key]);
        }

        return $this;
    }

    /**
     * Удаляет все из коллекции.
     *
     * @return $this
     */
    public function removeAll() {
        $this->items = [];
        return $this;
    }

    /**
     * Проверяет добавляемое в коллекцию.
     *
     * @param $item
     * @return bool
     */
    protected function verify($item) {
        return true;
    }

    /**
     * Выполняет функцию для каждого элемента коллекции.
     *
     * @param $function
     * @return static
     */
    public function each($function) {
        if ($function instanceof \Closure)
            foreach ($this->items as $key => &$item)
                if ($function($key, $item) === false) break;

        return $this;
    }

    /**
     * Возвращает новую коллекцию по фильтру.
     *
     * @param \Closure $filter
     * @return static
     */
    public function where($filter) {
        $instance = new static();

        if ($filter instanceof \Closure)
            foreach ($this->items as $key => $item)
                if ($filter($key, $item))
                    $instance->add($item);

        return $instance;
    }

    /**
     * Проверяет существование по ключу.
     *
     * @param $key
     * @return bool
     */
    public function exists($key) {
        return $this->offsetExists($key);
    }

    /**
     * Проверяет существование элемента.
     *
     * @param mixed $item
     * @param bool $strict
     * @return bool
     */
    public function has($item, $strict = true) {
        return ArrayHelper::isIn($item, $this->items, $strict);
    }

    /**
     * Проверяет коллекцию на пустоту.
     *
     * @return bool
     */
    public function isEmpty() {
        return $this->count() == 0;
    }

    /**
     * Возвращает ключ текущего элемента.
     *
     * @return string|null
     */
    public function getKey() {
        return key($this->items);
    }

    /**
     * Получает ключи в массиве.
     *
     * @return array
     */
    public function getKeys() {
        return ArrayHelper::getKeys($this->items);
    }

    /**
     * Возвращает индекс текущего элемента.
     *
     * @return integer
     */
    public function getIndex() {
        $position = 0;
        $current = $this->getKey();
        $keys = $this->getKeys();

        foreach ($keys as $key) {
            if ($key === $current)
                break;

            $position++;
        }

        return $position;
    }

    /**
     * Возвращает текущий элемент.
     *
     * @return mixed
     */
    public function getCurrent() {
        return current($this->items);
    }

    /**
     * Возвращает первый элемент коллекции.
     *
     * @return mixed
     */
    public function getFirst() {
        $this->reset();
        return $this->getCurrent();
    }

    /**
     * Возвращает последний элемент коллекции.
     *
     * @return mixed
     */
    public function getLast() {
        return end($this->items);
    }

    /**
     * Сортирует элементы по алгоритму.
     * @param \Closure $callback
     * @return static
     */
    public function sort($callback) {
        if (!Type::isFunction($callback))
            return;

        usort($this->items, $callback);

        return $this;
    }

    /**
     * Возвращает следующий элемент коллекции.
     *
     * @return mixed|boolean
     */
    public function getNext() {
        return next($this->items);
    }

    /**
     * Возвращает предыдущий элемент коллекции.
     *
     * @return mixed|boolean
     */
    public function getPrevious() {
        return prev($this->items);
    }

    /**
     * Количество.
     *
     * @return integer
     */
    public function getCount() {
        return $this->count();
    }

    /**
     * Количество.
     *
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * Сбрасывает указатель на элемент.
     *
     * @return static
     */
    public function reset() {
        reset($this->items);
        return $this;
    }

    /**
     * Переиндексация элементов.
     *
     * @param null|\Closure
     * @return static
     */
    public function reindex($handler = null) {
        if ($handler instanceof \Closure) {
            $items = [];

            foreach ($this->items as $key => $item) {
                $result = $handler($key, $item);

                if (Type::isString($result) || Type::isNumber($result)) {
                    $items[$result] = $item;
                } else {
                    $items[] = $item;
                }
            }

            $this->items = $items;
        } else {
            $this->items = ArrayHelper::getValues($this->items);
        }

        $this->reset();
        return $this;
    }

    public function getIterator() {
        return new \ArrayIterator($this->items);
    }

    /**
     * Элемент существует. Служебная функция.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset) {
        $offset = Type::toString($offset);
        return ArrayHelper::keyExists($offset, $this->items);
    }

    /**
     * Возвращает элемент. Служебная функция.
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    /**
     * Устанавливает элемент. Служебная функция.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value) {
        if ($this->verify($value)) $this->items[$offset] = $value;
    }

    /**
     * Удаляет элемент. Служебная функция.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset)) unset($this->items[$offset]);
    }

    public function serialize() { return serialize($this->items); }
    public function unserialize($data) {
        $items = unserialize($data);

        if (Type::isArrayable($items)) {
            $this->items = [];

            foreach ($items as $key => $item)
                if ($this->verify($item))
                    $this->items[$key] = $item;
        }
    }
}