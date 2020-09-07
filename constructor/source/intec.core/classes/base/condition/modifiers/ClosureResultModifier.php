<?php
namespace intec\core\base\condition\modifiers;

use Closure;
use intec\core\base\InvalidParamException;
use intec\core\base\condition\ResultModifier;

/**
 * Класс, представляющий модификатор результата условий на основе функции.
 * Class ConditionResultModifier
 * @property mixed $context Контекст вызова. Только для чтения.
 * @property Closure $closure Функция-обработчик. Только для чтения.
 * @package intec\core\base\condition\modifiers
 * @author apocalypsisdimon@gmail.com
 */
class ClosureResultModifier extends ResultModifier
{
    /**
     * Контекст функции для обработки.
     * @var mixed
     */
    protected $_context;
    /**
     * Функция для обработки.
     * @var Closure
     */
    protected $_closure;

    /**
     * Возвращает контекст функции для обработки.
     * @return mixed
     */
    public function getContext()
    {
        return $this->_context;
    }

    /**
     * Возвращает функцию для обработки.
     * @return Closure
     */
    public function getClosure()
    {
        return $this->_closure;
    }

    /**
     * @inheritdoc
     * @param Closure $closure Функция для обработки.
     * @param mixed $context Контекст вызова.
     */
    public function __construct($closure, $context = null, $config = [])
    {
        if (!($closure instanceof Closure))
            throw new InvalidParamException('Closure is not instance of function');

        if ($context === false) {
            $context = null;
        } else if ($context === null || $context === true) {
            $context = $this;
        }

        $this->_context = $context;
        $this->_closure = $closure;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function modify($condition, $data, $result)
    {
        $context = $this->getContext();
        $closure = $this->_closure;

        if ($context === null)
            return $closure($condition, $data, $result);

        return $closure->call($context, $condition, $data, $result);
    }
}