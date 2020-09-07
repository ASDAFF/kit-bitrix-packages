<?php
namespace intec\constructor\base;

/**
 * Интерфейс, определяющий возможность экспорта модели.
 * Interface Exportable
 * @package intec\constructor\base
 */
interface Exportable
{
    /**
     * Экспортирует модель как массив.
     * @return array
     */
    public function export();
}