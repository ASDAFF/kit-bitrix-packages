<?php
namespace intec\core\net;

use intec\core\base\BaseObject;
use intec\core\collections\Scalars;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\helpers\StringHelper;

/**
 * Class Url
 * @package core\net
 */
class Url extends BaseObject
{
    /** @var string|null $_scheme */
    protected $_scheme = 'http';
    /** @var string|null $_host */
    protected $_host;
    /** @var integer|null $_port */
    protected $_port;
    /** @var string|null $_user */
    protected $_user;
    /** @var string|null $_password */
    protected $_password;
    /** @var Scalars $_path */
    protected $_path;
    /** @var Scalars $_query */
    protected $_query;
    /** @var string|null $_fragment */
    protected $_fragment;

    public function __construct($url = null) {
        parent::__construct();
        $this->_path = new Scalars();
        $this->_query = new Scalars();
        $this->parse($url);
    }

    /**
     * Устанавливает тип схемы (HTTP, HTTPS, ...).
     *
     * @param string|null $value
     * @return Url
     */
    public function setScheme($value = null) {
        $this->_scheme = $value !== null ? Type::toString($value) : null;
        return $this;
    }

    /**
     * Возвращает тип схемы.
     *
     * @return string|null
     */
    public function getScheme() {
        return $this->_scheme;
    }

    /**
     * Устанавливает хост.
     *
     * @param string|null $value
     * @return Url
     */
    public function setHost($value = null) {
        $this->_host = $value !== null ? Type::toString($value) : null;
        return $this;
    }

    /**
     * Возвращает хост.
     *
     * @return string|null
     */
    public function getHost() {
        return $this->_host;
    }

    /**
     * Устанавливает порт.
     *
     * @param integer|null $value
     * @return Url
     */
    public function setPort($value = null) {
        $this->_port = $value !== null ? Type::toInteger($value) : null;
        return $this;
    }

    /**
     * Возвращает порт.
     *
     * @return integer|null
     */
    public function getPort() {
        return $this->_port;
    }

    /**
     * Устанавливает пользователя.
     *
     * @param string|null $value
     * @return Url
     */
    public function setUser($value = null) {
        $this->_user = $value !== null ? Type::toString($value) : null;
        return $this;
    }

    /**
     * Возвращает пользователя.
     *
     * @return string|null
     */
    public function getUser() {
        return $this->_user;
    }

    /**
     * Устанавливает пароль.
     *
     * @param string|null $value
     * @return Url
     */
    public function setPassword($value = null) {
        $this->_password = $value !== null ? Type::toString($value) : null;
        return $this;
    }

    /**
     * Возвращает пароль.
     *
     * @return string|null
     */
    public function getPassword() {
        return $this->_password;
    }

    /**
     * Коллекция пути.
     *
     * @return Scalars
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * Устанавливает путь из строки.
     *
     * @param string|null $value
     * @return Url
     */
    public function setPathString($value = null) {
        $this->_path->removeAll();

        if (Type::isString($value)) {
            if (StringHelper::startsWith($value, '/'))
                $value = StringHelper::slice($value, 1);

            $value = explode('/', $value);

            foreach ($value as $item)
                $this->_path->add($item);
        }

        return $this;
    }

    /**
     * Возвращает путь как строку.
     *
     * @return string
     */
    public function getPathString() {
        return '/'.implode('/', $this->_path->asArray());
    }

    /**
     * Коллекция параметров.
     *
     * @return Scalars
     */
    public function getQuery() {
        return $this->_query;
    }

    /**
     * Устанавливает параметры запроса из query строки url запроса.
     *
     * @param string|null $value
     * @return Url
     */
    public function setQueryString($value = null) {
        $this->_query->removeAll();

        if (Type::isString($value)) {
            $this->_query->setRange(static::parseQueryString($value));
        }

        return $this;
    }

    /**
     * Устанавливает параметры запроса из query строки url запроса.
     *
     * @return string
     */
    public function getQueryString() {
        return static::buildQueryString($this->_query->asArray());
    }

    /**
     * Устанавливает фрагмент (после #).
     *
     * @param string|null $value
     * @return Url
     */
    public function setFragment($value = null) {
        $this->_fragment = $value !== null ? Type::toString($value) : null;
        return $this;
    }

    /**
     * Возвращает фрагмент (после #).
     *
     * @return string|null
     */
    public function getFragment() {
        return $this->_fragment;
    }

    /**
     * Выполняет парсинг адреса.
     *
     * @param string $url
     * @return bool
     */
    protected function parse($url) {
        if (!Type::isString($url)) return false;

        $url = parse_url($url);
        $part = ArrayHelper::getValue($url, 'scheme');

        if (!empty($part))
            $this->setScheme($part);

        $part = ArrayHelper::getValue($url, 'host');

        if (!empty($part))
            $this->setHost($part);

        $part = ArrayHelper::getValue($url, 'port');

        if (!empty($part))
            $this->setPort($part);

        $part = ArrayHelper::getValue($url, 'user');

        if (!empty($part))
            $this->setUser($part);

        $part = ArrayHelper::getValue($url, 'pass');

        if (!empty($part))
            $this->setPassword($part);

        $part = ArrayHelper::getValue($url, 'path');

        if (!empty($part))
            $this->setPathString($part);

        $part = ArrayHelper::getValue($url, 'query');

        if (!empty($part))
            $this->setQueryString($part);

        $part = ArrayHelper::getValue($url, 'fragment');

        if (!empty($part))
            $this->setFragment($part);

        return true;
    }

    /**
     * Выполняет построение адреса.
     *
     * @return null|string
     */
    public function build() {
        $url = null;

        if (!empty($this->_host)) {
            if (!empty($this->_scheme))
                $url .= $this->_scheme.':';

            $url .= '//';

            if (!empty($this->_user)) {
                $url .= static::encode($this->_user);

                if (!empty($this->_password))
                    $url .= ':'.static::encode($this->_password);

                $url .= '@';
            }

            $url .= static::encode($this->_host);

            if ($this->_port !== null)
                $url .= ':'.$this->_port;
        }

        if (!$this->_path->isEmpty()) {
            $url .= $this->getPathString();
        }

        if (!$this->_query->isEmpty() && !empty($url))
            $url .= '?'.$this->getQueryString();

        if (!empty($this->_fragment) && !empty($url))
            $url .= '#'.static::encode($this->_fragment);

        return $url;
    }

    /**
     * Возвращает строковое представление url адреса.
     *
     * @return string
     */
    public function __toString() {
        $url = $this->build();
        return !empty($url) ? $url : '';
    }

    /**
     * Собирает строку параметров запроса.
     *
     * @param array $parameters
     * @return array|null|string
     */
    public static function buildQueryString($parameters) {
        $query = [];

        foreach ($parameters as $key => $value) {
            if (Type::isBoolean($value))
                $value = $value ? 1 : 0;

            $query[] = static::encode($key).'='.static::encode($value);
        }

        if (!empty($query)) {
            $query = implode('&', $query);
        } else {
            $query = null;
        }

        return $query;
    }

    /**
     * Разбирает строку параметров запроса.
     *
     * @param string $query
     * @return array
     */
    public static function parseQueryString($query) {
        $parameters = [];
        $query = Type::toString($query);
        $query = explode('&', $query);

        foreach ($query as $parameter) {
            $parameter = explode('=', $parameter);
            $key = static::decode(ArrayHelper::getValue($parameter, 0));
            $value = static::decode(ArrayHelper::getValue($parameter, 1));
            $parameters[$key] = $value;
        }

        return $parameters;
    }

    /**
     * Кодирует строку url.
     *
     * @param string $string
     * @param bool $raw
     * @return string
     */
    public static function encode($string, $raw = false) {
        $string = Type::toString($string);

        if ($raw)
            return rawurlencode($string);

        return urlencode($string);
    }

    /**
     * Кодирует части строки url.
     *
     * @param string $string
     * @param string $delimiter
     * @param bool $raw
     * @return string
     */
    public static function encodeParts($string, $delimiter = '/', $raw = false) {
        $string = explode($delimiter, $string);

        foreach ($string as $key => $part) {
            $string[$key] = static::encode($part, $raw);
        }

        return implode($delimiter, $string);
    }

    /**
     * Декодирует строку url.
     *
     * @param string $string
     * @param bool $raw
     * @return string
     */
    public static function decode($string, $raw = false) {
        $string = Type::toString($string);

        if ($raw)
            return rawurldecode($string);

        return urldecode($string);
    }

    /**
     * Декодирует части строки url.
     *
     * @param string $string
     * @param string $delimiter
     * @param bool $raw
     * @return string
     */
    public static function decodeParts($string, $delimiter = '/', $raw = false) {
        $string = explode($delimiter, $string);

        foreach ($string as $key => $part) {
            $string[$key] = static::decode($part, $raw);
        }

        return implode($delimiter, $string);
    }
}