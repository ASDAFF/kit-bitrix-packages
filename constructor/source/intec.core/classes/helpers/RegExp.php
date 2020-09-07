<?php
namespace intec\core\helpers;

class RegExp
{
    protected $pattern = '';

    protected $isGlobal = false;
    protected $isInsensitive = false;
    protected $isMultiLine = false;
    protected $isDotAll = false;
    protected $isUnicode = false;

    /**
     * Сравнивает переданное значение с шаблоном.
     *
     * @param string $pattern
     * @param string $subject
     * @param bool $global
     * @param int $offset
     * @return bool
     */
    public static function isMatchBy($pattern, $subject, $global = false, $offset = 0) {
        $global = Type::toBoolean($global);
        $matches = null;

        if ($global) {
            return Type::toBoolean(preg_match_all(
                Type::toString($pattern),
                Type::toString($subject),
                $matches,
                PREG_PATTERN_ORDER,
                Type::toInteger($offset)
            ));
        } else {
            return Type::toBoolean(preg_match(
                Type::toString($pattern),
                Type::toString($subject),
                $matches,
                0,
                Type::toInteger($offset)
            ));
        }
    }

    /**
     * Возвращает найденную группу.
     *
     * @param string $pattern
     * @param string $subject
     * @param int number
     * @param bool $global
     * @param int $offset
     * @return null|string
     */
    public static function matchBy($pattern, $subject, $number = 0, $global = false, $offset = 0) {
        $global = Type::toBoolean($global);
        $number = Type::toInteger($number);
        $matches = [];

        if ($global) {
            preg_match_all(
                Type::toString($pattern),
                Type::toString($subject),
                $matches,
                PREG_PATTERN_ORDER,
                Type::toInteger($offset)
            );
        } else {
            preg_match(
                Type::toString($pattern),
                Type::toString($subject),
                $matches,
                0,
                Type::toInteger($offset)
            );
        }

        return ArrayHelper::getValue($matches, $number, null);
    }

    /**
     * Возвращает найденные совпадения.
     *
     * @param string $pattern
     * @param string $subject
     * @param bool $global
     * @param int $offset
     * @return array
     */
    public static function matchesBy($pattern, $subject, $global = false, $offset = 0) {
        $global = Type::toBoolean($global);
        $matches = [];

        if ($global) {
            preg_match_all(
                Type::toString($pattern),
                Type::toString($subject),
                $matches,
                PREG_PATTERN_ORDER,
                Type::toInteger($offset)
            );
        } else {
            preg_match(
                Type::toString($pattern),
                Type::toString($subject),
                $matches,
                0,
                Type::toInteger($offset)
            );
        }

        return $matches;
    }

    /**
     * Заменяет все найденные вхождения по шаблону.
     *
     * @param string $pattern
     * @param string $replacement
     * @param string $subject
     * @param int $limit
     * @param null $count
     * @return mixed
     */
    public static function replaceBy($pattern, $replacement, $subject, $limit = -1, &$count = null) {
        return preg_replace(
            Type::toString($pattern),
            Type::toString($replacement),
            Type::toString($subject),
            Type::toInteger($limit),
            $count
        );
    }

    /**
     * Создает экземпляр класса регулярных выражений.
     *
     * RegExp constructor.
     * @param string $pattern
     */
    public function __construct($pattern) {
        $this->pattern($pattern);
    }

    /**
     * Устанавливает шаблон или возвращает его исходный вид.
     *
     * @param null $pattern
     * @return RegExp|string
     */
    public function pattern($pattern = null) {
        if ($pattern === null) {
            return Type::toString($this->pattern);
        } else {
            $this->pattern = Type::toString($pattern);
        }

        return $this;
    }

    /**
     * Устанавливает флаг глобального поиска.
     *
     * @param bool $state
     * @return RegExp|bool
     */
    public function isGlobal($state = null) {
        if ($state === null) {
            return Type::toBoolean($this->isGlobal);
        } else {
            $this->isGlobal = Type::toBoolean($state);
        }

        return $this;
    }

    /**
     * Устанавливает флаг чувствительности к регистру.
     *
     * @param bool $state
     * @return RegExp|bool
     */
    public function isInsensitive($state = null) {
        if ($state === null) {
            return Type::toBoolean($this->isInsensitive);
        } else {
            $this->isInsensitive = Type::toBoolean($state);
        }

        return $this;
    }

    /**
     * Устанавливает флаг многострочного поиска.
     *
     * @param null $state
     * @return RegExp|bool
     */
    public function isMultiLine($state = null) {
        if ($state === null) {
            return Type::toBoolean($this->isMultiLine);
        } else {
            $this->isMultiLine = Type::toBoolean($state);
        }

        return $this;
    }

    /**
     * Устанавливает флаг, при котором точка является всеми символами.
     *
     * @param null $state
     * @return RegExp|bool
     */
    public function isDotAll($state = null) {
        if ($state === null) {
            return Type::toBoolean($this->isDotAll);
        } else {
            $this->isDotAll = Type::toBoolean($state);
        }

        return $this;
    }

    /**
     * Устанавливает флаг, при котором включается поддержка UTF-8
     *
     * @param null $state
     * @return RegExp|bool
     */
    public function isUnicode($state = null) {
        if ($state === null) {
            return Type::toBoolean($this->isUnicode);
        } else {
            $this->isUnicode = Type::toBoolean($state);
        }

        return $this;
    }

    /**
     * Возвращает установленные флаги в виде строки.
     *
     * @return string
     */
    public function getFlags() {
        $flags = '';

        if ($this->isInsensitive)
            $flags .= 'i';

        if ($this->isMultiLine)
            $flags .= 'm';

        if ($this->isDotAll)
            $flags .= 's';

        if ($this->isUnicode)
            $flags .= 'u';

        return $flags;
    }

    /**
     * Возвращает построенный шаблон.
     *
     * @param bool $flags
     * @return string
     */
    public function getPattern($flags = true) {
        $pattern = '/'.$this->pattern.'/';

        if ($flags)
            $pattern .= $this->getFlags();

        return $pattern;
    }

    /**
     * Сравнивает значение.
     *
     * @param string $subject
     * @param int $offset
     * @return bool
     */
    public function isMatch($subject, $offset = 0) {
        return static::isMatchBy(
            $this->getPattern(true),
            $subject,
            $this->isGlobal(),
            $offset
        );
    }

    /**
     * Возвращает найденное вхождение.
     *
     * @param string $subject
     * @param int $number
     * @param int $offset
     * @return null|string
     */
    public function match($subject, $number = 0, $offset = 0)
    {
        return static::matchBy(
            $this->getPattern(true),
            $subject,
            $number,
            $this->isGlobal(),
            $offset
        );
    }

    /**
     * Возвращает найденные вхождения.
     *
     * @param string $subject
     * @param int $offset
     * @return array
     */
    public function matches($subject, $offset = 0) {
        return static::matchesBy(
            $this->getPattern(true),
            $subject,
            $this->isGlobal(),
            $offset
        );
    }

    /**
     * Заменяет replacement на subject по шаблону.
     *
     * @param string $replacement
     * @param string $subject
     * @param int $limit
     * @param null|int $count
     * @return mixed
     */
    public function replace($replacement, $subject, $limit = -1, &$count = null) {
        return static::replaceBy(
            $this->getPattern(true),
            $replacement,
            $subject,
            $limit,
            $count
        );
    }

    /**
     * Экранирует специальные символы.
     *
     * @param $subject
     * @return string
     */
    public static function escape($subject) {
        return preg_quote(Type::toString($subject), '/');
    }
}