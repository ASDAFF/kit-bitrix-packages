<?php
namespace intec\constructor\structure;

use intec\core\helpers\FileHelper;

/**
 * Class Snippet
 * @package intec\constructor\structure
 */
trait SnippetIconTrait
{
    /**
     * Возвращает путь до иконки.
     * @param boolean $relative
     * @return string
     */
    public function getIconPath($relative = false, $separator = DIRECTORY_SEPARATOR)
    {
        return $this->getMetaPath('icon', null, 'png', $relative, $separator);
    }

    /**
     * Проверяет на существование иконки.
     * @param boolean $relative
     * @return string
     */
    public function hasIcon()
    {
        return FileHelper::isFile($this->getIconPath());
    }
}