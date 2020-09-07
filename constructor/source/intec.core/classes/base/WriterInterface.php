<?php
namespace intec\core\base;

/**
 * Интерфейс механизма записи.
 * Interface WriterInterface
 * @package intec\core\base
 * @author apocalypsisdimon@gmail.com
 */
interface WriterInterface
{
    /**
     * Принимает данные и записывает их.
     * Принимает неограниченное число параметров.
     * @param mixed ...$data
     */
    public function write(...$data);
}