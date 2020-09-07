<?php
namespace intec\core\base;

use intec\Core;

/**
 * ErrorException расширяющее PHP ErrorException.
 * Class ErrorException
 * @package intec\core\base
 * @since 1.0.0
 */
class ErrorException extends \ErrorException
{
    const E_HHVM_FATAL_ERROR = 16777217; // E_ERROR | (1 << 24)

    /**
     * Конструктор исключения.
     * @param string $message [optional]
     * @param integer $code [optional]
     * @param integer $severity [optional]
     * @param string $filename [optional]
     * @param integer $lineno [optional]
     * @param \Exception $previous [optional]
     * @since 1.0.0
     */
    public function __construct($message = '', $code = 0, $severity = 1, $filename = __FILE__, $lineno = __LINE__, \Exception $previous = null)
    {
        parent::__construct($message, $code, $severity, $filename, $lineno, $previous);

        if (function_exists('xdebug_get_function_stack')) {
            $xDebugTrace = array_slice(array_reverse(xdebug_get_function_stack()), 3, -1);
            $trace = [];
            foreach ($xDebugTrace as $frame) {
                if (!isset($frame['function'])) {
                    $frame['function'] = 'unknown';
                }

                if (!isset($frame['type']) || $frame['type'] === 'static') {
                    $frame['type'] = '::';
                } elseif ($frame['type'] === 'dynamic') {
                    $frame['type'] = '->';
                }

                if (isset($frame['params']) && !isset($frame['args'])) {
                    $frame['args'] = $frame['params'];
                }
                $trace[] = $frame;
            }

            $ref = new \ReflectionProperty('Exception', 'trace');
            $ref->setAccessible(true);
            $ref->setValue($this, $trace);
        }
    }

    /**
     * Возвращает `true`, если ошибка является одной из фатальной ошибок.
     * @param array $error Ошибка полученная из error_get_last()
     * @return bool Если ошибка является фатальной.
     * @since 1.0.0
     */
    public static function isFatalError($error)
    {
        return isset($error['type']) && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, self::E_HHVM_FATAL_ERROR]);
    }

    /**
     * Возвращает наименование исключения.
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        static $names = [
            E_COMPILE_ERROR => 'PHP Compile Error',
            E_COMPILE_WARNING => 'PHP Compile Warning',
            E_CORE_ERROR => 'PHP Core Error',
            E_CORE_WARNING => 'PHP Core Warning',
            E_DEPRECATED => 'PHP Deprecated Warning',
            E_ERROR => 'PHP Fatal Error',
            E_NOTICE => 'PHP Notice',
            E_PARSE => 'PHP Parse Error',
            E_RECOVERABLE_ERROR => 'PHP Recoverable Error',
            E_STRICT => 'PHP Strict Warning',
            E_USER_DEPRECATED => 'PHP User Deprecated Warning',
            E_USER_ERROR => 'PHP User Error',
            E_USER_NOTICE => 'PHP User Notice',
            E_USER_WARNING => 'PHP User Warning',
            E_WARNING => 'PHP Warning',
            self::E_HHVM_FATAL_ERROR => 'HHVM Fatal Error',
        ];

        return isset($names[$this->getCode()]) ? $names[$this->getCode()] : 'Error';
    }
}
