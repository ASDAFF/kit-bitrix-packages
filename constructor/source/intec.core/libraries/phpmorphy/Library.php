<?php
namespace intec\core\libraries\phpmorphy;

use intec\core\helpers\Encoding;

/**
 * Class Library
 * @inheritdoc
 * @package intec\core\libraries\phpmorphy
 */
class Library extends \intec\core\base\Library
{
    /**
     * @inheritdoc
     */
    protected static $_instance;

    /**
     * @inheritdoc
     */
    public function load()
    {
        if ($this->getIsLoaded())
            return true;

        include(__DIR__.'/distribution/common.php');

        return $this->getIsLoaded();
    }

    /**
     * @inheritdoc
     */
    public function getIsLoaded()
    {
        return class_exists('phpMorphy');
    }

    /**
     * Возвращает путь до словарей.
     * @return string
     */
    public function getDictionariesDirectory()
    {
        return __DIR__.'/dictionaries/'.Encoding::getDefault();
    }
}