<?php
namespace intec\constructor\base;

use intec\constructor\base\snippet\Language;
use intec\constructor\base\snippet\Meta;
use intec\core\base\Component;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;

/**
 * Class Snippet
 * @property integer $id
 * @property string $code
 * @property string $namespace
 * @property string $directory
 * @property Language $language
 * @package intec\constructor\base
 */
abstract class ScannableSnippet extends Snippet
{
    /**
     * Идентификатор снипета.
     * @var string
     */
    protected $_id;
    /**
     * Пространство имен снипета.
     * @var string
     */
    protected $_namespace;

    /**
     * Возвращает идентификатор снипета.
     * @return string
     */
    public function getId()
    {
        if ($this->_id === null)
            $this->_id = $this->getDirectory()->getName();

        return $this->_id;
    }

    /**
     * Возвращает код снипета.
     * @return string|false
     */
    public function getCode()
    {
        if ($this->_code === null) {
            $this->_code = false;

            $id = $this->getId();
            $namespace = $this->getNamespace();

            if (!empty($id)) {
                if (!empty($namespace)) {
                    $this->_code = $namespace . ':' . $id;
                } else {
                    $this->_code = $id;
                }
            }
        }

        return $this->_code;
    }

    /**
     * Возвращает пространство имен снипета.
     * @return string|false
     */
    public function getNamespace()
    {
        if ($this->_namespace === null) {
            $this->_namespace = false;

            $directory = $this->getDirectory();

            if ($directory->getLevel() > 1) {
                $directory = $directory
                    ->getParent();

                $this->_namespace = $directory->getName();
            }
        }

        return $this->_namespace;
    }
}