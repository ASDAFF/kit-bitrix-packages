<?php
namespace intec\core\io;

use intec\Core;
use intec\core\base\BaseObject;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * Представляет путь до элемента файловой системы.
 * Class Path
 * @property string $value Значение пути. Только для чтения.
 * @property integer $level Уровень пути. Только для чтения.
 * @property Path|null $parent Возвращает ролительский путь. Только для чтения.
 * @property integer $isEmpty Путь пустой. Только для чтения.
 * @property integer $isAbsolute Путь абсолютный. Только для чтения.
 * @property integer $isRelative Путь относительный. Только для чтения.
 * @package intec\core\io
 * @since 1.0.0
 * @author apocalypsisdimon@gmail.com
 */
class Path extends BaseObject
{
    /**
     * Ращделитель по умолчанию.
     * @var string
     * @since 1.0.0
     */
    const SEPARATOR = DIRECTORY_SEPARATOR;

    /**
     * Шаблон регулярного выражения начала пути.
     * @var string
     * @since 1.0.0
     */
    protected static $expression = '/^(([A-Za-z]:)|(\/))/';

    /**
     * Путь до элемента файловой системы.
     * @var string
     * @since 1.0.0
     */
    protected $_value;

    /**
     * Инициализирует путь из переменной.
     * @param string|Path|null $path Путь до файла.
     * @return Path
     * @since 1.0.0
     */
    public static function from($path)
    {
        if ($path instanceof static)
            return $path;

        return new Path($path);
    }

    /**
     * Нормализует путь, убирая лишние части и приводя разделители к 1 виду.
     * @param string $path Путь.
     * @param boolean $relative Является относительным путем.
     * @param string $separator Разделитель.
     * @return string
     * @since 1.0.0
     */
    public static function normalize($path, $relative = false, $separator = self::SEPARATOR)
    {
        $path = Type::toString($path);
        $path = Core::getAlias($path);
        $path = strtr($path, '/\\', $separator.$separator);

        if (strpos($separator.$path, "{$separator}.") === false && strpos($path, "{$separator}{$separator}") === false) {
            if ($path !== $separator)
                $path = rtrim($path, $separator);

            if ($relative)
                if (strpos($path, $separator) === 0)
                    $path = substr($path, 1);

            return $path;
        }

        $parts = [];
        $absolute = strpos($path, $separator) === 0;

        foreach (explode($separator, $path) as $part) {
            if ($part === '..' && !empty($parts) && end($parts) !== '..') {
                array_pop($parts);
            } else if ($part === '.' || $part === '') {
                continue;
            } else {
                $parts[] = $part;
            }
        }

        $path = implode($separator, $parts);

        if ($absolute && !$relative)
            $path = $separator.$path;

        return $path === '' ? '.' : $path;
    }

    /**
     * Возвращает части пути до элемента файловой системы.
     * @param string $path Путь до элемента файловой системы.
     * @param string $separator Разделитель.
     * @return array
     * @since 1.0.0
     */
    public static function getPartsFrom($path, $separator = self::SEPARATOR)
    {
        $path = static::normalize($path, $separator);
        $parts = pathinfo($path);
        $result = [];

        $result['directory'] = ArrayHelper::getValue($parts, 'dirname');
        $result['name'] = [
            'base' => ArrayHelper::getValue($parts, 'basename'),
            'short' => ArrayHelper::getValue($parts, 'filename')
        ];
        $result['extension'] = ArrayHelper::getValue($parts, 'extension');

        return $result;
    }

    /**
     * Возвращает директорию пути элемента файловой системы.
     * @param string $path Путь до элемента файловой системы.
     * @param string $separator Разделитель.
     * @return string
     * @since 1.0.0
     */
    public static function getDirectoryFrom($path, $separator = self::SEPARATOR)
    {
        $path = static::normalize($path, false, $separator);
        $path = pathinfo($path, PATHINFO_DIRNAME);

        return $path;
    }

    /**
     * Возвращает имя элемента файловой системы.
     * @param string $path Путь до элемента файловой системы.
     * @param boolean $base Возвращать полное имя (с расширением).
     * @return string
     * @since 1.0.0
     */
    public static function getNameFrom($path, $base = true)
    {
        $path = static::normalize($path);
        $path = pathinfo($path, $base ? PATHINFO_BASENAME : PATHINFO_FILENAME);

        return $path;
    }

    /**
     * Возвращает расширение элемента файловой системы.
     * @param string $path Путь до элемента файловой системы.
     * @return string
     * @since 1.0.0
     */
    public static function getExtensionFrom($path)
    {
        $path = static::normalize($path);
        $path = pathinfo($path, PATHINFO_EXTENSION);

        return $path;
    }

    /**
     * Возвращает путь относительно другого пути.
     * @param string $path Путь до элемента файловой системы.
     * @param string $from Путь, относительно которого рассчитать новый.
     * @param string $separator Разделитель.
     * @return string
     * @since 1.0.0
     */
    public static function getRelative($path, $from, $separator = self::SEPARATOR)
    {
        $path = static::normalize($path, true, $separator);
        $from = static::normalize($from, true, $separator);

        if ((empty($path) && !Type::isNumeric($path)) || (empty($from) && !Type::isNumeric($from)))
            return $path;

        $from = explode($separator, $from);
        $path = explode($separator, $path);
        $offset = 0;

        foreach ($path as $key => $currentPart) {
            $part = ArrayHelper::getValue($from, $key);

            if ($part != $currentPart)
                break;

            $offset++;
        }

        if ($offset > 0)
            $path = ArrayHelper::slice($path, $offset);

        $length = count($from) - $offset;

        for ($i = 0; $i < $length; $i++)
            ArrayHelper::unshift($path, '..');

        return implode($separator, $path);
    }

    /**
     * Path constructor.
     * @param string $value Путь до элемента файловой системы.
     * @since 1.0.0
     */
    public function __construct($value)
    {
        if (empty($value) && !Type::isNumeric($value))
            $value = '';

        $value = Core::getAlias($value);
        $this->_value = static::normalize($value, false, '/');

        parent::__construct([]);
    }

    /**
     * Объеденяет текущий путь с указанным, создавая новый путь.
     * @param Path|string $path Путь, с которым происходит объединение.
     * @return Path
     * @since 1.0.0
     */
    public function add($path)
    {
        $path = static::from($path);
        $path = $path->asRelative()->_value;
        $value = $this->_value;

        if ($this->getIsAbsolute() && $this->getLevel() === 0) {
            $path = $value.$path;
        } else if (!empty($value) || Type::isNumeric($value)) {
            $path = $value.'/'.$path;
        }

        return static::from($path);
    }

    /**
     * Возвращает значение пути.
     * @param string $separator Разделитель.
     * @return string
     * @since 1.0.0
     */
    public function getValue($separator = self::SEPARATOR)
    {
        return static::normalize(
            $this->_value,
            false,
            $separator
        );
    }

    /**
     * Возвращает имя элемента файловой системы.
     * @param boolean $base Вернуть полное имя.
     * @return string
     * @since 1.0.0
     */
    public function getName($base = true)
    {
        return static::getNameFrom($this->_value, $base);
    }

    /**
     * Возвращает расширение элемента файловой системы.
     * @return string
     * @since 1.0.0
     */
    public function getExtension()
    {
        return static::getExtensionFrom($this->_value);
    }

    /**
     * Возвращает ролительский путь.
     * @return Path|null
     * @since 1.0.0
     */
    public function getParent()
    {
        if ($this->getLevel() > 0) {
            $value = $this->_value;
            $value = static::getDirectoryFrom($value);

            return Path::from($value);
        }

        return null;
    }

    /**
     * Возвращает уровень вложенности пути.
     * @return integer
     * @since 1.0.0
     */
    public function getLevel()
    {
        $value = $this->_value;
        $level = substr_count($value, '/');

        if ($this->getIsRelative()) {
            if (!empty($value) || Type::isNumeric($value))
                $level++;
        } else if ($value === '/') {
            $level--;
        }

        return $level;
    }

    /**
     * Возвращает другой путь относительно данного.
     * @param Path|string $path Путь, который необходимо вычислить отночительно данного.
     * @return Path
     * @since 1.0.0
     */
    public function getRelativeTo($path)
    {
        $path = static::getRelative($path, $this->_value, '/');
        $path = static::from($path);

        return $path;
    }

    /**
     * Возвращает данный путь относительно другого.
     * @param Path|string $path Путь, относительно которого вычислять.
     * @return Path
     * @since 1.0.0
     */
    public function getRelativeFrom($path)
    {
        $path = static::getRelative($this->_value, $path, '/');
        $path = static::from($path);

        return $path;
    }

    /**
     * Возвращает путь относительно корня сайта, учитывая символические ссылки.
     * Если путь не совпадает с корневым или ссылочным, то возвращает просто относительный путь.
     * @return Path
     */
    public function toRelative()
    {
        $path = $this->getValue();
        $result = null;

        $rootPath = Path::from('@root');
        $rootLength = StringHelper::length($rootPath->getValue());

        $linkPath = Path::from('@root/linked');
        $linkLength = StringHelper::length($linkPath->getValue());

        if ($rootLength > $linkLength) {
            if (StringHelper::startsWith($path, $rootPath)) {
                $result = StringHelper::cut($path, $rootLength);
            } else if (StringHelper::startsWith($path, $linkPath)) {
                $result = StringHelper::cut($path, $linkLength);
            }
        } else {
            if (StringHelper::startsWith($path, $linkPath)) {
                $result = StringHelper::cut($path, $linkLength);
            } else if (StringHelper::startsWith($path, $rootPath)) {
                $result = StringHelper::cut($path, $rootLength);
            }
        }

        if (empty($result))
            $result = $this->asRelative();

        $result = Path::from($result)->asRelative();

        return $result;
    }

    /**
     * Возвращает данный путь как абсолютный.
     * @return Path
     * @since 1.0.0
     */
    public function asAbsolute()
    {
        if ($this->getIsRelative())
            return static::from('/'.$this->_value);

        return $this;
    }

    /**
     * Возвращает данный путь как относительный.
     * @return Path
     * @since 1.0.0
     */
    public function asRelative()
    {
        if ($this->getIsAbsolute()) {
            $value = $this->_value;
            $match = RegExp::matchBy(static::$expression, $value);
            $value = substr($value, strlen($match));

            return static::from($value);
        }

        return $this;
    }

    /**
     * Сравнивает данный путь с другим путем.
     * @param Path|string $path Путь, с которым сравнивать.
     * @return boolean
     * @since 1.0.0
     */
    public function isEqual($path)
    {
        $path = static::from($path);
        return $this->getValue() === $path->getValue();
    }

    /**
     * Путь пустой.
     * @return boolean
     * @since 1.0.0
     */
    public function getIsEmpty()
    {
        return empty($this->value) && !Type::isNumeric($this->value);
    }

    /**
     * Путь абсолютный.
     * @return boolean
     * @since 1.0.0
     */
    public function getIsAbsolute()
    {
        return RegExp::isMatchBy(static::$expression, $this->_value);
    }

    /**
     * Путь относительный.
     * @return boolean
     * @since 1.0.0
     */
    public function getIsRelative()
    {
        return !$this->getIsAbsolute();
    }

    /**
     * Возвращает значение пути.
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        return $this->getValue();
    }
}