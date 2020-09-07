<?php
namespace intec\core\base;

/**
 * Класс, представляющий библиотеку.
 * Class Library
 * @property boolean $isLoaded
 * @package intec\core\base
 * @author apocalypsisdimon@gmail.com
 */
abstract class Library extends Component
{
    /**
     * Содержит экземпляр библиотеки.
     * @var static|null
     */
    protected static $_instance;

    /**
     * Возвращает экземпляр библиотеки.
     * @return static|null
     */
    public static function getInstance()
    {
        if (static::$_instance === null)
            static::$_instance = new static();

        return static::$_instance;
    }

    /**
     * Загружает библиотеку.
     * @return boolean
     */
    public abstract function load();

    /**
     * Возвращает значение, указывающее состояние библиотеки.
     * @return boolean
     */
    public abstract function getIsLoaded();
}