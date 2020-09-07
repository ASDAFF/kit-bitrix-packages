<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\core\base\Component;
use intec\core\base\InvalidParamException;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\constructor\models\Build;


/**
 * Класс для управления файлами галереи.
 * Class Gallery
 * @property Build $build
 * @property string $type
 * @property string $path
 * @package intec\constructor\models
 */
class File extends Component
{
    /**
     * Тип: Стиль Css
     */
    const TYPE_CSS = 'css';
    /**
     * Тип: Скрипт JavaScript
     */
    const TYPE_JAVASCRIPT = 'javascript';
    /**
     * Тип: Стиль SASS (SCSS)
     */
    const TYPE_SCSS = 'scss';

    /**
     * Возвращает список типов файлов.
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_CSS => GetMessage('intec.constructorlite.models.build.file.type.css'),
            self::TYPE_JAVASCRIPT => GetMessage('intec.constructorlite.models.build.file.type.javascript'),
            self::TYPE_SCSS => GetMessage('intec.constructorlite.models.build.file.type.scss'),
        ];
    }

    /**
     * Возвращает список значений типов файлов.
     * @return array
     */
    public static function getTypesValues()
    {
        $values = self::getTypes();
        $values = ArrayHelper::getKeys($values);

        return $values;
    }

    /**
     * @var Build
     */
    protected $_build;
    /**
     * @var string
     */
    protected $_type;
    /**
     * @var string
     */
    protected $_path;

    /**
     * File constructor.
     * @param Build $build
     * @param string $type
     * @param string $path
     * @throws InvalidParamException
     */
    public function __construct($build, $type, $path)
    {
        parent::__construct([]);

        if (!$build instanceof Build)
            throw new InvalidParamException('Invalid Build for '.self::className());

        if (!ArrayHelper::isIn($type, self::getTypesValues()))
            throw new InvalidParamException('Invalid Type for '.self::className());

        $this->_build = $build;
        $this->_type = $type;
        $this->_path = $path;
    }

    /**
     * Возвращает сборку файла.
     * @return Build
     */
    public function getBuild()
    {
        return $this->_build;
    }

    /**
     * Возвращает тип подключаемого файла.
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Возвращает путь до файла.
     * @param bool $relative
     * @param string $separator
     * @param bool $minimized
     * @return mixed
     */
    public function getPath($relative = false, $separator = DIRECTORY_SEPARATOR, $minimized = false)
    {
        $directory = $this->getBuild()->getDirectory(false, false, $separator);
        $path = $this->_path;

        if ($minimized) {
            $parts = FileHelper::getPathParts($path, $separator);
            $path = $parts['directory'].$separator.$parts['name']['less'].'.min.'.$parts['extension'];

            if (!FileHelper::isFile($directory.$separator.$path))
                $path = $this->_path;
        }

        return $this->getBuild()->getDirectory(
            false,
            $relative,
            $separator
        ).$separator.$path;
    }

    /**
     * Проверка существования файла.
     * @return bool
     */
    public function isExists()
    {
        return FileHelper::isFile($this->getPath());
    }
}