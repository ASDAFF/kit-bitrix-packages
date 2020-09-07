<?php
namespace intec\core\base;

/**
 * Возникает при невозвможности создать экземпляр класса.
 * Class NotInstantiableException
 * @package intec\core\base
 * @since 1.0.0
 */
class NotInstantiableException extends InvalidConfigException
{
    /**
     * NotInstantiableException конструктор.
     * @param string $class Класс.
     * @param null $message Сообщение.
     * @param int $code Код.
     * @param \Exception|null $previous Предыдущее исключение.
     * @since 1.0.0
     */
    public function __construct($class, $message = null, $code = 0, \Exception $previous = null)
    {
        if ($message === null) {
            $message = "Can not instantiate $class.";
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * Возвращает наименование исключения.
     * @return string
     */
    public function getName()
    {
        return 'Not instantiable';
    }
}
