<?php
namespace intec\core\base;

/**
 * Абстрактный класс механизма записи.
 * Class Writer
 * @package intec\core\base
 * @author apocalypsisdimon@gmail.com
 */
abstract class Writer extends BaseObject implements WriterInterface
{
    /**
     * @inheritdoc
     */
    public abstract function write(...$data);
}