<?php
namespace intec\core\base\condition\providers;

use Closure;
use intec\core\base\InvalidParamException;
use intec\core\base\condition\DataProvider;

/**
 * Класс, представляющий провайдер данных на основе функций.
 * Class ConditionClosureDataProvider
 * @property mixed $context Контекст вызова. Только для чтения.
 * @property Closure $closure Функция для обработки. Только для чтения.
 * @package intec\core\base\condition\providers
 * @author apocalypsisdimon@gmail.com
 */
class ClosureDataProvider extends DataProvider
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
    public function receive($condition)
    {
        $context = $this->getContext();
        $closure = $this->_closure;

        if ($context === null)
            return $closure($condition);

        return $closure->call($context, $condition);
    }
}