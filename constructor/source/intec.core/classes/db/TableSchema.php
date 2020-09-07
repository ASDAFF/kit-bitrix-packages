<?php
namespace intec\core\db;

use intec\core\base\BaseObject;
use intec\core\base\InvalidParamException;

/**
 * Представляет собой таблицу базы данных.
 * Class TableSchema
 * @property array $columnNames Список наименований столбцов. Только для чтения.
 * @package intec\core\db
 * @since 1.0.0
 */
class TableSchema extends BaseObject
{
    /**
     * Схема, к которой принадлежит таблица.
     * @var string
     */
    public $schemaName;
    /**
     * Имя таблицы.
     * @var string
     */
    public $name;
    /**
     * Имя таблицы со схемой.
     * @var string
     */
    public $fullName;
    /**
     * @var string[] Первичные ключи таблицы.
     */
    public $primaryKey = [];
    /**
     * @var string Имя раздела первичного ключа. Если нет то `null`.
     */
    public $sequenceName;
    /**
     * Внешние ключи таблицы.
     * @var array
     */
    public $foreignKeys = [];
    /**
     * Метаданные столбцов таблицы. Каждая строка имеет объект [[ColumnSchema]]
     * и проиндексирована по названию столбца.
     * @var ColumnSchema[]
     */
    public $columns = [];


    /**
     * Возвращает метаданные столбца.
     * @param string $name Наименование столбца.
     * @return ColumnSchema Метаданные столбца. Возвращает `null` если столбца не существует.
     */
    public function getColumn($name)
    {
        return isset($this->columns[$name]) ? $this->columns[$name] : null;
    }

    /**
     * Возвращает названия всех столбцов таблицы.
     * @return array Список названий столбцов.
     */
    public function getColumnNames()
    {
        return array_keys($this->columns);
    }

    /**
     * Позволяет вручную установить первичный ключ таблицы.
     * @param string|array $keys Первичный ключь (Может быть композитным).
     * @throws InvalidParamException Если ключ не найден.
     */
    public function fixPrimaryKey($keys)
    {
        $keys = (array) $keys;
        $this->primaryKey = $keys;
        foreach ($this->columns as $column) {
            $column->isPrimaryKey = false;
        }
        foreach ($keys as $key) {
            if (isset($this->columns[$key])) {
                $this->columns[$key]->isPrimaryKey = true;
            } else {
                throw new InvalidParamException("Primary key '$key' cannot be found in table '{$this->name}'.");
            }
        }
    }
}
