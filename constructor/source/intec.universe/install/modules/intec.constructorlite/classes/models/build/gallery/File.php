<?php
namespace intec\constructor\models\build\gallery;
IncludeModuleLangFile(__FILE__);

use intec\core\base\Component;
use intec\core\base\InvalidParamException;
use intec\constructor\models\build\Gallery;
use intec\core\helpers\FileHelper;

/**
 * Класс, представляющий файл галереи.
 * Class File
 * @property Gallery $gallery
 * @property string $directory
 * @property string $name
 * @property string $path
 * @package intec\constructor\models
 */
class File extends Component
{
    /**
     * @var Gallery $_build
     */
    protected $_gallery;
    protected $_name;

    /**
     * Gallery constructor.
     * @param Gallery $build
     * @param string $name
     * @throws InvalidParamException
     */
    public function __construct($build, $name)
    {
        parent::__construct([]);

        if (!$build instanceof Gallery)
            throw new InvalidParamException('Invalid Gallery for '.self::className());

        $this->_gallery = $build;
        $this->_name = $name;
    }

    public function getGallery()
    {
        return $this->_gallery;
    }

    /**
     * Возвращает директорию файла.
     * @param int $relative
     * @param string $separator
     * @return string
     */
    public function getDirectory($relative = Gallery::DIRECTORY_RELATIVE_SITE, $separator = DIRECTORY_SEPARATOR)
    {
        return $this->getGallery()->getDirectory($relative, $separator);
    }

    /**
     * Возвращает наименование файла.
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Возвращает путь файла.
     * @param int $relative
     * @param string $separator
     * @return string
     */
    public function getPath($relative = Gallery::DIRECTORY_RELATIVE_SITE, $separator = DIRECTORY_SEPARATOR)
    {
        return $this->getDirectory($relative, $separator).$separator.$this->getName();
    }

    /**
     * Проверяет файл на существование.
     * @return bool
     */
    public function isExists()
    {
        return FileHelper::isFile($this->getPath(Gallery::DIRECTORY_ABSOLUTE));
    }

    /**
     * Удаляет файл.
     * @return bool
     */
    public function delete()
    {
        if ($this->isExists())
            return unlink($this->getPath(Gallery::DIRECTORY_ABSOLUTE));

        return true;
    }
}