<?php
namespace intec\constructor\base;

/**
 * Интерфейс, определяющий возможность импорта модели.
 * Interface Exportable
 * @package intec\constructor\base
 */
interface Importable
{
    /**
     * Импортирует модель из массива.
     * @param array $data Данные для импорта в массиве.
     * @return bool
     */
    public function import($data);
}