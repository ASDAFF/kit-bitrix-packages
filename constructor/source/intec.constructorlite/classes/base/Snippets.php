<?php
namespace intec\constructor\base;

use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use ReflectionClass;
use ReflectionException;

/**
 * Коллекция снипетов.
 * Class Snippets
 * @package intec\constructor\base
 */
class Snippets extends Collection
{
    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return $item instanceof Snippet;
    }
}