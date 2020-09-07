<?php
namespace intec\core\net\http;

use intec\core\base\BaseObject;
use intec\core\helpers\Type;

/**
 * Класс, реализующий прокси интерфейс.
 *
 * Class Proxy
 * @package core\net
 */
class Proxy extends BaseObject
{
    /** @var string|null $_address */
    protected $_address;
    /** @var integer|null $_port */
    protected $_port;
    /** @var string|null $_login */
    protected $_login;
    /** @var string|null $_password */
    protected $_password;

    /**
     * Proxy constructor.
     * @param string|null $address
     * @param integer|null $port
     * @param string|null $login
     * @param string|null $password
     */
    public function __construct($address = null, $port = null, $login = null, $password = null) {
        $this->setAddress($address);
        $this->setPort($port);
        $this->authorize($login, $password);

        parent::__construct([]);
    }

    /**
     * Устанавливает адрес.
     *
     * @param string|null $value
     * @return Proxy
     */
    public function setAddress($value) {
        $this->_address = null;

        if (Type::isString($value)) {
            $this->_address = $value;
        }

        return $this;
    }

    /**
     * Возвращает адрес.
     *
     * @return string
     */
    public function getAddress() {
        return $this->_address;
    }

    /**
     * Устанавливает порт.
     *
     * @param integer|null $value
     * @return Proxy
     */
    public function setPort($value) {
        $this->_port = null;

        if (Type::isNumeric($value) && $value >= 0 && $value <= 65535) {
            $this->_port = Type::toInteger($value);
        }

        return $this;
    }

    /**
     * Возвращает порт.
     *
     * @return integer
     */
    public function getPort() {
        return $this->_port;
    }

    /**
     * Возвращает полную строку подключения.
     *
     * @return string
     */
    public function getFullAddress() {
        return $this->_address.':'.$this->_port;
    }

    /**
     * Возвращает логин.
     *
     * @return string|null
     */
    public function getLogin() {
        return $this->_login;
    }

    /**
     * Возвращает пароль.
     *
     * @return null|string
     */
    public function getPassword() {
        return $this->_password;
    }

    /**
     * Задает параметры авторизации.
     *
     * @param string|null $login
     * @param string|null $password
     * @return $this
     */
    public function authorize($login = null, $password = null) {
        $login = Type::toString($login);
        $password = Type::toString($password);
        $this->_login = null;
        $this->_password = null;

        if (!empty($login) && !empty($password)) {
            $this->_login = $login;
            $this->_password = $password;
        }

        return $this;
    }

    /**
     * Можно-ли авторизация.
     *
     * @return bool
     */
    public function canAuthorize() {
        return !empty($this->_login) && !empty($this->_password);
    }

    /**
     * Можно-ли подключиться.
     *
     * @return bool
     */
    public function canConnect() {
        return !empty($this->_address) && $this->_port !== null;
    }
}