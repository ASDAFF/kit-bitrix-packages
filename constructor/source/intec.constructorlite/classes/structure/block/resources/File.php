<?php
namespace intec\constructor\structure\block\resources;

use intec\core\base\InvalidParamException;
use intec\core\base\BaseObject;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;
use intec\constructor\structure\block\Resources;

/**
 * Class Resolution
 * @property integer $width Только для чтения.
 * @property integer $height Только для чтения.
 * @property string $value Только для чтения.
 * @package intec\constructor\structure\block
 */
class File extends BaseObject
{
    /**
     * Ресурсы, к которым относится файл.
     * @var Resources
     */
    protected $_resources;
    /**
     * Имя файла с расширением.
     * @var string
     */
    protected $_name;

    /**
     * File constructor.
     * @param Resources $resources
     * @param Path $path
     */
    public function __construct($resources, $name)
    {
        if (!($resources instanceof Resources))
            throw new InvalidParamException('Resources is not a "'.Resources::className().'" instance.');

        if (empty($name))
            throw new InvalidParamException('Name cannot be empty.');

        $this->_name = $name;
        $this->_resources = $resources;

        parent::__construct([]);
    }

    /**
     * Возвращает ресурсы.
     * @return Resources
     */
    public function getResources()
    {
        return $this->_resources;
    }

    /**
     * Возвращает имя файла.
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Возвращает путь до файла.
     * @param boolean $relative
     * @return Path
     */
    public function getPath($relative = false)
    {
        return $this->getResources()->getDirectory($relative)->add($this->getName());
    }

    /**
     * Проверяет наличие файла.
     * @return boolean
     */
    public function getIsExists()
    {
        return FileHelper::isFile($this->getPath()->getValue());
    }

    /**
     * Удаляет файл.
     * @return bool
     */
    public function delete()
    {
        $path = $this->getPath()->getValue();

        if (FileHelper::isFile($path))
            return unlink($path);

        return true;
    }
}