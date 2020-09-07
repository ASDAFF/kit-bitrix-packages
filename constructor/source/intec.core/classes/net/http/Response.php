<?php
namespace intec\core\net\http;

use intec\core\base\BaseObject;
use intec\core\collections\Scalars;
use intec\core\helpers\Type;

/**
 * Класс, реализующий ответ сервера.
 *
 * Class Response
 * @property Scalars $headers
 * @property Scalars $cookies
 * @property integer|null $code
 * @property string|null $version
 * @property string|null $message
 * @property string|null $content
 * @package core\net
 */
class Response extends BaseObject
{
    /** @var Scalars $_headers */
    protected $_headers;
    /** @var Scalars $_cookies */
    protected $_cookies;
    /** @var integer|null $_code */
    protected $_code;
    /** @var string|null $_version */
    protected $_version;
    /** @var string|null $_message */
    protected $_message;
    /** @var string|null $_content */
    protected $_content;

    /**
     * Response constructor.
     * @param integer|null $code
     * @param string|null $version
     * @param string|null $message
     * @param string|null $content
     */
    public function __construct($code = null, $version = null, $message = null, $content = null) {
        $this->_headers = new Scalars();
        $this->_cookies = new Scalars();
        $this->_code = ($code >= 100 && $code < 600) ? $code : null;
        $this->_version = Type::isString($version) ? $version : null;
        $this->_message = Type::isString($message) ? $message : null;
        $this->_content = !empty($content) ? Type::toString($content) : null;

        parent::__construct([]);
    }

    /**
     * Возвращает заголовки.
     *
     * @return Scalars
     */
    public function getHeaders() {
        return $this->_headers;
    }

    /**
     * Возвращает куки.
     *
     * @return Scalars
     */
    public function getCookies() {
        return $this->_cookies;
    }

    /**
     * Возвращает код.
     *
     * @return int|null
     */
    public function getCode() {
        return $this->_code;
    }

    /**
     * Возвращает версию протокола.
     *
     * @return int|null
     */
    public function getVersion() {
        return $this->_version;
    }

    /**
     * Возвращает сообщение сервера.
     *
     * @return int|null
     */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * Возвращает контент.
     *
     * @return string|null
     */
    public function getContent() {
        return $this->_content;
    }
}