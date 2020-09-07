<?php
namespace intec\constructor\base;

/**
 * Интерфейс, определяющий возможность копирования модели.
 * Interface Copyable
 * @package intec\constructor\base
 */
interface Copyable
{
    /**
     * Копирует модель.
     * @return mixed
     */
    public function copy();
}