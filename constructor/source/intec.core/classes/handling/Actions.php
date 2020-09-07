<?php
namespace intec\core\handling;

use intec\core\base\Event;
use intec\core\base\EventsTrait;
use intec\core\base\BaseObject;
use intec\core\helpers\Type;

/**
 * Class Actions
 * @property Action|null $action
 * @package intec\core\handling
 */
class Actions extends BaseObject
{
    use EventsTrait;

    /**
     * @var Action|null
     */
    protected $_action;
    /**
     * @var mixed
     */
    public $data;

    /**
     * Создает базовое событие.
     * @param Action|null $action
     * @param mixed $data
     * @return Event
     */
    protected function getEvent($action = null, $data = null)
    {
        $event = new ActionEvent($action);
        $event->sender = $this;
        $event->data = $data;

        return $event;
    }

    /**
     * Срабатывает до действия.
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        return $this->trigger('beforeAction', $this->getEvent($action));
    }

    /**
     * Срабатывает после действия.
     * @param Action $action
     */
    public function afterAction($action)
    {
        $this->trigger('afterAction', $this->getEvent($action));
    }

    /**
     * Срабатывает, когда нет действия.
     * @param Action|null $action
     * @return bool
     */
    public function noAction($action)
    {
        return $this->trigger('noAction', $this->getEvent($action));
    }

    /**
     * Возвращает текущее действие.
     * @return Action|null
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Запускает действие.
     * @param Action|string $action
     * @return mixed|null
     */
    public function run($action = null)
    {
        $result = null;

        if (!($action instanceof Action)) {
            $action = Type::toString($action);

            if (!empty($action))
                $action = new Action($action);
        }

        if (empty($action)) {
            $result = $this->noAction(null);
            return $result;
        }

        $method = $action->getMethodName();
        $class = new \ReflectionClass($this);

        if ($class->hasMethod($method)) {
            $method = $class->getMethod($method);

            if ($this->beforeAction($action)) {
                $result = $method->invoke($this);
                $this->afterAction($action);
            }
        } else {
            $result = $this->noAction($action);
        }

        return $result;
    }
}