<?php
namespace intec\core\handling;

use intec\core\base\Event;

/**
 * Class ActionEvent
 * @property Action|null $action
 * @package intec\core\handling
 */
class ActionEvent extends Event
{
    /**
     * @var Action|null
     */
    protected $_action;

    /**
     * ActionEvent constructor.
     * @param Action|null $action
     */
    public function __construct($action = null)
    {
        parent::__construct([]);

        $this->_action = $action instanceof Action ? $action : null;
    }

    /**
     * Возвращает действие для события.
     * @return Action|null
     */
    public function getAction()
    {
        return $this->_action;
    }
}