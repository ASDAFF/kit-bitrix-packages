<?php
namespace intec\constructor\models\build;
IncludeModuleLangFile(__FILE__);

use intec\Core;
use intec\core\base\Component;
use intec\core\base\InvalidParamException;
use intec\core\helpers\FileHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\gallery\File as GalleryFile;
use intec\core\web\UploadedFile;

/**
 * Класс для управления файлами галереи.
 * Class Gallery
 * @property Build $build
 * @property string $directory
 * @package intec\constructor\models
 */
class Gallery extends Component
{
    /**
     * Директория относительно сборки.
     */
    const DIRECTORY_RELATIVE_BUILD = 0;
    /**
     * Директория относительно сайта.
     */
    const DIRECTORY_RELATIVE_SITE = 1;
    /**
     * Директория абсолютная.
     */
    const DIRECTORY_ABSOLUTE = 2;

    /**
     * @var Build
     */
    protected $_build;
    /**
     * @var string
     */
    protected $_directory = 'images/gallery';

    /**
     * Gallery constructor.
     * @param Build $build
     * @throws InvalidParamException
     */
    public function __construct($build)
    {
        parent::__construct([]);

        if (!$build instanceof Build)
            throw new InvalidParamException('Invalid Build for '.self::className());

        $this->_build = $build;
    }

    /**
     * Возвращает сборку галереи.
     * @return Build
     */
    public function getBuild()
    {
        return $this->_build;
    }

    /**
     * Возвращает директорию галереи.
     * @param int $relative
     * @param string $separator
     * @return string
     */
    public function getDirectory($relative = self::DIRECTORY_RELATIVE_SITE, $separator = DIRECTORY_SEPARATOR)
    {
        $result = null;

        if ($relative === self::DIRECTORY_RELATIVE_BUILD) {
            $result = $this->_directory;
        } else {
            $relative = $relative === self::DIRECTORY_RELATIVE_SITE ? true : false;
            $result = $this->getBuild()->getDirectory(false, $relative).$separator.$this->_directory;
        }

        $result = FileHelper::normalizePath($result, $separator);

        return $result;
    }

    /**
     * Добавляет файл в галерею.
     * Может добавить как из пути, так и из UploadedFile.
     * @param UploadedFile|string $from
     * @return GalleryFile
     */
    public function addFile($from)
    {
        $directory = $this->getDirectory(self::DIRECTORY_ABSOLUTE);

        if (!FileHelper::isDirectory($directory))
            FileHelper::createDirectory($directory, 0755, true);

        if (!FileHelper::isDirectory($directory))
            return null;

        $name = null;
        $extension = null;

        if ($from instanceof UploadedFile) {
            $name = $from->name;
            $extension = $from->getExtension();
            $from = $from->tempName;
        } else {
            $name = FileHelper::getEntryName($from);
            $extension = FileHelper::getFileExtension($name);
        }

        if (FileHelper::isFile($from)) {
            $file = new GalleryFile($this, $name);

            while ($file->isExists()) {
                $name = Core::$app->security->generateRandomString(20).'.'.$extension;
                $file = new GalleryFile($this, $name);
            }

            copy($from, $file->getPath(self::DIRECTORY_ABSOLUTE));

            if ($file->isExists())
                return $file;
        }

        return null;
    }

    /**
     * Возвращает список файлов директории.
     * @return GalleryFile[]
     */
    public function getFiles()
    {
        $directory = $this->getDirectory(Gallery::DIRECTORY_ABSOLUTE);
        $entries = FileHelper::getDirectoryEntries($directory, false);
        $result = [];

        foreach ($entries as $entry) {
            $path = $directory.DIRECTORY_SEPARATOR.$entry;

            if (FileHelper::isFile($path))
                $result[] = new GalleryFile($this, $entry);
        }

        return $result;
    }
}