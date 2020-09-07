<?php
namespace intec\core\bitrix\web;

use intec\core\base\BaseObject;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;

/**
 * Расширение JavScript.
 * Class JavaScriptExtension
 * @property string $id
 * @property string $script
 * @property string $style
 * @property string[] $dependencies
 * @package intec\core\bitrix\web
 */
class JavaScriptExtension extends BaseObject
{
    /**
     * Импортирует из расширения CJSCore
     * @param string $id Идентификатор.
     * @param array $array Массив из CJSCore.
     * @return static
     */
    public static function fromCJSExtension($id, $array)
    {
        return new static([
            'id' => $id,
            'script' => ArrayHelper::getValue($array, 'js'),
            'style' => ArrayHelper::getValue($array, 'css'),
            'dependencies' => ArrayHelper::getValue($array, 'rel')
        ]);
    }

    /**
     * Идентификатор расширения.
     * @var string
     */
    protected $_id;
    /**
     * Путь до скрипта.
     * @var string
     */
    protected $_script;
    /**
     * Путь до стиля.
     * @var string
     */
    protected $_style;
    /**
     * Зависимости расширения.
     * @var array
     */
    protected $_dependencies = [];

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Возвращает идентификатор расширения.
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Устанавливает идентификатор расширения.
     * @param $value
     * @return $this
     */
    public function setId($value)
    {
        $this->_id = null;

        if (!empty($value) || Type::isNumeric($value))
            $this->_id = $value;

        return $this;
    }

    /**
     * Возвращает путь до скрипта расширения.
     * @return string
     */
    public function getScript()
    {
        return $this->_script;
    }

    /**
     * Устанавливает путь до скрипта расширения.
     * @param string|Path $value
     * @return $this
     */
    public function setScript($value)
    {
        $this->_script = null;

        if (!empty($value) || Type::isNumeric($value))
            $this->_script = Path::from($value)
                ->toRelative()
                ->asAbsolute()
                ->getValue('/');

        return $this;
    }

    /**
     * Возвращает путь до стилей расширения.
     * @return string
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * Устанавливает путь до стилей расширения.
     * @param string|Path $value
     * @return $this
     */
    public function setStyle($value)
    {
        $this->_style = null;

        if (!empty($value) || Type::isNumeric($value))
            $this->_style = Path::from($value)
                ->toRelative()
                ->asAbsolute()
                ->getValue('/');

        return $this;
    }

    /**
     * Возвращает зависимости расширения.
     * @return array
     */
    public function getDependencies()
    {
        return $this->_dependencies;
    }

    /**
     * Устанавливает зависимости расширения.
     * @param array $value
     * @return $this
     */
    public function setDependencies($value)
    {
        $this->_dependencies = [];

        if (!empty($value) && Type::isArray($value))
            $this->_dependencies = $value;

        return $this;
    }

    /**
     * Проверяет расширение на корректность.
     * @return boolean
     */
    public function verify()
    {
        if ($this->_id === null)
            return false;

        if ($this->_script === null)
            return false;

        return true;
    }

    /**
     * Преобразует в расширение CJSCore.
     * @return array
     */
    public function toCJSExtension()
    {
        $array = [];
        $array['js'] = $this->script;

        if (!empty($this->style))
            $array['css'] = $this->style;

        if (!empty($this->dependencies))
            $array['rel'] = $this->dependencies;

        return $array;
    }
}