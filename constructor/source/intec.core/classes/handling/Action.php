<?php
namespace intec\core\handling;

use intec\core\base\Exception;
use intec\core\base\BaseObject;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * Class Action
 * @property string $id
 * @package intec\core\handling
 */
class Action extends BaseObject
{
    protected $_id;

    /**
     * Action constructor.
     * @param string $id
     * @throws Exception
     */
    public function __construct($id)
    {
        parent::__construct([]);

        $this->_id = Type::toString($id);

        if (empty($this->_id))
            throw new Exception('Undefined action id');
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getMethodName()
    {
        return 'action'.StringHelper::toUpperCharacter($this->_id);
    }
}