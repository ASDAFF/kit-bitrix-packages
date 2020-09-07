<?php
namespace intec\constructor\structure\block;

use intec\constructor\structure\block\resources\File;
use intec\Core;
use intec\core\base\Component;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;
use intec\core\web\UploadedFile;

class Resources extends Component
{
    /**
     * Директория с ресурсами.
     * @var Path
     */
    protected $_directory;

    /**
     * Resources constructor.
     * @param Path|string $directory
     */
    public function __construct($directory)
    {
        $this->_directory = Path::from($directory);

        parent::__construct([]);
    }

    /**
     * Директория с ресурсами.
     * @param bool $relative
     * @return Path
     */
    public function getDirectory($relative = false)
    {
        $directory = $this->_directory;

        if ($relative)
            $directory = $directory
                ->toRelative()
                ->asAbsolute();

        return $directory;
    }

    /**
     * Добавляет файл в ресурсы.
     * Может добавить как из пути, так и из UploadedFile.
     * @param UploadedFile|string $from
     * @return File
     */
    public function addFile($from)
    {
        $directory = $this->getDirectory();

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
            $file = new File($this, $name);

            while ($file->getIsExists()) {
                $name = Core::$app->security->generateRandomString(20).'.'.$extension;
                $file = new File($this, $name);
            }

            copy($from, $file->getPath());

            if ($file->getIsExists())
                return $file;
        }

        return null;
    }

    /**
     * Возвращает список хранимых файлов.
     * @return array
     */
    public function getFiles()
    {
        $result = [];
        $directory = $this->getDirectory();
        $entries = FileHelper::getDirectoryEntries($directory->getValue());

        foreach ($entries as $entry) {
            $path = $directory->add($entry);

            if (FileHelper::isFile($path->getValue()))
                $result[] = new File($this, $entry);
        }

        return $result;
    }
}