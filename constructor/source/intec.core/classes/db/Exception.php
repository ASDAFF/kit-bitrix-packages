<?php
namespace intec\core\db;

/**
 * Class Exception
 * @package intec\core\db
 * @since 1.0.0
 */
class Exception extends \intec\core\base\Exception
{
    /**
     * @var array
     * @since 1.0.0
     */
    public $errorInfo = [];


    /**
     * Constructor.
     * @param string $message
     * @param array $errorInfo
     * @param int $code
     * @param \Exception $previous
     * @since 1.0.0
     */
    public function __construct($message, $errorInfo = [], $code = 0, \Exception $previous = null)
    {
        $this->errorInfo = $errorInfo;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        return 'Database Exception';
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        return parent::__toString() . PHP_EOL
        . 'Additional Information:' . PHP_EOL . print_r($this->errorInfo, true);
    }
}
